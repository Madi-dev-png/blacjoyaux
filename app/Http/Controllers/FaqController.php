<?php

namespace App\Http\Controllers;

use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        $order = ['livraison', 'paiement', 'produit', 'retours'];

        $faqs = Faq::active()
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category')
            ->sortBy(fn ($items, $category) => array_search($category, $order) === false ? 999 : array_search($category, $order));

        $labels = [
            'livraison' => 'Commande & Livraison',
            'paiement'  => 'Paiement',
            'produit'   => 'Produits',
            'retours'   => 'Retours & SAV',
        ];

        return view('shop.faq', compact('faqs', 'labels'));
    }
}