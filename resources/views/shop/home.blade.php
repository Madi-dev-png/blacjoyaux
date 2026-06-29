@extends('layouts.shop')

@section('title', 'BLAC — Maroquinerie féminine')

@section('content')

{{-- ============================================================
     HERO - "MADE IN CÔTE D'IVOIRE" + "L'AVENIR EN MAIN"
============================================================ --}}
<section class="relative min-h-[85vh] flex items-end bg-gray-900 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-stone-800 to-amber-950"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 md:pb-24 w-full">
        <p class="text-xs tracking-[0.3em] uppercase text-amber-400 font-inter mb-4">Made in Côte d'Ivoire</p>
        <h1 class="font-cormorant font-bold text-white leading-none mb-8">
            <span class="block text-5xl md:text-7xl lg:text-8xl tracking-tight">L'AVENIR</span>
            <span class="block text-5xl md:text-7xl lg:text-8xl tracking-tight">EN MAIN</span>
        </h1>
        <a href="{{ route('products.index') }}" class="inline-block border border-white text-white text-xs tracking-[0.2em] uppercase px-7 py-3 hover:bg-white hover:text-gray-900 transition-all duration-300 font-inter font-medium">
            Découvrir
        </a>
    </div>
</section>

{{-- ============================================================
     BANDE DE PROMESSES - Couleur #e9e4f0
============================================================ --}}
<section class="py-6" style="background-color: #e9e4f0;">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-[10px] font-bold uppercase tracking-widest text-gray-800">

            <div class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Livraison 1 à 3 jours
            </div>

            <div class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                Artisanat Premium
            </div>

            <div class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                Mobile Money accepté
            </div>

            <div class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                Commander via WhatsApp
            </div>

        </div>
    </div>
</section>

{{-- ============================================================
     BEST-SELLER - "Collection JOYAU DE BLA"
============================================================ --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-12">
            <span class="text-xs tracking-[0.3em] uppercase text-amber-700 font-inter">Best-Seller</span>
            <h2 class="font-cormorant text-4xl md:text-5xl font-bold text-gray-900 mt-1">Collection JOYAU DE BLA</h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">

            @php
                $products = $products ?? [
                    ['name' => 'JOYAU DE BLA', 'price' => '65 000 FCFA', 'badge' => 'Best-Seller'],
                    ['name' => 'JOYAU DE BLA', 'price' => '65 000 FCFA', 'badge' => 'Best-Seller'],
                    ['name' => 'JOYAU DE BLA', 'price' => '65 000 FCFA', 'badge' => 'Best-Seller'],
                    ['name' => 'JOYAU DE BLA', 'price' => '65 000 FCFA', 'badge' => 'Best-Seller'],
                ];
            @endphp

            @foreach($products as $product)
            <div>
                <div class="aspect-square bg-gray-100 mb-3 flex items-center justify-center text-gray-400">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[10px] tracking-widest uppercase text-amber-700 font-medium">{{ $product['badge'] }}</span>
                        <p class="font-semibold text-sm">{{ $product['name'] }}</p>
                        <p class="text-sm font-bold text-gray-900">{{ $product['price'] }}</p>
                    </div>
                    <a href="#" class="text-xs font-medium text-amber-800 hover:underline">Voir</a>
                </div>
            </div>
            @endforeach

        </div>

        <div class="text-center mt-10">
            <a href="#" class="inline-block border border-gray-900 text-gray-900 text-xs tracking-[0.2em] uppercase px-6 py-3 hover:bg-gray-900 hover:text-white transition-all duration-300 font-inter font-medium">
                VOIR LA COLLECTION
            </a>
        </div>
    </div>
</section>

{{-- ============================================================
     ADRESSE + NOUVEAUTÉ
============================================================ --}}
<section class="py-16 bg-gray-50 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-8">

        <!-- Carte / Adresse -->
        <div class="bg-white p-8 border border-gray-200 shadow-sm">
            <div class="flex items-start gap-4">
                <svg class="w-8 h-8 text-amber-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <div>
                    <p class="font-bold text-gray-900 text-lg">COCODY PALMERAIE, ABIDJAN</p>
                    <p class="text-sm text-gray-500 mt-1">Nous rende visite</p>
                    <a href="#" class="inline-block mt-3 text-xs font-medium text-amber-800 border-b border-amber-800 pb-0.5">Voir sur la carte →</a>
                </div>
            </div>
        </div>

        <!-- Nouveauté -->
        <div class="bg-amber-50 p-8 border border-amber-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] tracking-widest uppercase text-amber-700 font-bold">Nouveauté</span>
                <p class="font-cormorant text-3xl font-bold text-gray-900">Collection DO</p>
                <p class="text-sm text-gray-600 mt-1">Édition limitée</p>
            </div>
            <svg class="w-12 h-12 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>
        </div>

    </div>
</section>

{{-- ============================================================
     TÉMOIGNAGES - "Nos Clientes"
============================================================ --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <p class="text-xs tracking-[0.3em] uppercase text-amber-700 font-inter mb-2">Elles témoignent</p>
            <h2 class="font-cormorant text-4xl md:text-5xl font-bold text-gray-900">Nos Clientes</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            @php
                $testimonials = $testimonials ?? [
                    [
                        'initials' => 'AM',
                        'name' => 'Awa M.',
                        'location' => 'Abidjan',
                        'text' => 'La qualité du cuir est exceptionnelle. Mon sac Joyau de Bla m\'accompagne partout, au bureau comme en soirée. Une vraie fierté ivoirienne.',
                    ],
                    [
                        'initials' => 'SK',
                        'name' => 'Sarah K.',
                        'location' => 'Dakar',
                        'text' => 'Service client impeccable et livraison rapide. Le design est épuré et chic. Je recommande vivement cette marque à toutes mes amies.',
                    ],
                    [
                        'initials' => 'FT',
                        'name' => 'Fatou T.',
                        'location' => 'Paris',
                        'text' => 'Les finitions sont parfaites. On sent vraiment le savoir-faire artisanal. C\'est mon troisième achat et je suis toujours aussi satisfaite.',
                    ],
                ];
            @endphp

            @foreach($testimonials as $t)
            <div class="bg-gray-50 p-8 border border-gray-100 shadow-sm">
                <div class="flex gap-0.5 mb-5">
                    @for($i = 0; $i < 5; $i++)
                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 0 0 .95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 0 0-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 0 0-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 0 0-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 0 0 .951-.69l1.07-3.292Z"/>
                        </svg>
                    @endfor
                </div>

                <p class="text-sm text-gray-600 leading-relaxed mb-6 font-inter">
                    "{{ $t['text'] }}"
                </p>

                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-amber-700 text-white flex items-center justify-center text-xs font-bold font-inter">
                        {{ $t['initials'] }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 font-inter">{{ $t['name'] }}</p>
                        <p class="text-xs text-gray-400 font-inter">{{ $t['location'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</section>

@endsection