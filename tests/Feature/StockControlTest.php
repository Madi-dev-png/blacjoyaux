<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_add_is_rejected_when_it_would_exceed_stock(): void
    {
        $product = Product::factory()->create(['is_active' => true, 'stock' => 3]);

        $response = $this->post(route('cart.add', $product), ['quantity' => 5]);

        $response->assertSessionHas('error');
        $this->assertSame(0, app(CartService::class)->quantityFor($product->id));
    }

    public function test_cart_add_is_rejected_once_cumulative_quantity_exceeds_stock(): void
    {
        $product = Product::factory()->create(['is_active' => true, 'stock' => 3]);

        $this->post(route('cart.add', $product), ['quantity' => 2]);
        $response = $this->post(route('cart.add', $product), ['quantity' => 2]);

        $response->assertSessionHas('error');
        $this->assertSame(2, app(CartService::class)->quantityFor($product->id));
    }

    public function test_cart_update_is_rejected_when_quantity_exceeds_stock(): void
    {
        $product = Product::factory()->create(['is_active' => true, 'stock' => 2]);
        $this->post(route('cart.add', $product), ['quantity' => 1]);

        $response = $this->patch(route('cart.update', $product), ['quantity' => 5]);

        $response->assertSessionHas('error');
        $this->assertSame(1, app(CartService::class)->quantityFor($product->id));
    }

    public function test_checkout_revalidates_stock_and_prevents_overselling(): void
    {
        $product = Product::factory()->create(['is_active' => true, 'stock' => 1, 'price' => 50000]);
        $this->post(route('cart.add', $product), ['quantity' => 1]);

        // Le stock baisse entre l'ajout au panier et le paiement (ex: une autre cliente commande entre-temps).
        $product->update(['stock' => 0]);

        $response = $this->post(route('checkout.store'), $this->validCheckoutPayload());

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_items', 0);
        $this->assertSame(0, $product->fresh()->stock);
    }

    public function test_checkout_rejects_an_inactive_product_even_if_still_in_cart(): void
    {
        $product = Product::factory()->create(['is_active' => true, 'stock' => 5, 'price' => 40000]);
        $this->post(route('cart.add', $product), ['quantity' => 1]);

        // Le produit est retiré de la vente entre l'ajout au panier et le paiement.
        $product->update(['is_active' => false]);

        $response = $this->post(route('checkout.store'), $this->validCheckoutPayload());

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_checkout_succeeds_and_decrements_stock_exactly_without_going_negative(): void
    {
        $product = Product::factory()->create(['is_active' => true, 'stock' => 2, 'price' => 30000]);
        $this->post(route('cart.add', $product), ['quantity' => 2]);

        $response = $this->post(route('checkout.store'), $this->validCheckoutPayload());

        $response->assertRedirect();
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 1);
        $this->assertSame(0, $product->fresh()->stock);
    }

    protected function validCheckoutPayload(array $overrides = []): array
    {
        return array_merge([
            'customer_name' => 'Aya Test',
            'customer_phone' => '0700000000',
            'shipping_address' => 'Cocody Palmeraie',
            'city' => 'Abidjan',
            'delivery_method' => 'abidjan',
            'payment_method' => 'a_la_livraison',
        ], $overrides);
    }
}
