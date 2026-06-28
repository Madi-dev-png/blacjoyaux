<?php

namespace App\Http\Controllers;

use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::active()
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');

        $labels = [
            'general'   => 'Questions générales',
            'livraison' => 'Livraison',
            'paiement'  => 'Paiement',
            'produit'   => 'Nos produits',
        ];

        return view('shop.faq', compact('faqs', 'labels'));
    }
}
