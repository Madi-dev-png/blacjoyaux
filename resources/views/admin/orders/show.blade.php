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
            <p style="font-size:.92rem; line-height:2;">
                <strong>{{ $order->customer_name }}</strong><br>
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-2px; margin-right:.35rem; color:var(--gris);"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.804 1.6l-.468.35a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"/></svg>{{ $order->customer_phone }}<br>
                @if($order->customer_email)
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-2px; margin-right:.35rem; color:var(--gris);"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>{{ $order->customer_email }}<br>
                @endif
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-2px; margin-right:.35rem; color:var(--gris);"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>{{ $order->shipping_address }}, {{ $order->city }}<br>
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-2px; margin-right:.35rem; color:var(--gris);"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/></svg>{{ ucfirst($order->delivery_method) }}<br>
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-2px; margin-right:.35rem; color:var(--gris);"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>{{ str_replace('_',' ', $order->payment_method) }}
            </p>
            @if($order->notes)
                <p style="margin-top:.8rem; padding-top:.8rem; border-top:1px solid var(--ivoire-2); font-size:.88rem; color:var(--gris);"><em>{{ $order->notes }}</em></p>
            @endif
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$order->customer_phone) }}" target="_blank" rel="noopener" class="btn btn-whatsapp btn-sm btn-block" style="margin-top:1rem;">Contacter sur WhatsApp</a>
        </div>
    </div>
</div>
@endsection
