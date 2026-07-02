@extends('layouts.shop')

@section('title', 'Blac Joyaux — L\'avenir en main')
@section('meta_description', "Blac Joyaux : sacs à main inspirés de l'héritage Ashanti, façonnés à Abidjan. Livraison 1 à 3 jours, commande via WhatsApp.")

@section('content')

{{-- HERO --}}
<section class="promo-hero">
    <div class="container">
        <p class="promo-eyebrow">Made in Côte d'Ivoire</p>
        <h1>L'AVENIR<strong>EN MAIN</strong></h1>
        <a href="{{ route('products.index') }}" class="btn-ghost-light">Découvrir</a>
    </div>
</section>

{{-- BARRE AVANTAGES --}}
<div class="perks-bar">
    <div class="container">
        <div class="perk">
            <span class="ico" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/>
                    <path d="M15 18H9"/>
                    <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/>
                    <circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/>
                </svg>
            </span>
            Livraison 1 à 3 jours
        </div>
        <div class="perk">
            <span class="ico" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
            </span>
            Artisanat premium
        </div>
        <div class="perk">
            <span class="ico" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
                </svg>
            </span>
            Mobile money accepté
        </div>
        <div class="perk">
            <span class="ico" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/>
                </svg>
            </span>
            Commander via WhatsApp
        </div>
    </div>
</div>

{{-- BEST-SELLER : COLLECTION JOYAU DE BLA --}}
<section class="nh-section">
    <div class="container">
        <div class="nh-section-head">
            <span class="nh-eyebrow">Best-seller</span>
            <h2>Collection <strong>JOYAU DE BLA</strong></h2>
        </div>

        <div class="nh-product-grid">
            @php
                $joyauItems = [
                    ['badge' => true],
                    ['badge' => true],
                    ['badge' => false],
                    ['badge' => false],
                ];
            @endphp
            @foreach($joyauItems as $item)
                <div class="nh-product-card">
                    <div class="nh-product-thumb">
                        @if($item['badge'])
                            <span class="nh-badge">Best-seller</span>
                        @endif
                        <span class="placeholder-ico">◈</span>
                    </div>
                    <div class="nh-product-name">Joyau de Bla</div>
                    <div class="nh-product-price">65 000 FCFA</div>
                    <a href="{{ route('products.index') }}" class="btn-outline-dark">Voir la collection</a>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SHOWROOM --}}
<section class="showroom-banner">
    <video class="showroom-video" autoplay muted loop playsinline poster="{{ asset('images/showroom-poster.jpg') }}">
        <source src="{{ asset('videos/howroom.mp4') }}" type="video/mp4">
    </video>
    <div class="container">
        <span class="nh-eyebrow">Showroom</span>
        <h2>Cocody Palmeraie, Abidjan</h2>
        <a href="{{ route('about') }}" class="btn-ghost-light">Nous rendre visite</a>
    </div>
</section>
{{-- COLLECTION DO --}}
<section class="nh-section">
    <div class="container">
        <div class="nh-section-head">
            <span class="nh-eyebrow">Nouveauté</span>
            <h2><strong>COLLECTION DO</strong></h2>
            <p class="subtitle">Élégance ivoirienne, portée avec héritage</p>
        </div>

        <div class="do-grid">
            <div class="do-item do-1">
                <div class="do-item-text">
                    <div class="name">Sac Bureau DO</div>
                    <div class="price">70 000 FCFA</div>
                </div>
            </div>
            <div class="do-item do-2">
                <div class="do-item-text">
                    <div class="name">DO Clutch</div>
                    <div class="price">75 000 FCFA</div>
                </div>
            </div>
            <div class="do-item do-3">
                <div class="do-item-text">
                    <div class="name">DO Mini</div>
                    <div class="price">75 000 FCFA</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- COLLECTION CAPSULE --}}
<section class="nh-section capsule-section">
    <div class="container">
        <div class="nh-section-head">
            <span class="nh-eyebrow">Exclusivité</span>
            <h2><strong>Collection CAPSULE</strong></h2>
            <p class="subtitle">2 à 3 modèles exclusifs</p>
        </div>

        <div class="capsule-grid">
            <div class="capsule-card">
                <div class="capsule-thumb"><span class="placeholder-ico">◈</span></div>
                <h3>GYE NYAME ÉLAN</h3>
                <div class="subt">Sac bureau femme</div>
                <div class="price">55 000 FCFA</div>
                <a href="https://wa.me/{{ $brandWhatsapp }}" target="_blank" rel="noopener" class="btn-whatsapp-block">Commander via WhatsApp</a>
            </div>
            <div class="capsule-card">
                <div class="capsule-thumb"><span class="placeholder-ico">◈</span></div>
                <h3>GYE NYAME LEGACY</h3>
                <div class="subt">Sac bureau homme</div>
                <div class="price">60 000 FCFA</div>
                <a href="https://wa.me/{{ $brandWhatsapp }}" target="_blank" rel="noopener" class="btn-whatsapp-block">Commander via WhatsApp</a>
            </div>
            <div class="capsule-card">
                <div class="capsule-thumb"><span class="placeholder-ico">◈</span></div>
                <h3>GYE NYAME HORIZON</h3>
                <div class="subt">Sac lifestyle unisexe</div>
                <div class="price">60 000 FCFA</div>
                <a href="https://wa.me/{{ $brandWhatsapp }}" target="_blank" rel="noopener" class="btn-whatsapp-block">Commander via WhatsApp</a>
            </div>
        </div>
    </div>
</section>

{{-- POUR LA FEMME / POUR L'HOMME --}}
<div class="split-banner">
    <div class="split-panel femme">
        <div>
            <h3>Pour la femme</h3>
            <a href="{{ route('products.index') }}" class="btn-ghost-light">Nous rendre visite</a>
        </div>
    </div>
    <div class="split-panel homme">
        <div>
            <h3>Pour l'homme</h3>
            <a href="{{ route('products.index') }}" class="btn-ghost-light">Nous rendre visite</a>
        </div>
    </div>
</div>

{{-- TÉMOIGNAGES --}}
<section class="nh-section testimonials-section">
    <div class="container">
        <div class="nh-section-head">
            <span class="nh-eyebrow">Elles témoignent</span>
            <h2>Nos <strong>CLIENTES</strong></h2>
        </div>

        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="stars">★★★★★</div>
                <p class="quote">"La qualité du cuir est exceptionnelle. Mon sac Joyau de Bla m'accompagne partout, au bureau comme en soirée. Une vraie fierté ivoirienne."</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">AM</div>
                    <div>
                        <div class="name">Awa M.</div>
                        <div class="loc">Abidjan</div>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="stars">★★★★★</div>
                <p class="quote">"Service client impeccable et livraison rapide. Le design est épuré et chic. Je recommande vivement cette marque à toutes mes amies."</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">SK</div>
                    <div>
                        <div class="name">Sarah K.</div>
                        <div class="loc">Dakar</div>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="stars">★★★★★</div>
                <p class="quote">"Les finitions sont parfaites. On sent vraiment le savoir-faire artisanal. C'est mon troisième achat et je suis toujours aussi satisfaite."</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">FT</div>
                    <div>
                        <div class="name">Fatou T.</div>
                        <div class="loc">Paris</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection