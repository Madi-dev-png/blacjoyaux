<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /** Page collection : liste des produits avec filtres. */
    public function index(Request $request)
    {
        $categories = Category::all();

        $products = Product::active()
            ->when($request->category, function ($q) use ($request) {
                $q->whereHas('category', fn ($c) => $c->where('slug', $request->category));
            })
            ->when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%');
            })
            ->when($request->sort === 'price_asc', fn ($q) => $q->orderBy('price'))
            ->when($request->sort === 'price_desc', fn ($q) => $q->orderByDesc('price'))
            ->when(! $request->sort || $request->sort === 'recent', fn ($q) => $q->latest())
            ->paginate(9)
            ->withQueryString();

        return view('shop.collection', compact('products', 'categories'));
    }

    /** Fiche produit. */
    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);

        $related = Product::active()
            ->where('id', '!=', $product->id)
            ->when($product->category_id, fn ($q) => $q->where('category_id', $product->category_id))
            ->take(4)
            ->get();

        return view('shop.product', compact('product', 'related'));
    }
}
