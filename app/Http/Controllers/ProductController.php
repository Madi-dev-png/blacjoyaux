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
            ->when($request->collection, function ($q) use ($request) {
                $q->where('collection', $request->collection);
            })
            ->when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%');
            })
            ->when($request->sort === 'price_asc', fn ($q) => $q->orderBy('price'))
            ->when($request->sort === 'price_desc', fn ($q) => $q->orderByDesc('price'))
            ->when(! $request->sort || $request->sort === 'recent', fn ($q) => $q->latest())
            ->paginate(11)
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
            ->inRandomOrder()
            ->take(4)
            ->get();

        $colorSiblings = Product::active()
            ->where('collection', $product->collection)
            ->orderBy('id')
            ->take(5)
            ->get();

        $collectionLabels = [
            'joyau_de_bla'  => 'Joyau de Bla',
            'collection_do' => 'Collection DO',
            'capsule'       => 'Capsule',
        ];
        $collectionLabel = $collectionLabels[$product->collection] ?? null;

        return view('shop.product', compact('product', 'related', 'colorSiblings', 'collectionLabel'));
    }
}
