@extends('layouts.shop')

@section('title', 'À propos — Blac Joyaux')
@section('meta_description', "Découvrez l'histoire de Blac Joyaux, notre fondatrice Manuela Kouadio, et l'héritage Ashanti derrière chaque création.")

@section('content')

{{-- HERO --}}
<section class="about-hero">
    <img src="{{ asset('images/about/hero.jpg') }}" alt="Sacs Blac Joyaux" class="about-hero-img" onerror="this.style.display='none'">
    <div class="about-hero-overlay">
        <div class="container">
            <h1>Notre histoire</h1>
            <p>L'héritage d'un savoir-faire ancestral réinventé pour l'élégance contemporaine.</p>
        </div>
    </div>
</section>

{{-- FONDATRICE --}}
<section class="about-founder">
    <div class="container about-founder-grid">
        <div class="about-founder-photo">
            <img src="{{ asset('images/about/founder.jpg') }}" alt="Manuela Kouadio, fondatrice de Blac Joyaux" onerror="this.style.display='none'">
            <span class="about-founder-tag">Fondatrice</span>
        </div>
        <div class="about-founder-text">
            <span class="nh-eyebrow">La visionnaire</span>
            <h2>Manuela Kouadio</h2>
            <p>Animée par une passion pour la culture Akan et le design moderne, Manuela a fondé Blac Joyaux pour combler le fossé entre les symboles traditionnels et la joaillerie de luxe. Son parcours est une quête d'identité, de beauté et d'authenticité.</p>
            <blockquote>« Chaque bijou est un pont jeté entre nos racines et notre futur. »</blockquote>
        </div>
    </div>
</section>

{{-- ASHANTI DOLL --}}
<section class="about-ashanti">
    <div class="container about-ashanti-grid">
        <div class="about-ashanti-text">
            <h2>Ashanti Doll</h2>
            <p>L'Akua'ba est bien plus qu'une simple figurine ; c'est un symbole de fertilité, de beauté et de sagesse chez le peuple Ashanti du Ghana.</p>

            <div class="about-ashanti-cards">
                <div class="about-ashanti-card">
                    <h3>Symbolisme</h3>
                    <p>La tête circulaire représente le soleil et l'éternité, tandis que le cou annelé symbolise la beauté idéale.</p>
                </div>
                <div class="about-ashanti-card">
                    <h3>Héritage</h3>
                    <p>Portée par les femmes pour assurer la santé de leur descendance, elle incarne la protection maternelle.</p>
                </div>
            </div>
        </div>
        <div class="about-ashanti-photo">
            <img src="{{ asset('images/about/ashanti.jpg') }}" alt="Poupée Akua'ba traditionnelle Ashanti" onerror="this.style.display='none'">
        </div>
    </div>
</section>

{{-- GYE NYAME --}}
<section class="about-symbol">
    <div class="container">
        <div class="about-symbol-ico">
            <img src="{{ asset('images/about/gye-nyame.jpg') }}" alt="Symbole Adinkra Gye Nyame">
        </div>
        <h2>Gye Nyame</h2>
        <p>« Sauf pour Dieu » — Le symbole Adinkra de l'omnipotence et de l'omniprésence. Il est l'ancre spirituelle de nos collections, rappelant que la force réside dans l'unité et la foi.</p>
    </div>
</section>

{{-- VALEURS --}}
<section class="about-values">
    <div class="container">
        <h2>Nos valeurs</h2>
        <div class="about-values-grid">
            <div class="about-value-card">
                <span class="num">01</span>
                <h3>Authenticité</h3>
                <p>Chaque design respecte scrupuleusement les codes culturels ancestraux.</p>
            </div>
            <div class="about-value-card">
                <span class="num">02</span>
                <h3>Excellence</h3>
                <p>Une sélection rigoureuse des matériaux pour une durabilité éternelle.</p>
            </div>
            <div class="about-value-card">
                <span class="num">03</span>
                <h3>Héritage</h3>
                <p>Transmettre l'histoire de l'Afrique à travers l'art.</p>
            </div>
            <div class="about-value-card">
                <span class="num">04</span>
                <h3>Innovation</h3>
                <p>Réimaginer les formes classiques avec des techniques modernes.</p>
            </div>
            <div class="about-value-card">
                <span class="num">05</span>
                <h3>Engagement</h3>
                <p>Soutenir les artisans locaux et le commerce équitable.</p>
            </div>
        </div>
    </div>
</section>

@endsection