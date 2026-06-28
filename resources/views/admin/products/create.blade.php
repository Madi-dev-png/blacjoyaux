@extends('layouts.admin')

@section('title', 'Nouveau produit')

@section('content')
<div class="admin-topbar">
    <h1>Nouveau produit</h1>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline btn-sm">← Retour</a>
</div>

<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
    @csrf
    @include('admin.products._form')
    <div style="margin-top:1.5rem; display:flex; gap:1rem;">
        <button type="submit" class="btn btn-primary">Créer le produit</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline">Annuler</a>
    </div>
</form>
@endsection
