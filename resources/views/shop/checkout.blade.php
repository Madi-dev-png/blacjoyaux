@extends('layouts.shop')

@section('title', 'Paiement — Blac Joyaux')

@section('content')
<section class="checkout-page">
<div class="container">

    {{-- ÉTAPES --}}
    <div class="cart-steps">
        <div class="cart-step">
            <span class="step-num">1</span>
            <span class="step-label">Panier</span>
        </div>
        <div class="cart-step-line"></div>
        <div class="cart-step is-active">
            <span class="step-num">2</span>
            <span class="step-label">Paiement</span>
        </div>
        <div class="cart-step-line"></div>
        <div class="cart-step">
            <span class="step-num">3</span>
            <span class="step-label">Confirmation</span>
        </div>
    </div>

    @if(session('error'))
        <p class="cart-flash cart-flash-error">{{ session('error') }}</p>
    @endif

    <form method="POST" action="{{ route('checkout.store') }}" class="checkout-layout" id="checkoutForm">
        @csrf

        <div class="checkout-main">

            {{-- LIVRAISON --}}
           <div class="checkout-block">
                <h2>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 3v5h-7V8Z"/>
                        <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                    </svg>
                    Informations de livraison
                </h2>

                <div class="checkout-field">
                    <label for="customer_name">Nom complet</label>
                    <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" placeholder="Ex: Amenan Konan" required>
                    @error('customer_name')<div class="checkout-error">{{ $message }}</div>@enderror
                </div>

                <div class="checkout-row">
                    <div class="checkout-field">
                        <label for="customer_phone">Téléphone</label>
                        <input type="tel" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" placeholder="+225 07 00 00 00 00" required>
                        @error('customer_phone')<div class="checkout-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="checkout-field">
                        <label for="city">Commune</label>
                        <select id="city" name="city" required>
                            <option value="" disabled selected>Sélectionnez une commune</option>
                            <optgroup label="Abidjan">
                                @foreach(['Abobo','Adjamé','Attécoubé','Cocody','Koumassi','Marcory','Plateau','Port-Bouët','Treichville','Yopougon','Bingerville','Songon','Anyama'] as $commune)
                                    <option value="{{ $commune }}" data-zone="abidjan" {{ old('city') === $commune ? 'selected' : '' }}>{{ $commune }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Hors Abidjan">
                                <option value="Autre ville" data-zone="interieur" {{ old('city') === 'Autre ville' ? 'selected' : '' }}>Autre ville (intérieur du pays)</option>
                            </optgroup>
                        </select>
                        @error('city')<div class="checkout-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="checkout-row">
                    <div class="checkout-field">
                        <label for="customer_email">Email <span style="font-weight:400; color:var(--gris);">(pour recevoir la confirmation de commande)</span></label>
                        <input type="email" id="customer_email" name="customer_email" value="{{ old('customer_email') }}" placeholder="vous@example.com">
                        @error('customer_email')<div class="checkout-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="checkout-field">
                        <label for="quartier">Quartier</label>
                        <input type="text" id="quartier" name="quartier" value="{{ old('quartier') }}" placeholder="Ex: Angré 7ème Tranche">
                    </div>
                </div>

                <div class="checkout-field">
                    <label for="shipping_address">Adresse précise</label>
                    <input type="text" id="shipping_address" name="shipping_address" value="{{ old('shipping_address') }}" placeholder="Rue, Bâtiment, Appt..." required>
                    @error('shipping_address')<div class="checkout-error">{{ $message }}</div>@enderror
                </div>

                <input type="hidden" name="delivery_method" id="delivery_method" value="abidjan">
            </div>

            {{-- PAIEMENT --}}
           <div class="checkout-block">
                <h2>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/>
                    </svg>
                    Moyen de paiement
                </h2>

                <div class="payment-methods">
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="orange_money" checked>
                        <span class="payment-check">✓</span>
                        <span class="payment-logo payment-logo-orange">ORANGE<br>MONEY</span>
                        <span class="payment-name">Orange</span>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="wave">
                        <span class="payment-check">✓</span>
                        <span class="payment-logo payment-logo-wave">WAVE</span>
                        <span class="payment-name">Wave</span>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="mtn_momo">
                        <span class="payment-check">✓</span>
                        <span class="payment-logo payment-logo-mtn">MTN<br>MOMO</span>
                        <span class="payment-name">MTN</span>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="a_la_livraison">
                        <span class="payment-check">✓</span>
                       <span class="payment-logo payment-logo-cash">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                        </span>
                        <span class="payment-name">Espèces</span>
                    </label>
                </div>
                @error('payment_method')<div class="checkout-error">{{ $message }}</div>@enderror
            </div>

<button type="submit" class="btn-confirm-order">
                Confirmer la commande
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                </svg>
            </button>
            <p class="checkout-terms">En cliquant sur confirmer, vous acceptez nos conditions générales de vente.</p>
        </div>

        {{-- RÉCAPITULATIF --}}
        <aside class="checkout-summary">
            <h2>Récapitulatif</h2>

            @foreach($items as $item)
                @php $product = $item['product']; @endphp
                <div class="checkout-summary-item">
                    <div class="checkout-summary-thumb">
                        @if($product->image)
                            <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}">
                        @else
                            <span class="placeholder-ico">◈</span>
                        @endif
                    </div>
                    <div class="checkout-summary-info">
                        <div class="name">{{ $product->name }}</div>
                        @if($product->color)<div class="detail">Coloris : {{ $product->color }}</div>@endif
                        <div class="detail">Quantité : {{ $item['quantity'] }}</div>
                        <div class="line-price">{{ number_format($item['line_total'], 0, ',', ' ') }} CFA</div>
                    </div>
                </div>
            @endforeach

            <div class="checkout-summary-lines">
                <div class="summary-line"><span>Sous-total</span><span>{{ number_format($subtotal, 0, ',', ' ') }} CFA</span></div>
                <div class="summary-line"><span>Livraison</span><span id="deliveryFeeDisplay">{{ number_format($fees['abidjan'], 0, ',', ' ') }} CFA</span></div>
                <div class="summary-line"><span>Taxes</span><span>0 CFA</span></div>
            </div>

            <div class="summary-total">
                <div>
                    <span>Total</span>
                    <div class="tva-note">TVA incluse</div>
                </div>
                <span id="totalDisplay">{{ number_format($subtotal + $fees['abidjan'], 0, ',', ' ') }} CFA</span>
            </div>
