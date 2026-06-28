@extends('layouts.admin')

@section('title', 'Produits')

@section('content')
<div class="admin-topbar">
    <h1>Produits</h1>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">+ Nouveau produit</a>
</div>

<div class="panel">
    <form method="GET" action="{{ route('admin.products.index') }}" style="display:flex; gap:.6rem; margin-bottom:1.2rem;">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="Rechercher un produit…" style="flex:1; padding:.6rem .9rem; border:1px solid var(--ivoire-2); border-radius:var(--r-sm);">
        <button class="btn btn-primary btn-sm" type="submit">Rechercher</button>
    </form>

    @if($products->isEmpty())
        <p style="color:var(--gris);">Aucun produit. <a href="{{ route('admin.products.create') }}">Créez le premier</a>.</p>
    @else
        <div class="table-scroll">
        <table class="data">
            <thead><tr><th>Produit</th><th>Catégorie</th><th>Prix</th><th>Stock</th><th>SEO</th><th>État</th><th></th></tr></thead>
            <tbody>
            @foreach($products as $product)
                <tr>
                    <td><strong>{{ $product->name }}</strong></td>
                    <td>{{ $product->category->name ?? '—' }}</td>
                    <td>{{ number_format($product->price,0,',',' ') }} F</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                        <span class="seo-meter" style="display:inline-block; width:60px; vertical-align:middle;">
                            <span style="width:{{ $product->seo_score }}%; background:{{ $product->seo_score >= 80 ? 'var(--vert-jade)' : ($product->seo_score >= 50 ? 'var(--or)' : 'var(--bad)') }};"></span>
                        </span>
                        <small>{{ $product->seo_score }}</small>
                    </td>
                    <td>{!! $product->is_active ? '<span class="status-pill st-livree">Actif</span>' : '<span class="status-pill st-annulee">Inactif</span>' !!}</td>
                    <td style="white-space:nowrap;">
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline btn-sm">Éditer</a>
                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" style="display:inline;" onsubmit="return confirm('Supprimer ce produit ?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm" style="background:none;color:var(--bad);" type="submit">Suppr.</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        </div>
        <div style="margin-top:1.2rem;">{{ $products->links() }}</div>
    @endif
</div>
@endsection
