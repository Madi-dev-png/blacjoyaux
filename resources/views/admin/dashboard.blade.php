@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('content')
<div class="admin-topbar">
    <h1>Tableau de bord</h1>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">+ Nouveau produit</a>
</div>

<div class="stat-grid">
    <div class="stat-card"><div class="label">Produits</div><div class="value">{{ $stats['products'] }}</div></div>
    <div class="stat-card"><div class="label">Produits actifs</div><div class="value">{{ $stats['active'] }}</div></div>
    <div class="stat-card"><div class="label">Commandes</div><div class="value">{{ $stats['orders'] }}</div></div>
    <div class="stat-card"><div class="label">En attente</div><div class="value">{{ $stats['pending'] }}</div></div>
    <div class="stat-card"><div class="label">Chiffre d'affaires</div><div class="value" style="font-size:1.5rem;">{{ number_format($stats['revenue'],0,',',' ') }} F</div></div>
    <div class="stat-card"><div class="label">Stock faible</div><div class="value">{{ $stats['low_stock'] }}</div></div>
</div>

<div class="admin-grid-2">
    <div class="panel">
        <h2>Dernières commandes</h2>
        @if($recentOrders->isEmpty())
            <p style="color:var(--gris);">Aucune commande pour le moment.</p>
        @else
            <table class="data">
                <thead><tr><th>Réf.</th><th>Client</th><th>Total</th><th>Statut</th></tr></thead>
                <tbody>
                @foreach($recentOrders as $order)
                    <tr>
                        <td><a href="{{ route('admin.orders.show', $order) }}">{{ $order->reference }}</a></td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ number_format($order->total,0,',',' ') }} F</td>
                        <td><span class="status-pill st-{{ $order->status }}">{{ $order->status_label }}</span></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="panel">
        <h2>Stock à surveiller</h2>
        @if($lowStock->isEmpty())
            <p style="color:var(--gris);">Tous les stocks sont confortables 👍</p>
        @else
            <table class="data">
                <thead><tr><th>Produit</th><th>Stock</th></tr></thead>
                <tbody>
                @foreach($lowStock as $product)
                    <tr>
                        <td><a href="{{ route('admin.products.edit', $product) }}">{{ $product->name }}</a></td>
                        <td><strong style="color:var(--bad);">{{ $product->stock }}</strong></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
