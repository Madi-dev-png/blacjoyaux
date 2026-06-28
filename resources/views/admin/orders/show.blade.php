@extends('layouts.admin')

@section('title', 'Commande '.$order->reference)

@section('content')
<div class="admin-topbar">
    <h1>Commande {{ $order->reference }}</h1>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-sm">← Retour</a>
</div>

<div class="admin-grid-2">
    <div class="panel">
        <h2>Articles</h2>
        <table class="data">
            <thead><tr><th>Produit</th><th>PU</th><th>Qté</th><th>Total</th></tr></thead>
            <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ number_format($item->unit_price,0,',',' ') }} F</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->line_total,0,',',' ') }} F</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div style="margin-top:1rem; text-align:right;">
            <div class="summary-row" style="justify-content:flex-end; gap:2rem;"><span>Sous-total</span><span>{{ number_format($order->subtotal,0,',',' ') }} F</span></div>
            <div class="summary-row" style="justify-content:flex-end; gap:2rem;"><span>Livraison</span><span>{{ number_format($order->delivery_fee,0,',',' ') }} F</span></div>
            <div class="summary-row total" style="justify-content:flex-end; gap:2rem;"><span>Total</span><span>{{ $order->formatted_total }}</span></div>
        </div>
    </div>

    <div>
        <div class="panel">
            <h2>Statut</h2>
            <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                @csrf @method('PATCH')
                <select name="status" style="width:100%; margin-bottom:.8rem;" onchange="this.form.submit()">
                    @foreach(\App\Models\Order::STATUSES as $key => $label)
                        <option value="{{ $key }}" {{ $order->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <noscript><button class="btn btn-primary btn-sm" type="submit">Mettre à jour</button></noscript>
            </form>
        </div>

        <div class="panel">
            <h2>Client</h2>
            <p style="font-size:.92rem; line-height:1.8;">
                <strong>{{ $order->customer_name }}</strong><br>
                📞 {{ $order->customer_phone }}<br>
                @if($order->customer_email)✉ {{ $order->customer_email }}<br>@endif
                📍 {{ $order->shipping_address }}, {{ $order->city }}<br>
                🚚 {{ ucfirst($order->delivery_method) }}<br>
                💳 {{ str_replace('_',' ', $order->payment_method) }}
            </p>
            @if($order->notes)
                <p style="margin-top:.8rem; padding-top:.8rem; border-top:1px solid var(--ivoire-2); font-size:.88rem; color:var(--gris);"><em>{{ $order->notes }}</em></p>
            @endif
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$order->customer_phone) }}" target="_blank" rel="noopener" class="btn btn-whatsapp btn-sm btn-block" style="margin-top:1rem;">Contacter sur WhatsApp</a>
        </div>
    </div>
</div>
@endsection
