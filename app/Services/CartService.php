<?php

namespace App\Services;

use App\Models\Product;
use App\Models\PromoCode;
use Illuminate\Support\Facades\Session;

/**
 * Panier basé sur la session (guest checkout — aucun compte requis).
 * Stocke : [product_id => quantity].
 */
class CartService
{
    protected string $key = 'cart';

    protected string $promoKey = 'cart_promo_code';

    public function items(): array
    {
        $cart = Session::get($this->key, []);
        if (empty($cart)) {
            return [];
        }

        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');
        $items = [];

        foreach ($cart as $id => $qty) {
            if (! isset($products[$id])) {
                continue;
            }
            $product = $products[$id];
            $items[] = [
                'product' => $product,
                'quantity' => $qty,
                'line_total' => $product->price * $qty,
            ];
        }

        return $items;
    }

    /** Quantité déjà présente au panier pour un produit donné. */
    public function quantityFor(int $productId): int
    {
        return (int) (Session::get($this->key, [])[$productId] ?? 0);
    }

    public function add(int $productId, int $quantity = 1): void
    {
        $cart = Session::get($this->key, []);
        $cart[$productId] = ($cart[$productId] ?? 0) + max(1, $quantity);
        Session::put($this->key, $cart);
    }

    public function update(int $productId, int $quantity): void
    {
        $cart = Session::get($this->key, []);
        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = $quantity;
        }
        Session::put($this->key, $cart);
    }

    public function remove(int $productId): void
    {
        $cart = Session::get($this->key, []);
        unset($cart[$productId]);
        Session::put($this->key, $cart);
    }

    public function clear(): void
    {
        Session::forget($this->key);
        Session::forget($this->promoKey);
    }

    public function subtotal(): int
    {
        return array_sum(array_map(fn ($i) => $i['line_total'], $this->items()));
    }

    public function count(): int
    {
        return array_sum(Session::get($this->key, []));
    }

    /** Tente d'appliquer un code promo au panier. Retourne un message d'erreur, ou null si succès. */
    public function applyPromo(string $code): ?string
    {
        $promo = PromoCode::findUsable($code);

        if (! $promo) {
            return 'Ce code promo n\'existe pas.';
        }

        if ($reason = $promo->invalidReason($this->subtotal())) {
            return $reason;
        }

        Session::put($this->promoKey, $promo->code);

        return null;
    }

    public function removePromo(): void
    {
        Session::forget($this->promoKey);
    }

    /** Code promo actuellement appliqué, uniquement s'il reste valide pour le sous-total courant. */
    public function appliedPromo(): ?PromoCode
    {
        $code = Session::get($this->promoKey);
        if (! $code) {
            return null;
        }

        $promo = PromoCode::findUsable($code);
        if (! $promo || ! $promo->isUsableFor($this->subtotal())) {
            return null;
        }

        return $promo;
    }

    public function discount(): int
    {
        return $this->appliedPromo()?->discountFor($this->subtotal()) ?? 0;
    }

    public function total(): int
    {
        return $this->subtotal() - $this->discount();
    }
}
