<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $heritage = Product::active()->where('collection', 'capsule')->get();

        return view('shop.home', [
            'joyauDeBla' => Product::active()->where('collection', 'joyau_de_bla')->take(4)->get(),
            'collectionDo' => Product::active()->where('collection', 'collection_do')->take(4)->get(),
            // Un seul sac représentatif par modèle (chaque modèle a 3 variantes de couleur en base).
            'blacHeritage' => $heritage->unique('variant_group')->values(),
            'elanProduct' => $heritage->firstWhere('variant_group', 'elan') ?: $heritage->firstWhere('variant_group', 'bureau-femme'),
            'empireProduct' => $heritage->firstWhere('variant_group', 'empire') ?: $heritage->firstWhere('variant_group', 'bureau-homme'),
            'testimonials' => DB::table('testimonials')->take(3)->get(),
            'brandWhatsapp' => config('app.whatsapp_number'),
            'cartCount' => session('cart') ? count(session('cart')) : 0,
        ]);
    }

    public function about()
    {
        return view('shop.about');
    }
}
