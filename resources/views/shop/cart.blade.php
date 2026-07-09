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

                        <form action="{{ route('cart.remove', $product) }}" method="POST" class="cart-row-remove js-confirm-remove">
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
            @if($promo)
                <div class="summary-line" style="color:var(--vert-dark, #1a7a4c);">
                    <span>Réduction ({{ $promo->code }})</span>
                    <span>&minus;{{ number_format($discount, 0, ',', ' ') }} FCFA</span>
                </div>
            @endif

            <div class="summary-total">
                <span>Total</span>
                <span>{{ number_format($subtotal - $discount, 0, ',', ' ') }} FCFA</span>
            </div>

            @if($promo)
                <div class="summary-promo" style="display:flex; align-items:center; justify-content:space-between; gap:.5rem;">
                    <span style="font-size:.85rem;">Code appliqué : <strong>{{ $promo->code }}</strong></span>
                    <form method="POST" action="{{ route('cart.promo.remove') }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm" style="background:none; color:var(--bad);">Retirer</button>
                    </form>
                </div>
            @else
                <label class="summary-promo-label" for="promoCode">Code promo</label>
                <form method="POST" action="{{ route('cart.promo.apply') }}" class="summary-promo">
                    @csrf
                    <input type="text" id="promoCode" name="code" placeholder="Entrer le code" style="text-transform:uppercase;">
                    <button type="submit">Appliquer</button>
                </form>
            @endif

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

            <a href="https://wa.me/{{ $brandWhatsapp }}?text={{ urlencode('Bonjour Blac Joyaux, je souhaite finaliser ma commande depuis mon panier.') }}" target="_blank" rel="noopener" class="cart-whatsapp">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                </svg>
                Finaliser ma commande sur WhatsApp
            </a>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.cart-row-qty').forEach(function (form) {
        const input = form.querySelector('.qty-input');
        const minusBtn = form.querySelector('.qty-minus');
        const plusBtn = form.querySelector('.qty-plus');

        minusBtn.addEventListener('click', function () {
            const current = parseInt(input.value, 10) || 1;
            if (current > 1) {
                input.value = current - 1;
                form.submit();
            }
        });

       plusBtn.addEventListener('click', function () {
            const current = parseInt(input.value, 10) || 1;
            const max = parseInt(input.max, 10) || 20;
            if (current < max) {
                input.value = current + 1;
                form.submit();
            }
        });
    });

    document.querySelectorAll('.js-confirm-remove').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Retirer cet article ?',
                text: 'Ce produit sera supprimé de votre panier.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui, retirer',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#5e4b8c',
                cancelButtonColor: '#aaa',
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>

@endsection