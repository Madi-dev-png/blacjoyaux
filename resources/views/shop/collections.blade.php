@extends('layouts.shop')

@section('title', 'Nos Collections — Blac Joyaux')
@section('meta_description', "Découvrez les trois univers Blac Joyaux : Joyau de Bla, Collection DO et Capsule.")

@section('content')

{{-- HERO --}}
<section class="coll-hero">
    <div class="container">
        <span class="nh-eyebrow">Blac Joyaux</span>
        <h1>Nos collections</h1>
        <p class="subtitle">Trois univers, un seul héritage</p>
        <div class="coll-divider"><span></span><i>◆</i><span></span></div>
    </div>
</section>

{{-- BLOCS COLLECTIONS --}}
@foreach($collections as $c)
    <section class="coll-split {{ $loop->index % 2 === 1 ? 'is-reversed' : '' }} coll-theme-{{ $c['key'] }}">
        <div class="coll-split-text">
            <span class="coll-split-tag">{{ strtoupper($c['tag']) }}</span>
            <h2>{{ $c['label'] }}</h2>

            @php
                $descriptions = [
                    'joyau_de_bla'  => "Notre collection iconique. Des sacs sculptés dans le cuir véritable, portant l'élégance ivoirienne à travers chaque couture.",
                    'collection_do' => "Inspirée de la modernité ivoirienne. Des silhouettes épurées pour la femme contemporaine qui porte son héritage avec style.",
                    'capsule'       => "La collection GYE NYAME est pensée comme une capsule premium composée de trois modèles complémentaires, chacun répondant à un moment précis de la vie d'un professionnel.",
                ];
            @endphp
            <p>{{ $descriptions[$c['key']] ?? '' }}</p>

            @if($c['key'] === 'capsule')
                <span class="coll-meta">{{ $c['count'] }} pièces · Édition limitée</span>
            @else
                <span class="coll-meta">À partir de {{ number_format($c['from_price'] ?? 0, 0, ',', ' ') }} FCFA</span>
            @endif

            @if($c['thumbs']->filter()->isNotEmpty())
                <div class="coll-thumbs">
                    @foreach($c['thumbs'] as $thumb)
                        <span class="coll-thumb">
                            @if($thumb)
                                <img src="{{ asset('storage/'.$thumb) }}" alt="">
                            @endif
                        </span>
                    @endforeach
                </div>
            @endif

            <a href="{{ route('products.index', ['collection' => $c['key']]) }}" class="btn-coll-discover">
                Découvrir la collection
            </a>
        </div>

        <div class="coll-split-visual">
            @if($c['thumbs']->filter()->isNotEmpty())
                <img src="{{ asset('storage/'.$c['thumbs']->filter()->first()) }}" alt="{{ $c['label'] }}">
            @endif

            @if($c['key'] === 'capsule')
                <span class="coll-visual-badge coll-badge-count">{{ $c['count'] }}<small>pièces</small></span>
                <span class="coll-visual-badge coll-badge-edition">
                    Édition limitée<br><small>Saison 2025</small>
                </span>
            @else
                <span class="coll-visual-badge coll-badge-count-square">{{ $c['count'] }} modèles</span>
            @endif
        </div>
    </section>
@endforeach

{{-- ARTISANAT --}}
<section class="coll-craft">
    <div class="container">
        <div class="coll-divider"><span></span><i>◆</i><span></span></div>
        <span class="nh-eyebrow">Made in Côte d'Ivoire</span>
        <h2>L'artisanat au cœur de chaque pièce</h2>
        <p>Chaque sac Blac Joyaux est fabriqué à la main par nos artisans, avec un cuir véritable soigneusement sélectionné pour sa durabilité et sa noblesse.</p>

        <div class="coll-craft-grid">
            <div class="coll-craft-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M18 11V6a2 2 0 0 0-2-2 2 2 0 0 0-2 2"/><path d="M14 10V4a2 2 0 0 0-2-2 2 2 0 0 0-2 2v2"/><path d="M10 10.5V6a2 2 0 0 0-2-2 2 2 0 0 0-2 2v8"/><path d="M18 8a2 2 0 1 1 4 0v6a8 8 0 0 1-8 8h-2c-2.8 0-4.5-.86-5.99-2.34l-3.6-3.6a2 2 0 0 1 2.83-2.82L7 15"/></svg>
                <span>Fait main</span>
            </div>
            <div class="coll-craft-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4"/><line x1="12" y1="2" x2="12" y2="4"/><line x1="12" y1="20" x2="12" y2="22"/><line x1="2" y1="12" x2="4" y2="12"/><line x1="20" y1="12" x2="22" y2="12"/></svg>
                <span>Cuir véritable</span>
            </div>
            <div class="coll-craft-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="m9 12.5-2 8 5-3 5 3-2-8"/></svg>
                <span>Qualité premium</span>
            </div>
            <div class="coll-craft-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                <span>Commande WhatsApp</span>
            </div>
        </div>
    </div>
</section>

@endsection