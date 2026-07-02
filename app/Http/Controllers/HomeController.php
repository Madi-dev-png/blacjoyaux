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
        'collectionCapsule' => $capsule->take(3),
        // Sacs mis en avant dans le bandeau "Pour la femme / Pour l'homme".
        // On cherche d'abord par mot-clé dans le nom (plus robuste qu'un slug figé),
        // avec repli sur les 2 premiers produits capsule si aucun ne correspond.
        'bureauFemme'       => $capsule->first(fn ($p) => str_contains(mb_strtolower($p->name), 'élan')) ?? $capsule->get(0),
        'bureauHomme'       => $capsule->first(fn ($p) => str_contains(mb_strtolower($p->name), 'legacy')) ?? $capsule->get(1),
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