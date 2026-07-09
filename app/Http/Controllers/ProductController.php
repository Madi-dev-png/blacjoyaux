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
                $q->where('name', 'like', '%'.$this->escapeLike($request->search).'%');
            })
            ->when($request->sort === 'price_asc', fn ($q) => $q->orderBy('price'))
            ->when($request->sort === 'price_desc', fn ($q) => $q->orderByDesc('price'))
            ->when(! $request->sort || $request->sort === 'recent', fn ($q) => $q->latest())
            ->paginate(12)
            ->withQueryString();

        return view('shop.collection', compact('products', 'categories'));
    }

    /** Suggestions de recherche en direct (AJAX), utilisées par la barre de recherche du header. */
    public function search(Request $request)
    {
        $term = trim((string) $request->get('search'));

        if (mb_strlen($term) < 2) {
            return response()->json([]);
        }

        $products = Product::active()
            ->where('name', 'like', '%'.$this->escapeLike($term).'%')
            ->orderBy('name')
            ->take(6)
            ->get();

        return response()->json($products->map(fn ($p) => [
            'name' => $p->name,
            'price' => $p->formatted_price,
            'url' => route('products.show', $p),
            'image' => $p->image ? asset('storage/'.$p->image) : null,
        ]));
    }

    /** Page "Nos collections". */
    public function collections()
    {
        $defs = [
            'joyau_de_bla' => ['label' => 'Joyau de Bla', 'tag' => 'Collection signature'],
            'collection_do' => ['label' => 'Collection DO', 'tag' => 'Nouveauté 2025'],
            'capsule' => ['label' => 'Blac Héritage', 'tag' => 'Capsule premium'],
        ];

        $collections = collect($defs)->map(function ($def, $key) {
            $products = Product::active()->where('collection', $key)->get();

            return [
                'key' => $key,
                'label' => $def['label'],
                'tag' => $def['tag'],
                'count' => $products->count(),
                'from_price' => $products->min('price'),
                'thumbs' => $products->take(4)->pluck('image'),
            ];
        });

        return view('shop.collections', compact('collections'));
    }

    /** Fiche produit. */
    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);

        // Regroupement des couleurs : UNIQUEMENT via le champ "variant_group" rempli
        // manuellement dans l'admin. On ne devine plus rien à partir du nom du produit,
        // car deux sacs différents peuvent partager un nom très proche (ex: deux modèles
        // "Collection DO" en cuir marron) sans être des variantes de couleur l'un de l'autre.
        if (! empty($product->variant_group)) {
            $colorSiblings = Product::active()
                ->where('variant_group', $product->variant_group)
                ->orderBy('id')
                ->get();
        } else {
            $colorSiblings = collect([$product]);
        }

        // "Vous aimerez aussi" : on exclut le produit courant et ses propres variantes de
        // couleur (pas d'intérêt à se recommander soi-même), on privilégie la même catégorie,
        // puis on complète avec d'autres produits actifs pour toujours proposer 4 suggestions.
        $excludedIds = $colorSiblings->pluck('id')->push($product->id);

        $related = Product::active()
            ->whereNotIn('id', $excludedIds)
            ->when($product->category_id, fn ($q) => $q->where('category_id', $product->category_id))
            ->inRandomOrder()
            ->take(4)
            ->get();

        if ($related->count() < 4) {
            $more = Product::active()
                ->whereNotIn('id', $excludedIds->merge($related->pluck('id')))
                ->inRandomOrder()
                ->take(4 - $related->count())
                ->get();

            $related = $related->concat($more);
        }

        $collectionLabels = [
            'joyau_de_bla' => 'Joyau de Bla',
            'collection_do' => 'Collection DO',
            'capsule' => 'Blac Héritage',
        ];
        $collectionLabel = $collectionLabels[$product->collection] ?? null;

        return view('shop.product', compact('product', 'related', 'colorSiblings', 'collectionLabel'));
    }

    /**
     * Échappe les jokers SQL (% _ \) dans un terme de recherche, pour qu'une
     * saisie comme "%%%" soit traitée littéralement et non comme un motif LIKE.
     */
    protected function escapeLike(string $term): string
    {
        return addcslashes($term, '%_\\');
    }
}
