@extends('layouts.admin')

@section('title', 'Codes promo')

@section('content')
<div class="admin-topbar">
    <h1>Codes promo</h1>
</div>

<div class="admin-grid-2">
    <div class="panel">
        <h2>Codes existants</h2>
        @if($promoCodes->isEmpty())
            <p style="color:var(--gris);">Aucun code promo. Créez-en un via le formulaire.</p>
        @else
            <div class="table-scroll">
            <table class="data">
                <thead><tr><th>Code</th><th>Réduction</th><th>Utilisation</th><th>Expire</th><th>État</th><th></th></tr></thead>
                <tbody>
                @foreach($promoCodes as $promo)
                    <tr>
                        <td>
                            <details>
                                <summary style="cursor:pointer; font-weight:600;">{{ $promo->code }}</summary>
                                <form method="POST" action="{{ route('admin.promo-codes.update', $promo) }}" style="margin-top:.8rem;">
                                    @csrf @method('PATCH')
                                    <input type="text" name="code" value="{{ $promo->code }}" style="width:100%; margin-bottom:.4rem; text-transform:uppercase;" required maxlength="40">
                                    <div style="display:flex; gap:.5rem; margin-bottom:.4rem;">
                                        <select name="type" style="flex:1;">
                                            <option value="percent" {{ $promo->type==='percent'?'selected':'' }}>Pourcentage (%)</option>
                                            <option value="fixed" {{ $promo->type==='fixed'?'selected':'' }}>Montant fixe (FCFA)</option>
                                        </select>
                                        <input type="number" name="value" value="{{ $promo->value }}" style="width:100px;" min="1" required title="Valeur">
                                    </div>
                                    <div style="display:flex; gap:.5rem; margin-bottom:.4rem;">
                                        <input type="number" name="min_subtotal" value="{{ $promo->min_subtotal }}" style="flex:1;" min="0" placeholder="Panier min. (FCFA)">
                                        <input type="number" name="max_uses" value="{{ $promo->max_uses }}" style="flex:1;" min="1" placeholder="Nb. d'utilisations max">
                                    </div>
                                    <input type="date" name="expires_at" value="{{ $promo->expires_at?->format('Y-m-d') }}" style="width:100%; margin-bottom:.4rem;">
                                    <div style="display:flex; gap:.5rem; align-items:center; flex-wrap:wrap;">
                                        <label style="font-size:.85rem;"><input type="checkbox" name="is_active" value="1" {{ $promo->is_active?'checked':'' }}> Actif</label>
                                        <button class="btn btn-primary btn-sm" type="submit">Enregistrer</button>
                                    </div>
                                </form>
                            </details>
                        </td>
                        <td>{{ $promo->type === 'percent' ? $promo->value.' %' : number_format($promo->value,0,',',' ').' F' }}</td>
                        <td>{{ $promo->used_count }}{{ $promo->max_uses ? ' / '.$promo->max_uses : '' }}</td>
                        <td>{{ $promo->expires_at?->translatedFormat('d M Y') ?? '—' }}</td>
                        <td>{!! $promo->is_active ? '<span class="status-pill st-livree">Actif</span>' : '<span class="status-pill st-annulee">Inactif</span>' !!}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.promo-codes.destroy', $promo) }}" onsubmit="return confirm('Supprimer ce code promo ?');">
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
        <h2>Créer un code promo</h2>
        <form method="POST" action="{{ route('admin.promo-codes.store') }}">
            @csrf
            <div class="field">
                <label for="code">Code *</label>
                <input type="text" id="code" name="code" required style="width:100%; text-transform:uppercase;" placeholder="Ex: BIENVENUE10" maxlength="40">
            </div>
            <div class="field">
                <label for="type">Type de réduction *</label>
                <select id="type" name="type" style="width:100%;">
                    <option value="percent">Pourcentage (%)</option>
                    <option value="fixed">Montant fixe (FCFA)</option>
                </select>
            </div>
            <div class="field">
                <label for="value">Valeur *</label>
                <input type="number" id="value" name="value" required style="width:100%;" min="1" placeholder="Ex: 10">
            </div>
            <div class="field">
                <label for="min_subtotal">Panier minimum (FCFA)</label>
                <input type="number" id="min_subtotal" name="min_subtotal" style="width:100%;" min="0" placeholder="Facultatif">
            </div>
            <div class="field">
                <label for="max_uses">Nombre d'utilisations maximum</label>
                <input type="number" id="max_uses" name="max_uses" style="width:100%;" min="1" placeholder="Facultatif — illimité si vide">
            </div>
            <div class="field">
                <label for="expires_at">Date d'expiration</label>
                <input type="date" id="expires_at" name="expires_at" style="width:100%;">
            </div>
            <button class="btn btn-primary btn-block" type="submit">Créer le code</button>
        </form>
    </div>
</div>
@endsection
