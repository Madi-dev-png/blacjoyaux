<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Boutique (front-office public)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/a-propos', [HomeController::class, 'about'])->name('about');
Route::get('/collection', [ProductController::class, 'index'])->name('products.index');
Route::get('/recherche', [ProductController::class, 'search'])->name('products.search');
Route::get('/collections', [ProductController::class, 'collections'])->name('collections.index');
Route::get('/produit/{product}', [ProductController::class, 'show'])->name('products.show');

// Panier (session, sans compte)
Route::get('/panier', [CartController::class, 'index'])->name('cart.index');
Route::post('/panier/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/panier/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/panier/{product}', [CartController::class, 'remove'])->name('cart.remove');

// Commande (guest checkout)
Route::get('/commande', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/commande', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/commande/confirmation/{reference}', [CheckoutController::class, 'confirmation'])
    ->name('checkout.confirmation');

// FAQ
Route::get('/faq', [FaqController::class, 'index'])->name('faq');

// Assistant IA (widget de chat, appelé en AJAX depuis toutes les pages)
Route::post('/chat', [ChatController::class, 'send'])->name('chat.send');

// SEO : sitemap dynamique
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
//Contact 
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

/*
|--------------------------------------------------------------------------
| Authentification (admin uniquement)
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Back-office administrateur (CRUD Laravel — protégé)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware('admin')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
            ->name('dashboard');

        // Produits + endpoint d'aperçu SEO en temps réel
        Route::get('produits/seo-preview', [\App\Http\Controllers\Admin\ProductController::class, 'seoPreview'])
            ->name('products.seo-preview');
        Route::resource('produits', \App\Http\Controllers\Admin\ProductController::class)
            ->parameters(['produits' => 'product'])
            ->names('products');

        // Commandes
        Route::get('commandes', [\App\Http\Controllers\Admin\OrderController::class, 'index'])
            ->name('orders.index');
        Route::get('commandes/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])
            ->name('orders.show');
        Route::patch('commandes/{order}/statut', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])
            ->name('orders.status');

        // FAQ
        Route::get('faq', [\App\Http\Controllers\Admin\FaqController::class, 'index'])
            ->name('faqs.index');
        Route::post('faq', [\App\Http\Controllers\Admin\FaqController::class, 'store'])
            ->name('faqs.store');
        Route::patch('faq/{faq}', [\App\Http\Controllers\Admin\FaqController::class, 'update'])
            ->name('faqs.update');
        Route::delete('faq/{faq}', [\App\Http\Controllers\Admin\FaqController::class, 'destroy'])
            ->name('faqs.destroy');
    });
