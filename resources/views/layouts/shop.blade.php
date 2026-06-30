<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Blac Joyaux — Maroquinerie ivoirienne, héritage Ashanti')</title>
    <meta name="description" content="@yield('meta_description', "Blac Joyaux : sacs à main inspirés de l'héritage Ashanti, façonnés à Abidjan. Livraison rapide, commande via WhatsApp.")">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Blac Joyaux">
    <meta property="og:title" content="@yield('title', 'Blac Joyaux')">
    <meta property="og:description" content="@yield('meta_description', 'Maroquinerie ivoirienne, héritage Ashanti.')">
    <meta property="og:url" content="{{ url()->current() }}">

    @stack('structured-data')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,400&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
<a href="#contenu" class="skip-link">Aller au contenu</a>

<header class="new-header">
    <div class="container new-header-top">
        <form class="nh-search" action="{{ route('products.index') }}" method="GET">
            <span class="ico">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="7"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
            </span>
            <input type="search" name="q" placeholder="Rechercher un sac...">
        </form>

        <a href="{{ route('home') }}" class="nh-brand">
            <span class="nh-brand-name">BLAC</span>
            <span class="nh-brand-sub">JOYAUX</span>
        </a>

        <div class="nh-actions">
            <a href="{{ route('login') }}" aria-label="Compte">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </a>
            <a href="{{ route('cart.index') }}" class="nh-cart" aria-label="Panier">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/>
                    <path d="M3 6h18"/>
                    <path d="M16 10a4 4 0 0 1-8 0"/>
                </svg>
                @if($cartCount > 0)
                    <span class="nh-badge">{{ $cartCount }}</span>
                @endif
            </a>
            <button type="button" class="nav-toggle" id="nav-toggle"
                    aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="mobile-nav">
                <span class="nh-menu-text">MENU</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>
        </div>
    </div>

    <nav class="new-nav" aria-label="Navigation principale">
        <div class="container">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'is-active' : '' }}">Accueil</a>
            <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'is-active' : '' }}">Boutique</a>
            <a href="{{ route('products.index') }}">Collections</a>
            <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'is-active' : '' }}">À propos</a>
            <a href="{{ route('faq') }}" class="{{ request()->routeIs('faq') ? 'is-active' : '' }}">Contact</a>
        </div>
    </nav>

    <nav class="mobile-nav" id="mobile-nav" aria-label="Navigation mobile" hidden>
        <a href="{{ route('home') }}">Accueil</a>
        <a href="{{ route('products.index') }}">Boutique</a>
        <a href="{{ route('products.index') }}">Collections</a>
        <a href="{{ route('about') }}">À propos</a>
        <a href="{{ route('faq') }}">Contact</a>
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

<footer class="new-footer">
    <div class="container nf-top">
        <span class="nf-brand-name">BLAC</span>
        <span class="nf-brand-sub">JOYAUX</span>
        <p class="nf-tagline">L'avenir en main</p>

        <nav class="nf-nav">
            <a href="{{ route('products.index') }}">Boutique</a>
            <a href="{{ route('products.index') }}">Collections</a>
            <a href="{{ route('about') }}">À propos</a>
            <a href="{{ route('faq') }}">Contact</a>
        </nav>

        <div class="nf-social">
            <a href="#" aria-label="Facebook">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M22 12a10 10 0 1 0-11.56 9.87v-6.99H7.9v-2.88h2.54V9.8c0-2.5 1.49-3.89 3.78-3.89 1.1 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.87h2.78l-.44 2.88h-2.34v6.99A10 10 0 0 0 22 12Z"/>
                </svg>
            </a>
            <a href="#" aria-label="Instagram">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                </svg>
            </a>
            <a href="#" aria-label="TikTok">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M16.6 5.82c-.86-.6-1.46-1.5-1.6-2.55h-2.93v12.6a2.5 2.5 0 1 1-1.77-2.39V10.4a5.5 5.5 0 1 0 4.7 5.43V9.18a6.9 6.9 0 0 0 4.1 1.33V7.55a4.1 4.1 0 0 1-2.5-1.73Z"/>
                </svg>
            </a>
            <a href="https://wa.me/{{ $brandWhatsapp }}" target="_blank" rel="noopener" aria-label="WhatsApp">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                </svg>
            </a>
        </div>

        <div class="nf-bottom">
            <div class="nf-bottom-links">
                <a href="{{ route('faq') }}">Conditions</a>
                <a href="{{ route('faq') }}">Confidentialité</a>
                <a href="{{ route('faq') }}">Livraison &amp; Retours</a>
            </div>
            <p class="nf-copy">© {{ date('Y') }} Blac Joyaux. Tous droits réservés. Made in Côte d'Ivoire</p>
        </div>
    </div>
</footer>

@include('partials.chat-widget')

<script src="{{ asset('js/chat.js') }}"></script>
<script>
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