<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\PromoCode;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PromoCodeTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_promo_code_is_applied_and_reduces_the_total(): void
    {
        $product = Product::factory()->create(['is_active' => true, 'stock' => 5, 'price' => 10000]);
        PromoCode::create(['code' => 'TEST10', 'type' => 'percent', 'value' => 10, 'is_active' => true]);

        $this->post(route('cart.add', $product), ['quantity' => 1]);
        $response = $this->post(route('cart.promo.apply'), ['code' => 'test10']);

        $response->assertSessionHas('success');
        $cart = app(CartService::class);
        $this->assertSame(1000, $cart->discount());
        $this->assertSame(9000, $cart->total());
    }

    public function test_unknown_promo_code_is_rejected(): void
    {
        $product = Product::factory()->create(['is_active' => true, 'stock' => 5, 'price' => 10000]);
        $this->post(route('cart.add', $product), ['quantity' => 1]);

        $response = $this->post(route('cart.promo.apply'), ['code' => 'NEXISTEPAS']);

        $response->assertSessionHas('error');
        $this->assertNull(app(CartService::class)->appliedPromo());
    }

    public function test_expired_promo_code_is_rejected(): void
    {
        $product = Product::factory()->create(['is_active' => true, 'stock' => 5, 'price' => 10000]);
        PromoCode::create(['code' => 'PERIME', 'type' => 'percent', 'value' => 10, 'is_active' => true, 'expires_at' => now()->subDay()]);

        $this->post(route('cart.add', $product), ['quantity' => 1]);
        $response = $this->post(route('cart.promo.apply'), ['code' => 'PERIME']);

        $response->assertSessionHas('error');
        $this->assertNull(app(CartService::class)->appliedPromo());
    }

    public function test_promo_code_below_minimum_subtotal_is_rejected(): void
    {
        $product = Product::factory()->create(['is_active' => true, 'stock' => 5, 'price' => 5000]);
        PromoCode::create(['code' => 'GROSPANIER', 'type' => 'fixed', 'value' => 1000, 'is_active' => true, 'min_subtotal' => 50000]);

        $this->post(route('cart.add', $product), ['quantity' => 1]);
        $response = $this->post(route('cart.promo.apply'), ['code' => 'GROSPANIER']);

        $response->assertSessionHas('error');
        $this->assertNull(app(CartService::class)->appliedPromo());
    }

    public function test_removing_promo_code_clears_the_discount(): void
    {
        $product = Product::factory()->create(['is_active' => true, 'stock' => 5, 'price' => 10000]);
        PromoCode::create(['code' => 'TEST10', 'type' => 'percent', 'value' => 10, 'is_active' => true]);

        $this->post(route('cart.add', $product), ['quantity' => 1]);
        $this->post(route('cart.promo.apply'), ['code' => 'TEST10']);
        $this->delete(route('cart.promo.remove'));

        $this->assertNull(app(CartService::class)->appliedPromo());
        $this->assertSame(0, app(CartService::class)->discount());
    }

    public function test_checkout_applies_the_discount_to_the_order_and_increments_usage(): void
    {
        $product = Product::factory()->create(['is_active' => true, 'stock' => 5, 'price' => 20000]);
        $promo = PromoCode::create(['code' => 'TEST10', 'type' => 'percent', 'value' => 10, 'is_active' => true]);

        $this->post(route('cart.add', $product), ['quantity' => 1]);
        $this->post(route('cart.promo.apply'), ['code' => 'TEST10']);

        $response = $this->post(route('checkout.store'), [
            'customer_name' => 'Aya Test',
            'customer_phone' => '0700000000',
            'shipping_address' => 'Cocody Palmeraie',
            'city' => 'Abidjan',
            'delivery_method' => 'retrait',
            'payment_method' => 'a_la_livraison',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'promo_code' => 'TEST10',
            'discount' => 2000,
            'total' => 18000,
        ]);
        $this->assertSame(1, $promo->fresh()->used_count);
    }

    public function test_checkout_rejects_a_promo_code_that_reached_its_usage_limit_between_cart_and_checkout(): void
    {
        $product = Product::factory()->create(['is_active' => true, 'stock' => 5, 'price' => 10000]);
        $promo = PromoCode::create(['code' => 'LIMITE1', 'type' => 'fixed', 'value' => 500, 'is_active' => true, 'max_uses' => 1, 'used_count' => 0]);

        $this->post(route('cart.add', $product), ['quantity' => 1]);
        $this->post(route('cart.promo.apply'), ['code' => 'LIMITE1']);

        // Le code atteint sa limite juste avant le paiement (ex: une autre cliente vient de l'utiliser).
        $promo->update(['used_count' => 1]);

        $response = $this->post(route('checkout.store'), [
            'customer_name' => 'Aya Test',
            'customer_phone' => '0700000000',
            'shipping_address' => 'Cocody Palmeraie',
            'city' => 'Abidjan',
            'delivery_method' => 'retrait',
            'payment_method' => 'a_la_livraison',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', ['discount' => 0, 'promo_code' => null, 'total' => 10000]);
    }
}
