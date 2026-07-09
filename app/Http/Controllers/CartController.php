<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected CartService $cart) {}

    public function index()
    {
        $items = $this->cart->items();
        $subtotal = $this->cart->subtotal();

        $cartProductIds = collect($items)->pluck('product.id');

        $suggestions = Product::active()
            ->whereNotIn('id', $cartProductIds)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('shop.cart', compact('items', 'subtotal', 'suggestions'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate(['quantity' => 'nullable|integer|min:1|max:20']);

        abort_unless($product->is_active, 404);

        if ($product->stock < 1) {
            return back()->with('error', "« {$product->name} » est actuellement épuisé.");
        }

        $quantity = (int) $request->input('quantity', 1);
        $alreadyInCart = $this->cart->quantityFor($product->id);

        // On ne laisse jamais le panier dépasser le stock réel.
        if ($alreadyInCart + $quantity > $product->stock) {
            $remaining = max(0, $product->stock - $alreadyInCart);

            return back()->with('error', $remaining > 0
                ? "Stock insuffisant pour « {$product->name} » : vous pouvez encore en ajouter {$remaining} au maximum."
                : "Vous avez déjà tout le stock disponible de « {$product->name} » dans votre panier.");
        }

        $this->cart->add($product->id, $quantity);

        return back()->with('success', "« {$product->name} » a été ajouté à votre panier.");
    }

    public function update(Request $request, Product $product)
    {
        $request->validate(['quantity' => 'required|integer|min:0|max:20']);

        $quantity = (int) $request->quantity;

        if ($quantity > $product->stock) {
            return back()->with('error', "Stock insuffisant pour « {$product->name} » : il en reste {$product->stock}.");
        }

        $this->cart->update($product->id, $quantity);

        return back()->with('success', 'Panier mis à jour.');
    }

    public function remove(Product $product)
    {
        $this->cart->remove($product->id);

        return back()->with('success', 'Article retiré du panier.');
    }
}
