<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class StorefrontFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_faq_page_shows_active_questions(): void
    {
        Faq::create([
            'question' => 'Peut-on payer par Wave ou Orange Money ?',
            'answer' => 'Oui, Wave et Orange Money sont acceptés.',
            'category' => 'paiement',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->get('/faq')
            ->assertStatus(200)
            ->assertSee('Peut-on payer par Wave ou Orange Money ?');
    }

    public function test_product_search_endpoint_returns_matching_product(): void
    {
        $product = Product::factory()->create(['name' => 'Sac Bureau Femme – Marron', 'is_active' => true]);
        Product::factory()->create(['name' => 'Pochette Soirée', 'is_active' => true]);

        $response = $this->getJson('/recherche?search=Bureau');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment(['name' => $product->name]);
    }

    public function test_product_search_endpoint_ignores_short_terms(): void
    {
        Product::factory()->create(['name' => 'Sac Bureau Femme', 'is_active' => true]);

        $this->getJson('/recherche?search=a')->assertJson([]);
    }

    public function test_cart_page_shows_whatsapp_cta_when_it_has_items(): void
    {
        $product = Product::factory()->create(['is_active' => true, 'stock' => 5]);

        $this->post(route('cart.add', $product), ['quantity' => 1]);

        $this->get('/panier')
            ->assertStatus(200)
            ->assertSee('Finaliser ma commande sur WhatsApp');
    }

    public function test_product_page_shows_story_block_only_when_present(): void
    {
        $withStory = Product::factory()->create(['is_active' => true, 'story' => 'Le leadership se construit chaque jour.']);
        $withoutStory = Product::factory()->create(['is_active' => true, 'story' => null]);

        $this->get(route('products.show', $withStory))
            ->assertSee('histoire cachée derrière ce sac');

        $this->get(route('products.show', $withoutStory))
            ->assertDontSee('histoire cachée derrière ce sac');
    }

    public function test_wishlist_toggle_adds_and_removes_product(): void
    {
        $product = Product::factory()->create(['is_active' => true]);

        $add = $this->postJson(route('wishlist.toggle', $product));
        $add->assertStatus(200)->assertJson(['added' => true, 'count' => 1]);

        $this->get('/favoris')->assertStatus(200)->assertSee($product->name);

        $remove = $this->postJson(route('wishlist.toggle', $product));
        $remove->assertStatus(200)->assertJson(['added' => false, 'count' => 0]);

        $this->get('/favoris')->assertStatus(200)->assertDontSee($product->name);
    }

    public function test_wishlist_page_shows_empty_state_by_default(): void
    {
        $this->get('/favoris')
            ->assertStatus(200)
            ->assertSee('pas encore ajouté de sac à vos favoris');
    }

    public function test_chat_assistant_greets_and_answers_product_questions(): void
    {
        $product = Product::factory()->create([
            'name' => 'Sac Bureau Femme – Cognac',
            'is_active' => true,
            'price' => 55000,
            'stock' => 4,
        ]);

        $greeting = $this->postJson(route('chat.send'), ['message' => 'Bonjour']);
        $greeting->assertStatus(200)->assertJsonFragment(['source' => 'faq']);
        $this->assertStringContainsString('bienvenue', $greeting->json('reply'));

        $priceQuestion = $this->postJson(route('chat.send'), [
            'message' => 'Combien coûte le sac bureau femme cognac ?',
        ]);
        $priceQuestion->assertStatus(200);
        $this->assertStringContainsString('55 000 F CFA', $priceQuestion->json('reply'));
        $this->assertStringContainsString($product->name, $priceQuestion->json('reply'));
    }

    public function test_chat_assistant_calls_anthropic_api_when_a_key_is_configured(): void
    {
        config(['services.anthropic.key' => 'sk-ant-test-key']);

        Http::fake([
            'api.anthropic.com/*' => Http::response([
                'content' => [
                    ['type' => 'text', 'text' => 'Réponse simulée de Claude.'],
                ],
            ], 200),
        ]);

        $response = $this->postJson(route('chat.send'), ['message' => 'Avez-vous des sacs en cuir ?']);

        $response->assertStatus(200)->assertJson([
            'reply' => 'Réponse simulée de Claude.',
            'source' => 'ia',
        ]);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.anthropic.com/v1/messages'
                && $request->hasHeader('x-api-key', 'sk-ant-test-key')
                && $request->hasHeader('anthropic-version', '2023-06-01')
                && ! empty($request['system'])
                && $request['messages'][0]['role'] === 'user';
        });
    }

    public function test_chat_assistant_falls_back_to_faq_when_the_api_call_fails(): void
    {
        config(['services.anthropic.key' => 'sk-ant-test-key']);

        Http::fake([
            'api.anthropic.com/*' => Http::response([], 500),
        ]);

        $response = $this->postJson(route('chat.send'), ['message' => 'Bonjour']);

        $response->assertStatus(200)->assertJsonFragment(['source' => 'faq']);
        $this->assertStringContainsString('bienvenue', $response->json('reply'));
    }
}
