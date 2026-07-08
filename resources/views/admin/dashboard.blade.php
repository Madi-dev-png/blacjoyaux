@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('content')
<div class="admin-topbar">
    <div>
        <h1>Tableau de bord</h1>
        <p class="subtitle">Bienvenue, voici un aperçu de votre boutique.</p>
    </div>
    <div class="admin-topbar-right">
        <div class="admin-search">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            Rechercher...
        </div>
        <span class="admin-bell" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
        </span>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">+ Nouveau produit</a>
    </div>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-card-top">
            <span class="stat-card-ico"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></span>
            <span class="stat-trend is-{{ $trends['revenue']['direction'] }}">{{ $trends['revenue']['label'] }}</span>
        </div>
        <div class="label">Revenu (statuts payés)</div>
        <div class="value">{{ number_format($stats['revenue'],0,',',' ') }} FCFA</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <span class="stat-card-ico"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.9 7.5 12 2 3.1 7.5"/><path d="M3.1 7.5 12 13l8.9-5.5"/><path d="M12 13v9"/><path d="M3.1 7.5v9L12 22l8.9-5.5v-9"/></svg></span>
            <span class="stat-trend is-flat">{{ $stats['active'] }}/{{ $stats['products'] }} actifs</span>
        </div>
        <div class="label">Produits</div>
        <div class="value">{{ $stats['products'] }} articles</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <span class="stat-card-ico"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg></span>
            <span class="stat-trend is-{{ $trends['orders']['direction'] }}">{{ $trends['orders']['label'] }}</span>
        </div>
        <div class="label">Commandes totales</div>
        <div class="value">{{ $stats['orders'] }} ventes</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <span class="stat-card-ico"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></span>
            @if($stats['pending'] > 0)
                <span class="stat-trend is-down">à traiter</span>
            @else
                <span class="stat-trend is-flat">à jour</span>
            @endif
        </div>
        <div class="label">Commandes en attente</div>
        <div class="value">{{ $stats['pending'] }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <span class="stat-card-ico"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4"/><path d="M10.4 3.9 2.7 17a2 2 0 0 0 1.7 3h15.2a2 2 0 0 0 1.7-3L13.6 3.9a2 2 0 0 0-3.2 0Z"/><path d="M12 17h.01"/></svg></span>
            @if($stats['low_stock'] > 0)
                <span class="stat-trend is-down">à surveiller</span>
            @else
                <span class="stat-trend is-flat">stable</span>
            @endif
        </div>
        <div class="label">Stock faible</div>
        <div class="value">{{ $stats['low_stock'] }} article{{ $stats['low_stock'] > 1 ? 's' : '' }}</div>
    </div>
</div>

<div class="panel">
    <div class="panel-head">
        <h2>Ventes des 14 derniers jours</h2>
    </div>
    <div class="sales-chart">
        @foreach($salesChart as $i => $point)
            <div class="sales-chart-bar">
                <div class="sales-chart-fill" style="height: {{ $point['height'] }}%;" title="{{ $point['label'] }} — {{ number_format($point['total'],0,',',' ') }} FCFA"></div>
                @if($i % 2 === 0)
                    <span class="sales-chart-label">{{ $point['label'] }}</span>
                @endif
            </div>
        @endforeach
    </div>
</div>

<div class="admin-grid-2">
    <div class="panel">
        <div class="panel-head">
            <h2>Commandes récentes</h2>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-sm">Voir tout</a>
        </div>
        @if($recentOrders->isEmpty())
            <p style="color:var(--gris);">Aucune commande pour le moment.</p>
        @else
            <div class="table-scroll">
            <table class="data">
                <thead><tr><th>Réf.</th><th>Client</th><th>Total</th><th>Statut</th><th>Date</th></tr></thead>
                <tbody>
                @foreach($recentOrders as $order)
                    <tr>
                        <td><a href="{{ route('admin.orders.show', $order) }}">{{ $order->reference }}</a></td>
                        <td>{{ $order->customer_name }}</td>
                        <td><strong>{{ number_format($order->total,0,',',' ') }} F</strong></td>
                        <td><span class="status-pill st-{{ $order->status }}">{{ $order->status_label }}</span></td>
                        <td style="color:var(--gris); font-size:.82rem;">{{ $order->created_at->format('d M, H:i') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            </div>
        @endif
    </div>

    <div>
        <div class="panel">
            <h2>Produits les plus vendus</h2>
            @if($topProducts->isEmpty())
                <p style="color:var(--gris); font-size:.88rem;">Pas encore de vente enregistrée.</p>
            @else
                @foreach($topProducts as $i => $item)
                    <div class="ranking-row">
                        <span class="ranking-num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <div>
                            <div class="ranking-name">{{ $item->product_name }}</div>
                            <div class="ranking-sub">{{ $item->total_qty }} vente{{ $item->total_qty > 1 ? 's' : '' }}</div>
                        </div>
                        <div class="ranking-value">{{ number_format($item->total_revenue,0,',',' ') }} FCFA</div>
                    </div>
                @endforeach
            @endif
        </div>

        @if($lowStock->isNotEmpty())
        <div class="panel">
            <h2>Stock à surveiller</h2>
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
        </div>
        @endif

        @if($weakProducts->isNotEmpty())
        <div class="panel">
            <div class="panel-head">
                <h2>Santé du catalogue</h2>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline btn-sm">Voir tout</a>
            </div>
            @foreach($weakProducts as $product)
                <div class="health-row">
                    <div style="flex:1; min-width:0;">
                        <a href="{{ route('admin.products.edit', $product) }}" class="ranking-name">{{ $product->name }}</a>
                        <div class="seo-meter"><span style="width: {{ $product->seo_score }}%; background: var(--bad);"></span></div>
                    </div>
                    <span class="health-score">{{ $product->seo_score }}/100</span>
                </div>
            @endforeach
        </div>
        @endif

        <div class="admin-help-card">
            <h3>Besoin d'aide ?</h3>
            <p>Contactez votre conseiller dédié pour toute question sur la gestion de votre showroom digital.</p>
            <a href="{{ route('admin.faqs.index') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                Parler à un expert
            </a>
        </div>
    </div>
</div>
@endsection