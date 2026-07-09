{{-- Formulaire produit partagé (création + édition) avec assistant SEO temps réel --}}
@php($isEdit = $product->exists)

<div class="admin-grid-2">
    {{-- Colonne gauche : informations produit --}}
    <div class="panel">
        <h2>Informations produit</h2>

        <div class="field">
            <label for="name">Nom du produit *</label>
            <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required style="width:100%;">
            @error('name')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="category_id">Catégorie</label>
            <select id="category_id" name="category_id" style="width:100%;">
                <option value="">— Aucune —</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" data-name="{{ $cat->name }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="field">
            <label for="collection">Collection</label>
            <select id="collection" name="collection" style="width:100%;">
                <option value="">— Aucune —</option>
                <option value="joyau_de_bla" {{ old('collection', $product->collection) === 'joyau_de_bla' ? 'selected' : '' }}>Joyau de Bla</option>
                <option value="collection_do" {{ old('collection', $product->collection) === 'collection_do' ? 'selected' : '' }}>Collection DO</option>
                <option value="capsule" {{ old('collection', $product->collection) === 'capsule' ? 'selected' : '' }}>Blac Héritage</option>
            </select>
            <small style="color:var(--gris); font-size:.78rem;">Détermine dans quelle section du site (accueil/boutique) ce produit apparaît, et avec quels sacs il partage ses pastilles de couleur.</small>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
            <div class="field">
                <label for="price">Prix (F CFA) *</label>
                <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" min="0" required style="width:100%;">
                @error('price')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="field">
                <label for="stock">Stock *</label>
                <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" min="0" required style="width:100%;">
                @error('stock')<div class="error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
            <div class="field">
                <label for="color">Coloris</label>
                <input type="text" id="color" name="color" value="{{ old('color', $product->color) }}" style="width:100%;">
            </div>
            <div class="field">
                <label for="material">Matière</label>
                <input type="text" id="material" name="material" value="{{ old('material', $product->material) }}" style="width:100%;">
            </div>
        </div>

        <div class="field">
            <label for="variant_group">Groupe de couleur <small style="color:var(--gris); font-weight:400;">(optionnel)</small></label>
            <input type="text" id="variant_group" name="variant_group" value="{{ old('variant_group', $product->variant_group) }}" placeholder="Ex: sac-bureau-femme" style="width:100%;">
            <small style="color:var(--gris); font-size:.78rem;">Donnez EXACTEMENT le même texte à tous les sacs qui sont de vraies variantes de couleur d'un même modèle (ex: tous les "sac-bureau-femme" partageront leurs pastilles de couleur). Laissez vide si ce sac n'a pas de variante de couleur, ou si un autre sac lui ressemble sans être le même modèle.</small>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem;">
            <div class="field">
                <label for="dimensions">Dimensions</label>
                <input type="text" id="dimensions" name="dimensions" value="{{ old('dimensions', $product->dimensions) }}" placeholder="27 cm x 16 cm x 11 cm" style="width:100%;">
            </div>
            <div class="field">
                <label for="closure">Fermeture</label>
                <input type="text" id="closure" name="closure" value="{{ old('closure', $product->closure) }}" placeholder="Broche en métal doré" style="width:100%;">
            </div>
            <div class="field">
                <label for="lining">Doublure</label>
                <input type="text" id="lining" name="lining" value="{{ old('lining', $product->lining) }}" placeholder="Gabardine 100% coton" style="width:100%;">
            </div>
        </div>

        <div class="field">
            <label for="short_description">Description courte</label>
            <input type="text" id="short_description" name="short_description" value="{{ old('short_description', $product->short_description) }}" maxlength="300" style="width:100%;">
        </div>

        <div class="field">
            <label for="description">Description complète</label>
            <textarea id="description" name="description" style="width:100%; min-height:120px;">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="field">
            <label for="story">Storytelling <small style="color:var(--gris); font-weight:400;">(optionnel)</small></label>
            <textarea id="story" name="story" style="width:100%; min-height:120px;" placeholder="Le récit de marque autour de ce produit (héritage, inspiration, symbolique...).">{{ old('story', $product->story) }}</textarea>
            <small style="color:var(--gris); font-size:.78rem;">Affiché sur la fiche produit dans un bloc séparé de la description. Laissez vide tant que le texte n'est pas prêt.</small>
        </div>

        <div class="field">
            <label for="image">Image principale</label>
            <input type="file" id="image" name="image" accept="image/*" style="width:100%;">
            @if($isEdit && $product->image)
                <img src="{{ asset('storage/'.$product->image) }}" alt="" style="width:90px; border-radius:var(--r-sm); margin-top:.6rem;">
            @endif
            @error('image')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div style="display:flex; gap:1.5rem; margin-top:.5rem;">
            <label style="display:flex; gap:.5rem; align-items:center;">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}> Produit actif
            </label>
            <label style="display:flex; gap:.5rem; align-items:center;">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}> Mettre en avant
            </label>
        </div>
    </div>

    {{-- Colonne droite : assistant SEO temps réel --}}
    <div class="panel" id="seo-panel" data-preview-url="{{ route('admin.products.seo-preview') }}" @if($isEdit) data-id="{{ $product->id }}" @endif>
        <h2>Assistant référencement (SEO)</h2>
        <p style="font-size:.85rem; color:var(--gris); margin-top:-.5rem;">Ces champs sont générés automatiquement à partir du nom du produit. Vous pouvez les ajuster.</p>

        <div style="display:flex; align-items:center; gap:1rem; margin:1rem 0;">
            <div style="flex:1;">
                <div class="seo-meter"><span id="seo-bar" style="width:0%; background:var(--bad);"></span></div>
            </div>
            <strong id="seo-score" style="font-family:var(--font-display); font-size:1.4rem;">0</strong>
            <span id="seo-level" style="font-size:.82rem; color:var(--gris);">—</span>
        </div>

        <div class="field">
            <label for="slug">URL (slug)</label>
            <input type="text" id="slug" name="slug" value="{{ old('slug', $product->slug) }}" style="width:100%;">
        </div>
        <div class="field">
            <label for="meta_title">Titre SEO <small id="mt-count" style="color:var(--gris);"></small></label>
            <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}" maxlength="60" style="width:100%;">
        </div>
        <div class="field">
            <label for="meta_description">Description SEO <small id="md-count" style="color:var(--gris);"></small></label>
            <textarea id="meta_description" name="meta_description" maxlength="320" style="width:100%; min-height:80px;">{{ old('meta_description', $product->meta_description) }}</textarea>
        </div>

        <ul id="seo-checks" style="list-style:none; padding:0; margin:1rem 0 0; display:grid; gap:.4rem; font-size:.85rem;"></ul>

        {{-- Aperçu Google --}}
        <div style="margin-top:1.2rem; padding:1rem; background:var(--ivoire); border-radius:var(--r-sm);">
            <div style="font-size:.75rem; color:var(--gris); margin-bottom:.4rem;">Aperçu Google</div>
            <div id="serp-title" style="color:#1a0dab; font-size:1rem;">Titre…</div>
            <div id="serp-url" style="color:#006621; font-size:.8rem;">blacjoyaux.com › produit › …</div>
            <div id="serp-desc" style="color:#545454; font-size:.82rem;">Description…</div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const panel = document.getElementById('seo-panel');
    const url = panel.dataset.previewUrl;
    const productId = panel.dataset.id || '';
    const csrf = '{{ csrf_token() }}';

    const get = id => document.getElementById(id);
    const fields = ['name','price','short_description','description','slug','meta_title','meta_description'];
    let timer = null;

    function colorFor(score) {
        return score >= 80 ? 'var(--vert-jade)' : (score >= 50 ? 'var(--or)' : 'var(--bad)');
    }

    async function refresh() {
        const catSelect = get('category_id');
        const category = catSelect.options[catSelect.selectedIndex]?.dataset.name || '';

        const params = new URLSearchParams({
            name: get('name').value,
            price: get('price').value || 0,
            short_description: get('short_description').value,
            description: get('description').value,
            slug: get('slug').value,
            meta_title: get('meta_title').value,
            meta_description: get('meta_description').value,
            category: category,
            id: productId,
        });

        try {
            const res = await fetch(url + '?' + params.toString(), {
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            });
            const data = await res.json();

            // Remplit les champs seulement s'ils sont vides (suggestions auto)
            if (!get('slug').value) get('slug').value = data.slug;
            if (!get('meta_title').value) get('meta_title').value = data.meta_title;
            if (!get('meta_description').value) get('meta_description').value = data.meta_description;

            // Jauge
            get('seo-bar').style.width = data.score + '%';
            get('seo-bar').style.background = colorFor(data.score);
            get('seo-score').textContent = data.score;
            get('seo-level').textContent = data.level;

            // Compteurs
            get('mt-count').textContent = '(' + get('meta_title').value.length + '/60)';
            get('md-count').textContent = '(' + get('meta_description').value.length + ' car.)';

            // Checklist
            const ul = get('seo-checks');
            ul.innerHTML = '';
            const icoOk = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-2px; margin-right:.35rem;"><path d="M20 6 9 17l-5-5"/></svg>';
            const icoWarn = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-2px; margin-right:.35rem;"><path d="M12 9v4"/><path d="M10.4 3.9 2.7 17a2 2 0 0 0 1.7 3h15.2a2 2 0 0 0 1.7-3L13.6 3.9a2 2 0 0 0-3.2 0Z"/><path d="M12 17h.01"/></svg>';
            Object.values(data.checks).forEach(c => {
                const li = document.createElement('li');
                li.innerHTML = (c.ok ? icoOk : icoWarn) + c.msg;
                li.style.color = c.ok ? 'var(--vert-jade)' : 'var(--gris)';
                ul.appendChild(li);
            });

            // Aperçu SERP
            get('serp-title').textContent = get('meta_title').value || get('name').value || 'Titre…';
            get('serp-url').textContent = 'blacjoyaux.com › produit › ' + (get('slug').value || '…');
            get('serp-desc').textContent = get('meta_description').value || 'Description…';
        } catch (e) { /* silencieux */ }
    }

    function debounced() {
        clearTimeout(timer);
        timer = setTimeout(refresh, 350);
    }

    fields.forEach(f => {
        const el = get(f);
        if (el) el.addEventListener('input', debounced);
    });
    get('category_id').addEventListener('change', debounced);

    // Premier calcul au chargement
    refresh();
})();
</script>
@endpush
