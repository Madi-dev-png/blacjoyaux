@extends('layouts.admin')

@section('title', 'FAQ')

@section('content')
<div class="admin-topbar">
    <h1>Foire aux questions</h1>
</div>

<div class="admin-grid-2">
    <div class="panel">
        <h2>Questions existantes</h2>
        @if($faqs->isEmpty())
            <p style="color:var(--gris);">Aucune question. Ajoutez-en une via le formulaire.</p>
        @else
            <div class="table-scroll">
            <table class="data">
                <thead><tr><th>Question</th><th>Catégorie</th><th>État</th><th></th></tr></thead>
                <tbody>
                @foreach($faqs as $faq)
                    <tr>
                        <td>
                            <details>
                                <summary style="cursor:pointer;">{{ $faq->question }}</summary>
                                <form method="POST" action="{{ route('admin.faqs.update', $faq) }}" style="margin-top:.8rem;">
                                    @csrf @method('PATCH')
                                    <input type="text" name="question" value="{{ $faq->question }}" style="width:100%; margin-bottom:.4rem;">
                                    <textarea name="answer" style="width:100%; min-height:70px; margin-bottom:.4rem;">{{ $faq->answer }}</textarea>
                                    <div style="display:flex; gap:.5rem; align-items:center; flex-wrap:wrap;">
                                        <select name="category">
                                            @foreach(['general'=>'Général','livraison'=>'Livraison','paiement'=>'Paiement','produit'=>'Produit'] as $k=>$v)
                                                <option value="{{ $k }}" {{ $faq->category===$k?'selected':'' }}>{{ $v }}</option>
                                            @endforeach
                                        </select>
                                        <input type="number" name="sort_order" value="{{ $faq->sort_order }}" style="width:70px;" title="Ordre">
                                        <label style="font-size:.85rem;"><input type="checkbox" name="is_active" value="1" {{ $faq->is_active?'checked':'' }}> Actif</label>
                                        <button class="btn btn-primary btn-sm" type="submit">Enregistrer</button>
                                    </div>
                                </form>
                            </details>
                        </td>
                        <td>{{ ucfirst($faq->category) }}</td>
                        <td>{!! $faq->is_active ? '<span class="status-pill st-livree">Actif</span>' : '<span class="status-pill st-annulee">Masqué</span>' !!}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" onsubmit="return confirm('Supprimer ?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm" style="background:none;color:var(--bad);" type="submit">Suppr.</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            </div>
        @endif
    </div>

    <div class="panel">
        <h2>Ajouter une question</h2>
        <form method="POST" action="{{ route('admin.faqs.store') }}">
            @csrf
            <div class="field">
                <label for="q">Question *</label>
                <input type="text" id="q" name="question" required style="width:100%;">
            </div>
            <div class="field">
                <label for="a">Réponse *</label>
                <textarea id="a" name="answer" required style="width:100%; min-height:90px;"></textarea>
            </div>
            <div class="field">
                <label for="cat">Catégorie *</label>
                <select id="cat" name="category" style="width:100%;">
                    <option value="general">Général</option>
                    <option value="livraison">Livraison</option>
                    <option value="paiement">Paiement</option>
                    <option value="produit">Produit</option>
                </select>
            </div>
            <div class="field">
                <label for="so">Ordre d'affichage</label>
                <input type="number" id="so" name="sort_order" value="0" style="width:100%;">
            </div>
            <button class="btn btn-primary btn-block" type="submit">Ajouter à la FAQ</button>
        </form>
    </div>
</div>
@endsection
