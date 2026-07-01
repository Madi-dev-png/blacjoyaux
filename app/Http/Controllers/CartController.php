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

        $suggestions = \App\Models\Product::active()
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

        $this->cart->add($product->id, (int) $request->input('quantity', 1));

        return back()->with('success', "« {$product->name} » a été ajouté à votre panier.");
    }

    public function update(Request $request, Product $product)
    {
        $request->validate(['quantity' => 'required|integer|min:0|max:20']);

        $this->cart->update($product->id, (int) $request->quantity);

        return back()->with('success', 'Panier mis à jour.');
    }

    public function remove(Product $product)
    {
        $this->cart->remove($product->id);

        return back()->with('success', 'Article retiré du panier.');
    }
}
