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

    /** Page "Nos collections". */
    public function collections()
    {
        $defs = [
            'joyau_de_bla'  => ['label' => 'Joyau de Bla', 'tag' => 'Collection signature'],
            'collection_do' => ['label' => 'Collection DO', 'tag' => 'Nouveauté 2025'],
            'capsule'       => ['label' => 'Collection Capsule', 'tag' => 'Exclusivité'],
        ];

        $collections = collect($defs)->map(function ($def, $key) {
            $products = Product::active()->where('collection', $key)->get();

            return [
                'key'         => $key,
                'label'       => $def['label'],
                'tag'         => $def['tag'],
                'count'       => $products->count(),
                'from_price'  => $products->min('price'),
                'thumbs'      => $products->take(4)->pluck('image'),
            ];
        });

        return view('shop.collections', compact('collections'));
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

        // On ne regroupe comme "variantes de couleur" que les produits qui partagent
        // le même nom de base (ex: "Sac à main – Nouvelle version") : ainsi, cliquer sur
        // une couleur affiche bien une variante du MÊME sac, jamais un modèle différent.
        $colorSiblings = Product::active()
            ->where('collection', $product->collection)
            ->orderBy('id')
            ->get()
            ->filter(fn ($sibling) => $sibling->base_name === $product->base_name)
            ->values();

        // Si aucune vraie variante de couleur n'existe pour ce modèle, on ne montre
        // que le produit courant (le bloc couleurs restera masqué côté vue).
        if ($colorSiblings->count() < 2) {
            $colorSiblings = collect([$product]);
        }

        $collectionLabels = [
            'joyau_de_bla'  => 'Joyau de Bla',
            'collection_do' => 'Collection DO',
            'capsule'       => 'Capsule',
        ];
        $collectionLabel = $collectionLabels[$product->collection] ?? null;

        return view('shop.product', compact('product', 'related', 'colorSiblings', 'collectionLabel'));
    }
}