<div class="checkout-trust">
                <p>
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/>
                    </svg>
                    Paiement 100% sécurisé
                </p>
                <p>
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 3v5h-7V8Z"/>
                        <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                    </svg>
                    Livraison estimée : 24h – 48h
                </p>
                <p>
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>
                    </svg>
                    Artisanat authentique d'Abidjan
                </p>
            </div>
        </aside>
    </form>
</div>
</section>

<script>
(function () {
    const citySelect = document.getElementById('city');
    const deliveryMethodInput = document.getElementById('delivery_method');
    const deliveryFeeDisplay = document.getElementById('deliveryFeeDisplay');
    const totalDisplay = document.getElementById('totalDisplay');

    const fees = { abidjan: {{ $fees['abidjan'] }}, interieur: {{ $fees['interieur'] }} };
    const subtotal = {{ $subtotal }};

    function fmt(n) { return n.toLocaleString('fr-FR').replace(/,/g, ' ') + ' CFA'; }

    citySelect.addEventListener('change', function () {
        const zone = citySelect.options[citySelect.selectedIndex].dataset.zone || 'abidjan';
        deliveryMethodInput.value = zone;
        const fee = fees[zone] ?? 0;
        deliveryFeeDisplay.textContent = fmt(fee);
        totalDisplay.textContent = fmt(subtotal + fee);
    });
})();
</script>
@endsection