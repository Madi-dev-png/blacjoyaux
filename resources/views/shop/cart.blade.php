@extends('layouts.shop')

@section('title', 'Panier — Blac Joyaux')

@section('content')

<section class="cart-page">
<div class="container">

    {{-- ÉTAPES --}}
    <div class="cart-steps">
        <div class="cart-step is-active">
            <span class="step-num">1</span>
            <span class="step-label">Panier</span>
        </div>
        <div class="cart-step-line"></div>
        <div class="cart-step">
            <span class="step-num">2</span>
            <span class="step-label">Paiement</span>
        </div>
        <div class="cart-step-line"></div>
        <div class="cart-step">
            <span class="step-num">3</span>
            <span class="step-label">Confirmation</span>
        </div>
    </div>

    @if(session('success'))
        <p class="cart-flash">{{ session('success') }}</p>
    @endif

    <div class="cart-layout">

        {{-- TABLE PRODUITS --}}
        <div class="cart-table-wrap">
            @if(count($items))
                <div class="cart-table-head">
                    <span>Produit</span>
                    <span>Quantité</span>
                    <span>Total</span>
                </div>

                @foreach($items as $item)
                    @php $product = $item['product']; @endphp
                    <div class="cart-row">
                        <div class="cart-row-product">
                            <a href="{{ route('products.show', $product) }}" class="cart-row-thumb">
                                @if($product->image)
                                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}">
                                @else
                                    <span class="placeholder-ico">◈</span>
                                @endif
                            </a>
                            <div class="cart-row-info">
                                <a href="{{ route('products.show', $product) }}" class="cart-row-name">{{ $product->name }}</a>
                                <span class="cart-row-price">{{ $product->formatted_price }}</span>
                            </div>
                        </div>

                        <form action="{{ route('cart.update', $product) }}" method="POST" class="cart-row-qty">
                            @csrf @method('PATCH')
                            <button type="button" class="qty-btn qty-minus" aria-label="Diminuer">−</button>
                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="20" class="qty-input" onchange="this.form.submit()">
                            <button type="button" class="qty-btn qty-plus" aria-label="Augmenter">+</button>
                        </form>

                        <div class="cart-row-total">
                            {{ number_format($item['line_total'], 0, ',', ' ') }} FCFA
                        </div>

                        <form action="{{ route('cart.remove', $product) }}" method="POST" class="cart-row-remove">
                            @csrf @method('DELETE')
                            <button type="submit" aria-label="Retirer">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                    <path d="M10 11v6"/><path d="M14 11v6"/>
                                    <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                @endforeach

                <div class="cart-table-footer">
                    <a href="{{ route('products.index') }}" class="cart-back-link">← Continuer mes achats</a>
                    <button type="button" class="cart-refresh-link" onclick="location.reload()">
                        ⟳ Mettre à jour le panier
                    </button>
                </div>
            @else
                <div class="cart-empty">
                    <p>Votre panier est vide.</p>
                    <a href="{{ route('products.index') }}" class="btn-outline-dark">Découvrir la boutique</a>
                </div>
            @endif
        </div>

        {{-- RÉSUMÉ --}}
        @if(count($items))
        <aside class="cart-summary">
            <h2>Résumé de la commande</h2>

            <div class="summary-line">
                <span>Sous-total</span>
                <span>{{ number_format($subtotal, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="summary-line">
                <span>Frais de livraison</span>
                <span class="summary-free">Offerts</span>
            </div>
            <div class="summary-line">
                <span>Taxes (calculées lors du paiement)</span>
                <span>0 FCFA</span>
            </div>

            <div class="summary-total">
                <span>Total</span>
                <span>{{ number_format($subtotal, 0, ',', ' ') }} FCFA</span>
            </div>

            <label class="summary-promo-label">Code promo</label>
            <div class="summary-promo">
                <input type="text" placeholder="Entrer le code">
                <button type="button">Appliquer</button>
            </div>

           <a href="{{ route('checkout.index') }}" class="btn-checkout">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                Procéder au paiement
            </a>

           <div class="summary-secure">
                <span class="ico">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/>
                    </svg>
                </span>
                <span>Paiement sécurisé avec un chiffrement AES-256</span>
            </div>
        </aside>
        @endif
    </div>

    {{-- SUGGESTIONS --}}
    @if($suggestions->count())
    <section class="cart-suggestions">
        <h2>Complétez votre look</h2>
        <div class="cart-suggestions-grid">
            @foreach($suggestions as $s)
                <a href="{{ route('products.show', $s) }}" class="suggestion-card">
                    <div class="suggestion-thumb">
                        @if($s->image)
                            <img src="{{ asset('storage/'.$s->image) }}" alt="{{ $s->name }}">
                        @else
                            <span class="placeholder-ico">◈</span>
                        @endif
                        @if($s->is_featured)
                            <span class="suggestion-badge">Nouveau</span>
                        @endif
                    </div>
                    <div class="suggestion-info">
                        <div class="suggestion-name">{{ $s->name }}</div>
                        <div class="suggestion-price">{{ $s->formatted_price }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
    @endif

</div>
</section>

@endsection