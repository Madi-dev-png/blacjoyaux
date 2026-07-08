<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\WishlistService;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function __construct(protected WishlistService $wishlist) {}

    public function index()
    {
        $items = $this->wishlist->items();

        return view('shop.wishlist', compact('items'));
    }

    public function toggle(Request $request, Product $product)
    {
        $added = $this->wishlist->toggle($product->id);

        if ($request->wantsJson()) {
            return response()->json([
                'added' => $added,
                'count' => $this->wishlist->count(),
            ]);
        }

        return back()->with('success', $added
            ? "« {$product->name} » a été ajouté à vos favoris."
            : "« {$product->name} » a été retiré de vos favoris.");
    }
}
