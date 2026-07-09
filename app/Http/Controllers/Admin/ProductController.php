<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct(protected SeoService $seo) {}

    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->search, fn ($q) => $q->where('name', 'like', '%'.$request->search.'%'))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $product = new Product;

        return view('admin.products.create', compact('categories', 'product'));
    }

    public function store(Request $request)
    {
        $data = $this->validateProduct($request);
        $data = $this->prepareSeo($data);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit créé avec succès.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateProduct($request, $product->id);
        $data = $this->prepareSeo($data, $product->id);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit mis à jour.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit supprimé.');
    }

    /**
     * Endpoint AJAX : aperçu SEO en temps réel.
     * Le formulaire admin appelle cette route à chaque frappe pour afficher
     * les suggestions (slug, meta_title, meta_description) et le score qualité.
     */
    public function seoPreview(Request $request)
    {
        $name = (string) $request->input('name', '');
        $category = $request->input('category');
        $short = $request->input('short_description');
        $price = (int) $request->input('price', 0);

        $slug = $name ? $this->seo->generateSlug($name, $request->input('id')) : '';
        $metaTitle = $request->input('meta_title') ?: ($name ? $this->seo->generateMetaTitle($name, $category) : '');
        $metaDesc = $request->input('meta_description') ?: ($name ? $this->seo->generateMetaDescription($name, $short, $price) : '');

        $result = $this->seo->score([
            'name' => $name,
            'slug' => $slug,
            'meta_title' => $metaTitle,
            'meta_description' => $metaDesc,
            'description' => $request->input('description', ''),
        ]);

        return response()->json([
            'slug' => $slug,
            'meta_title' => $metaTitle,
            'meta_description' => $metaDesc,
            'score' => $result['score'],
            'level' => $result['level'],
            'checks' => $result['checks'],
        ]);
    }

    protected function validateProduct(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => 'required|string|max:160',
            'category_id' => 'nullable|exists:categories,id',
            'collection' => 'nullable|string|in:joyau_de_bla,collection_do,capsule',
            'short_description' => 'nullable|string|max:300',
            'description' => 'nullable|string',
            'story' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'color' => 'nullable|string|max:60',
            'variant_group' => 'nullable|string|max:80',
            'material' => 'nullable|string|max:60',
            'dimensions' => 'nullable|string|max:60',
            'closure' => 'nullable|string|max:100',
            'lining' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:4096',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:320',
            'slug' => 'nullable|string|max:200',
        ]);
    }

    /** Complète automatiquement les champs SEO manquants + calcule le score. */
    protected function prepareSeo(array $data, ?int $ignoreId = null): array
    {
        $categoryName = isset($data['category_id'])
            ? optional(Category::find($data['category_id']))->name
            : null;

        $data['slug'] = ! empty($data['slug'])
            ? $this->seo->generateSlug($data['slug'], $ignoreId)
            : $this->seo->generateSlug($data['name'], $ignoreId);

        $data['meta_title'] = ! empty($data['meta_title'])
            ? $data['meta_title']
            : $this->seo->generateMetaTitle($data['name'], $categoryName);

        $data['meta_description'] = ! empty($data['meta_description'])
            ? $data['meta_description']
            : $this->seo->generateMetaDescription($data['name'], $data['short_description'] ?? null, $data['price'] ?? null);

        $data['is_active'] = (bool) ($data['is_active'] ?? false);
        $data['is_featured'] = (bool) ($data['is_featured'] ?? false);

        $score = $this->seo->score([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'meta_title' => $data['meta_title'],
            'meta_description' => $data['meta_description'],
            'description' => $data['description'] ?? '',
        ]);
        $data['seo_score'] = $score['score'];

        return $data;
    }
}
