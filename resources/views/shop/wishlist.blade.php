@extends('layouts.shop')

@section('title', 'Mes favoris — Blac Joyaux')
@section('meta_description', "Retrouvez ici les sacs Blac Joyaux que vous avez mis de côté.")

@section('content')

<section class="shop-hero">
    <div class="container">
        <span class="nh-eyebrow">Ma sélection</span>
        <h1>Mes favoris</h1>
        <p class="subtitle">{{ $items->count() }} sac{{ $items->count() > 1 ? 's' : '' }} mis de côté</p>
    </div>
</section>

<section class="shop-body">
<div class="container">

    @if($items->isEmpty())
        <div class="cart-empty">
            <p>Vous n'avez pas encore ajouté de sac à vos favoris.</p>
            <a href="{{ route('products.index') }}" class="btn-outline-dark">Découvrir la boutique</a>
        </div>
    @else
        <div class="shop-grid" style="padding-top: 2.5rem;">
            @foreach($items as $product)
                <article class="shop-card" data-wishlist-card>
                    <button type="button" class="wishlist-btn shop-card-wishlist is-active"
                            data-product-id="{{ $product->id }}"
                            data-toggle-url="{{ route('wishlist.toggle', $product) }}"
                            aria-label="Retirer des favoris">
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

                    @if($product->in_stock)
                        <form method="POST" action="{{ route('cart.add', $product) }}" style="margin-top:.7rem;">
                            @csrf
                            <button type="submit" class="btn-outline-dark" style="width:100%;">Ajouter au panier</button>
                        </form>
                    @else
                        <span class="btn-outline-dark" style="width:100%; display:block; text-align:center; opacity:.5; margin-top:.7rem;">Épuisé</span>
                    @endif
                </article>
            @endforeach
        </div>
    @endif

</div>
</section>

@endsection
