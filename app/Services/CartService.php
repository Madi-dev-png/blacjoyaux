<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

/**
 * Panier basé sur la session (guest checkout — aucun compte requis).
 * Stocke : [product_id => quantity].
 */
class CartService
{
    protected string $key = 'cart';

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
    }

    public function subtotal(): int
    {
        return array_sum(array_map(fn ($i) => $i['line_total'], $this->items()));
    }

    public function count(): int
    {
        return array_sum(Session::get($this->key, []));
    }
}
