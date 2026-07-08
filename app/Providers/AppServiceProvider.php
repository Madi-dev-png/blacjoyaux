<?php

namespace App\Providers;

use App\Services\CartService;
use App\Services\WishlistService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Partage le nombre d'articles du panier + WhatsApp avec toutes les vues.
        View::composer('*', function ($view) {
            $cart = app(CartService::class);
            $wishlist = app(WishlistService::class);
            $view->with('cartCount', $cart->count());
            $view->with('wishlistCount', $wishlist->count());
            $view->with('wishlistIds', $wishlist->ids());
            $view->with('brandWhatsapp', config('services.brand.whatsapp'));
        });
    }
}
