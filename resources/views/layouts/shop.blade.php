<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'BLAC — Maroquinerie')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #ffffff;
            color: #1a1a1a;
        }
        .font-cormorant {
            font-family: 'Cormorant Garamond', serif;
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- HEADER --}}
<header class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-100/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">

            <!-- Recherche (gauche) -->
            <div class="flex-1 flex items-center gap-2 text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" placeholder="Rechercher un sac..." class="text-sm bg-transparent focus:outline-none w-full" />
            </div>

            <!-- Logo -->
            <a href="{{ route('home') }}" class="font-bold text-2xl tracking-widest text-center">
                BLAC <span class="block text-[8px] tracking-[0.4em] -mt-1 font-medium">JOYAUX</span>
            </a>

            <!-- Icônes (droite) -->
            <div class="flex-1 flex justify-end gap-6 items-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <a href="{{ route('cart.index') }}" class="relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] rounded-full w-4 h-4 flex items-center justify-center font-bold">0</span>
                </a>
                <button class="font-bold text-sm uppercase flex items-center gap-2">
                    Menu
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

        </div>

        <!-- Navigation Secondaire -->
        <nav class="hidden lg:flex justify-center gap-10 text-[11px] uppercase tracking-[0.2em] pb-5 font-medium">
            <a href="{{ route('home') }}">Accueil</a>
            <a href="{{ route('products.index') }}">Boutique</a>
            <a href="#">Collections</a>
            <a href="{{ route('about') }}">À propos</a>
            <a href="{{ route('faq') }}">Contact</a>
        </nav>
    </div>
</header>

{{-- CONTENU PRINCIPAL --}}
<main id="contenu" class="pt-20">
    @yield('content')
</main>

{{-- FOOTER --}}
<footer class="mt-20 border-t p-10 text-center text-sm text-gray-500">
    © {{ date('Y') }} Blac Joyaux — Made in Côte d'Ivoire 🇨🇮
</footer>

</body>
</html>