@extends('layouts.shop')

@section('title', 'Commande confirmée — Blac Joyaux')

@section('content')
<section class="confirm-page">
<div class="container">

    <div class="confirm-card">

        {{-- EN-TÊTE --}}
        <div class="confirm-head">
            <div class="confirm-check">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </div>
            <h1>Commande confirmée !</h1>
            <p class="confirm-ref">Référence : #{{ $order->reference }}</p>

            @php
                $delayLabels = [
                    'abidjan'   => 'Livraison sous 1 à 3 jours',
                    'interieur' => 'Livraison sous 5 à 7 jours',
                    'retrait'   => 'Retrait en boutique disponible sous 24h',
                ];
            @endphp
            <span class="confirm-delay-pill">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 3v5h-7V8Z"/>
                    <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                </svg>
                {{ $delayLabels[$order->delivery_method] ?? 'Livraison en cours de préparation' }}
            </span>
        </div>

        <div class="confirm-body">

            {{-- RÉCAPITULATIF --}}
            <div class="confirm-col">
                <h2>Récapitulatif</h2>

                @foreach($order->items as $item)
                    <div class="confirm-item">
                        <div class="confirm-item-thumb">
                            @if($item->product && $item->product->image)
                                <img src="{{ asset('storage/'.$item->product->image) }}" alt="{{ $item->product_name }}">
                            @else
                                <span class="placeholder-ico">◈</span>
                            @endif
                        </div>
                        <div class="confirm-item-info">
                            <div class="name">{{ $item->product_name }}</div>
                            @if($item->product && $item->product->color)
                                <div class="detail">{{ $item->product->color }} · Quantité : {{ $item->quantity }}</div>
                            @else
                                <div class="detail">Quantité : {{ $item->quantity }}</div>
                            @endif
                            <div class="price">{{ number_format($item->line_total, 0, ',', ' ') }} FCFA</div>
                        </div>
                    </div>
                @endforeach

                <div class="confirm-totals">
                    <div class="line"><span>Sous-total</span><span>{{ number_format($order->subtotal, 0, ',', ' ') }} FCFA</span></div>
                    <div class="line">
                        <span>Livraison</span>
                        <span>{{ $order->delivery_fee > 0 ? number_format($order->delivery_fee, 0, ',', ' ').' FCFA' : 'Offerte' }}</span>
                    </div>
                    <div class="line total"><span>Total</span><span>{{ number_format($order->total, 0, ',', ' ') }} FCFA</span></div>
                </div>
            </div>

            {{-- ADRESSE + PAIEMENT --}}
            <div class="confirm-col">
                <h2>Adresse de livraison</h2>
                <div class="confirm-address">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>
                    </svg>
                    <div>
                        <strong>{{ $order->customer_name }}</strong><br>
                        {{ $order->shipping_address }}<br>
                        {{ $order->city }}, Abidjan<br>
                        Côte d'Ivoire
                    </div>
                </div>

                <h2 class="confirm-payment-title">Mode de paiement</h2>
                @php
                    $paymentLabels = [
                        'orange_money'   => 'Orange Money',
                        'wave'           => 'Wave',
                        'mtn_momo'       => 'MTN Mobile Money',
                        'a_la_livraison' => 'Paiement à la livraison (Espèces)',
                    ];
                @endphp
                <div class="confirm-payment-box">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/>
                    </svg>
                    <div>
                        <strong>{{ $paymentLabels[$order->payment_method] ?? $order->payment_method }}</strong><br>
                        <span class="confirm-payment-date">Commande passée le {{ $order->created_at->translatedFormat('d M Y') }}</span>
                    </div>
                </div>

                <p class="confirm-note">
                    Un e-mail de confirmation vient de vous être envoyé. Notre atelier prépare actuellement votre pièce unique avec le plus grand soin.
                </p>
            </div>
        </div>

        <div class="confirm-footer">
            <a href="{{ route('products.index') }}" class="btn-confirm-back">Retour à la boutique</a>
        </div>
    </div>

</div>
</section>
@endsection