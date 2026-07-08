@extends('layouts.shop')

@section('title', 'Boutique — Blac Joyaux')
@section('meta_description', "Découvrez toutes les créations Blac Joyaux : sacs à main, pochettes, cabas et bandoulières façonnés à Abidjan.")

@section('content')

{{-- HERO --}}
<section class="shop-hero">
    <div class="container">
        <span class="nh-eyebrow">Maroquinerie de luxe</span>
        <h1>Boutique</h1>
        <p class="subtitle">Toutes nos créations</p>
    </div>
</section>
<section class="shop-body">
<div class="container">
    <div class="shop-toolbar">
        <div class="shop-filters">
            <span class="shop-filters-label">FILTRER :</span>
            <a href="{{ request()->fullUrlWithQuery(['collection' => null, 'page' => null]) }}"
               class="filter-pill {{ request('collection') ? '' : 'is-active' }}">Toutes</a>
            <a href="{{ request()->fullUrlWithQuery(['collection' => 'joyau_de_bla', 'page' => null]) }}"
               class="filter-pill {{ request('collection') === 'joyau_de_bla' ? 'is-active' : '' }}">Joyau de Bla</a>
            <a href="{{ request()->fullUrlWithQuery(['collection' => 'collection_do', 'page' => null]) }}"
               class="filter-pill {{ request('collection') === 'collection_do' ? 'is-active' : '' }}">Collection DO</a>
            <a href="{{ request()->fullUrlWithQuery(['collection' => 'capsule', 'page' => null]) }}"
               class="filter-pill {{ request('collection') === 'capsule' ? 'is-active' : '' }}">Capsule</a>
        </div>

        <form method="GET" class="shop-sort">
            <span class="shop-sort-label">TRIER :</span>
            <select class="sort-select" name="sort" onchange="this.form.submit()">
                <option value="recent" {{ request('sort', 'recent') === 'recent' ? 'selected' : '' }}>Plus récent</option>
                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Prix : croissant</option>
                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Prix : décroissant</option>
            </select>
           @if(request('collection'))
                <input type="hidden" name="collection" value="{{ request('collection') }}">
            @endif
        </form>
    </div>

    <div class="shop-results-bar">
        <span class="shop-results-count">
            {{ $products->total() }} produit{{ $products->total() > 1 ? 's' : '' }} trouvé{{ $products->total() > 1 ? 's' : '' }}
        </span>
        <div class="shop-view-toggle">
            <span class="label">VUE :</span>
            <button type="button" class="view-btn is-active" aria-label="Vue grille">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                    <rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>
                </svg>
            </button>
            <button type="button" class="view-btn" aria-label="Vue liste">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- GRILLE PRODUITS --}}
    @php
        $badgeMap = [
            'joyau_de_bla'   => ['label' => 'Best-seller', 'class' => 'badge-bestseller'],
            'collection_do'  => ['label' => 'Nouveauté',   'class' => 'badge-new'],
            'capsule'        => ['label' => 'Exclusif',    'class' => 'badge-exclusive'],
        ];
    @endphp

    <div class="shop-grid">
        @forelse($products as $product)
           @php $badge = $badgeMap[$product->collection ?? ''] ?? null; @endphp
            <article class="shop-card">
                @if($badge)
                    <span class="shop-card-badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                @endif
                <button type="button" class="wishlist-btn shop-card-wishlist {{ in_array($product->id, $wishlistIds) ? 'is-active' : '' }}"
                        data-product-id="{{ $product->id }}"
                        data-toggle-url="{{ route('wishlist.toggle', $product) }}"
                        aria-label="Ajouter aux favoris">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78Z"/>
                    </svg>
                </button>
                <a href="{{ route('products.show', $product) }}" class="shop-card-thumb">
                    @if($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}">
                    @else
                        <span class="placeholder-ico" aria-hidden="true">◈</span>
                    @endif
                </a>
                @if($product->category)
                    <span class="shop-card-cat">{{ $product->category->name }}</span>
                @endif
                <a href="{{ route('products.show', $product) }}" class="shop-card-name">{{ $product->name }}</a>
                <div class="shop-card-price">{{ $product->formatted_price }}</div>
            </article>
        @empty
            <p>Aucun produit ne correspond à votre recherche.</p>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    @if($products->hasPages())
        <nav class="shop-pagination" aria-label="Pagination">
            <a href="{{ $products->previousPageUrl() ?? '#' }}" class="{{ $products->onFirstPage() ? 'is-disabled' : '' }}" aria-label="Page précédente">←</a>
            @for($i = 1; $i <= $products->lastPage(); $i++)
                <a href="{{ $products->url($i) }}" class="{{ $products->currentPage() == $i ? 'is-active' : '' }}">{{ $i }}</a>
            @endfor
            <a href="{{ $products->nextPageUrl() ?? '#' }}" class="{{ $products->hasMorePages() ? '' : 'is-disabled' }}" aria-label="Page suivante">→</a>
        </nav>
      @endif
</div>
</section>

{{-- NEWSLETTER --}}
<section class="shop-newsletter">
    <div class="container">
        <div class="ico">✉</div>
        <h2>Restez informée</h2>
        <p>Recevez en avant-première nos nouvelles collections, offres exclusives et actualités de l'atelier.</p>
        <form class="shop-newsletter-form" method="POST" action="#">
            @csrf
            <input type="email" name="email" placeholder="Votre adresse e-mail..." required>
            <button type="submit">S'inscrire</button>
        </form>
    </div>
</section>

@endsection