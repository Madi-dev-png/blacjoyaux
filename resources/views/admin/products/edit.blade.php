@extends('layouts.admin')

@section('title', 'Éditer un produit')

@section('content')
<div class="admin-topbar">
    <h1>Éditer : {{ $product->name }}</h1>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline btn-sm">← Retour</a>
</div>

<form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    @include('admin.products._form')
    <div style="margin-top:1.5rem; display:flex; gap:1rem;">
        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        <a href="{{ route('products.show', $product) }}" target="_blank" class="btn btn-outline">↗ Voir sur la boutique</a>
    </div>
</form>
@endsection
