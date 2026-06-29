<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller

{
    public function index()
{
    return view('shop.home', [
        'joyauDeBla'        => Product::where('collection', 'joyau_de_bla')->take(4)->get(),
        'collectionDo'      => Product::where('collection', 'do')->take(4)->get(),
        'collectionCapsule' => Product::where('collection', 'capsule')->take(3)->get(),
        'testimonials'      => DB::table('testimonials')->take(3)->get(),
        'brandWhatsapp'     => config('app.whatsapp_number'),
        'cartCount'         => session('cart') ? count(session('cart')) : 0,
    ]);
}

    public function about()
    {
        return view('shop.about');
    }
}
