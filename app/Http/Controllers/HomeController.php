<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Product::active()->featured()->latest()->take(4)->get();
        $newest = Product::active()->latest()->take(8)->get();
        $categories = Category::withCount('products')->get();

        return view('shop.home', compact('featured', 'newest', 'categories'));
    }

    public function about()
    {
        return view('shop.about');
    }
}
