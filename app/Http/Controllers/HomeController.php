<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller

{
    public function index()
{
    $capsule = Product::active()->where('collection', 'capsule')->get();

    return view('shop.home', [
        'joyauDeBla'        => Product::active()->where('collection', 'joyau_de_bla')->take(4)->get(),
        'collectionDo'      => Product::active()->where('collection', 'collection_do')->take(4)->get(),
        // Un seul sac représentatif par modèle (chaque modèle a 3 variantes de couleur en base).
        'collectionCapsule' => $capsule->unique('variant_group')->values(),
        // Sacs mis en avant dans le bandeau "Pour la femme / Pour l'homme".
        'bureauFemme'       => $capsule->firstWhere('variant_group', 'bureau-femme'),
        'bureauHomme'       => $capsule->firstWhere('variant_group', 'bureau-homme'),
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