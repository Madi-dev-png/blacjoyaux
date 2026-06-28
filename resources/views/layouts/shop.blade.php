<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- SEO --}}
    <title>@yield('title', 'Blac Joyaux — Maroquinerie féminine, héritage Ashanti')</title>
    <meta name="description" content="@yield('meta_description', "Blac Joyaux : sacs à main pour femmes inspirés de l'héritage Ashanti. Élégance, qualité et fierté africaine. Livraison à Abidjan.")">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Blac Joyaux">
    <meta property="og:title" content="@yield('title', 'Blac Joyaux')">
    <meta property="og:description" content="@yield('meta_description', 'Maroquinerie féminine, héritage Ashanti.')">
    <meta property="og:url" content="{{ url()->current() }}">

    @stack('structured-data')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;1,9..144,400&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
<a href="#contenu" class="skip-link">Aller au contenu</a>

<header class="site-header">
    <div class="container header-inner">
        <a href="{{ route('home') }}" class="brand">
            <span class="brand-mark">◈</span>
            <span class="brand-name">Blac&nbsp;Joyaux</span>
        </a>

        <nav class="main-nav" aria-label="Navigation principale">
            <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'is-active' : '' }}">Collection</a>
            <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'is-active' : '' }}">L'histoire</a>
            <a href="{{ route('faq') }}" class="{{ request()->routeIs('faq') ? 'is-active' : '' }}">FAQ</a>
        </nav>

        <div class="header-actions">
            <a href="{{ route('cart.index') }}" class="cart-link" aria-label="Panier">
                <span class="cart-icon">🛍</span>
                @if($cartCount > 0)
                    <span class="cart-badge">{{ $cartCount }}</span>
                @endif
            </a>

            <button type="button" class="nav-toggle" id="nav-toggle"
                    aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="mobile-nav">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>

    {{-- Menu mobile (déroulant) --}}
    <nav class="mobile-nav" id="mobile-nav" aria-label="Navigation mobile" hidden>
        <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'is-active' : '' }}">Collection</a>
        <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'is-active' : '' }}">L'histoire</a>
        <a href="{{ route('faq') }}" class="{{ request()->routeIs('faq') ? 'is-active' : '' }}">FAQ</a>
        <a href="{{ route('cart.index') }}">Panier @if($cartCount > 0)({{ $cartCount }})@endif</a>
    </nav>
</header>

@if(session('success'))
    <div class="flash flash-success container" role="status">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="flash flash-error container" role="alert">{{ session('error') }}</div>
@endif

<main id="contenu">
    @yield('content')
</main>

<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-brand">
            <span class="brand-mark">◈</span>
            <p class="footer-tagline">L'élégance qui raconte une histoire. Maroquinerie féminine inspirée de l'héritage Ashanti, façonnée à Abidjan.</p>
        </div>
        <div>
            <h3>Boutique</h3>
            <ul>
                <li><a href="{{ route('products.index') }}">Collection</a></li>
                <li><a href="{{ route('about') }}">Notre histoire</a></li>
                <li><a href="{{ route('faq') }}">Questions fréquentes</a></li>
            </ul>
        </div>
        <div>
            <h3>Nous joindre</h3>
            <ul>
                <li><a href="https://wa.me/{{ $brandWhatsapp }}" target="_blank" rel="noopener">WhatsApp</a></li>
                <li>Cocody Palmeraie, Abidjan</li>
                <li>Livraison 3–5 jours</li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom container">
        <p>© {{ date('Y') }} Blac Joyaux — Projet pédagogique IFRAN (Groupe 8). Marque fondée par Manuela Kouadio.</p>
    </div>
</footer>

{{-- Widget assistant IA (présent sur toutes les pages) --}}
@include('partials.chat-widget')

<script src="{{ asset('js/chat.js') }}"></script>
<script>
    // Menu mobile (burger)
    (function () {
        const toggle = document.getElementById('nav-toggle');
        const menu = document.getElementById('mobile-nav');
        if (!toggle || !menu) return;
        toggle.addEventListener('click', function () {
            const open = menu.hasAttribute('hidden');
            if (open) {
                menu.removeAttribute('hidden');
                toggle.setAttribute('aria-expanded', 'true');
                toggle.classList.add('is-open');
            } else {
                menu.setAttribute('hidden', '');
                toggle.setAttribute('aria-expanded', 'false');
                toggle.classList.remove('is-open');
            }
        });
    })();
</script>
@stack('scripts')
</body>
</html>
