@extends('layouts.admin')

@section('title', 'Commandes')

@section('content')
<div class="admin-topbar">
    <h1>Commandes</h1>
</div>

<div class="panel">
    <div style="display:flex; gap:.5rem; margin-bottom:1.2rem; flex-wrap:wrap;">
        <a href="{{ route('admin.orders.index') }}" class="chip {{ !request('status') ? 'is-active' : '' }}">Toutes</a>
        @foreach(\App\Models\Order::STATUSES as $key => $label)
            <a href="{{ route('admin.orders.index', ['status' => $key]) }}" class="chip {{ request('status') === $key ? 'is-active' : '' }}">{{ $label }}</a>
        @endforeach
    </div>

    @if($orders->isEmpty())
        <p style="color:var(--gris);">Aucune commande.</p>
    @else
        <div class="table-scroll">
        <table class="data">
            <thead><tr><th>Réf.</th><th>Date</th><th>Client</th><th>Articles</th><th>Total</th><th>Statut</th><th></th></tr></thead>
            <tbody>
            @foreach($orders as $order)
                <tr>
                    <td><strong>{{ $order->reference }}</strong></td>
                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                    <td>{{ $order->customer_name }}<br><small style="color:var(--gris);">{{ $order->customer_phone }}</small></td>
                    <td>{{ $order->items_count }}</td>
                    <td>{{ number_format($order->total,0,',',' ') }} F</td>
                    <td><span class="status-pill st-{{ $order->status }}">{{ $order->status_label }}</span></td>
                    <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline btn-sm">Détail</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
        </div>
        <div style="margin-top:1.2rem;">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
