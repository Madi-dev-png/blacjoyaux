<?php

namespace App\Http\Controllers;

use App\Models\Product;

class SitemapController extends Controller
{
    /** Génère un sitemap.xml dynamique pour le référencement. */
    public function index()
    {
        $products = Product::active()->latest('updated_at')->get();

        $content = view('sitemap', compact('products'))->render();

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }
}
