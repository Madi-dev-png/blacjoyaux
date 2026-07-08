@extends('layouts.shop')

@section('title', $product->meta_title ?: $product->name.' — Blac Joyaux')
@section('meta_description', $product->meta_description ?: $product->short_description)

@push('structured-data')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": @json($product->name),
    "description": @json($product->meta_description ?: $product->short_description),
    "brand": { "@type": "Brand", "name": "Blac Joyaux" },
    @if($product->image)"image": "{{ asset('storage/'.$product->image) }}",@endif
    "offers": {
        "@type": "Offer",
        "price": "{{ $product->price }}",
        "priceCurrency": "XOF",
        "availability": "{{ $product->in_stock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
        "url": "{{ route('products.show', $product) }}"
    }
}
</script>
@endpush

@section('content')
<section class="pdp-page">
<div class="container">

    {{-- FIL D'ARIANE --}}
    <nav class="pdp-breadcrumb" aria-label="Fil d'ariane">
        <a href="{{ route('home') }}">Accueil</a> <span>›</span>
        <a href="{{ route('products.index') }}">Boutique</a>
        @if($collectionLabel)
            <span>›</span> <a href="{{ route('products.index', ['collection' => $product->collection]) }}">{{ $collectionLabel }}</a>
        @endif
        <span>›</span> <strong id="pdpBreadcrumbName">{{ strtoupper($product->name) }}</strong>
    </nav>

    <div class="pdp-layout">

        {{-- GALERIE --}}
        <div class="pdp-gallery">
            @php
                $images = array_filter(array_merge([$product->image], $product->gallery ?? []));
            @endphp
            <div class="pdp-main-image" id="pdpMainImage" style="aspect-ratio: {{ $product->image_ratio }};">
                @if(count($images))
                    <img src="{{ asset('storage/'.$images[array_key_first($images)]) }}" alt="{{ $product->name }}" id="pdpMainImg">
                @else
                    <span class="placeholder-ico" id="pdpMainPlaceholder" aria-hidden="true">◈</span>
                @endif
            </div>
            @if(count($images) > 1)
                <div class="pdp-thumbs" id="pdpThumbs">
                    @foreach($images as $i => $img)
                        <button type="button" class="pdp-thumb {{ $i === 0 ? 'is-active' : '' }}" onclick="pdpSwapImage('{{ asset('storage/'.$img) }}', this)">
                            <img src="{{ asset('storage/'.$img) }}" alt="{{ $product->name }} vue {{ $i + 1 }}">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- INFOS --}}
        <div class="pdp-info">
            @if($collectionLabel)
                <span class="pdp-pill">{{ strtoupper($collectionLabel) }}</span>
            @endif

            <h1 id="pdpName">{{ $product->name }}</h1>

            <div class="pdp-price" id="pdpPrice">{{ $product->formatted_price }}</div>

            @if($colorSiblings->count() > 1)
                <div class="pdp-colors">
                    <span class="pdp-colors-label">Couleur : <strong id="pdpColorName">{{ $product->color ?? '' }}</strong></span>
                    <div class="pdp-swatches" id="pdpSwatches">
                        @foreach($colorSiblings as $sibling)
                            <a href="{{ route('products.show', $sibling) }}"
                               class="pdp-swatch {{ $sibling->id === $product->id ? 'is-active' : '' }}"
                               title="{{ $sibling->color ?? $sibling->name }}"
                               style="background: {{ $sibling->color_hex }};"
                               data-url="{{ route('products.show', $sibling) }}"
                               data-image="{{ $sibling->image ? asset('storage/'.$sibling->image) : '' }}"
                               data-ratio="{{ $sibling->image_ratio }}"
                               data-name="{{ $sibling->name }}"
                               data-color="{{ $sibling->color ?? $sibling->name }}"
                               data-price="{{ $sibling->formatted_price }}"
                               data-in-stock="{{ $sibling->in_stock ? '1' : '0' }}"
                               data-add-url="{{ route('cart.add', $sibling) }}"
                               onclick="return pdpSelectColor(event, this)">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($product->short_description)
                <p class="pdp-short-desc">{{ $product->short_description }}</p>
            @endif

            <div id="pdpActions">
            @if($product->in_stock)
                <form method="POST" action="{{ route('cart.add', $product) }}" class="pdp-actions" id="pdpCartForm">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn-pdp-cart">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-2px; margin-right:.4rem;">
                            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/>
                            <path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                        </svg>
                        Ajouter au panier
                    </button>
                </form>
            @else
                <div class="pdp-actions"><span class="btn-pdp-cart" style="opacity:.5; pointer-events:none;">Épuisé</span></div>
            @endif
            </div>

            @if($product->story)
                <div class="pdp-story">
                    <h3>L'histoire cachée derrière ce sac…</h3>
                    {!! nl2br(e($product->story)) !!}
                </div>
            @endif

            {{-- DÉTAILS PRODUIT --}}
            @if($product->dimensions || $product->material || $product->closure || $product->lining)
            <details class="pdp-accordion" open>
                <summary>Détails produit <span class="acc-ico">+</span></summary>
                <div class="pdp-accordion-body">
                    @if($product->dimensions)<p><strong>Dimensions :</strong> {{ $product->dimensions }}</p>@endif
                    @if($product->material)<p><strong>Matière :</strong> {{ $product->material }}</p>@endif
                    @if($product->closure)<p><strong>Fermeture :</strong> {{ $product->closure }}</p>@endif
                    @if($product->lining)<p><strong>Doublure :</strong> {{ $product->lining }}</p>@endif
                </div>
            </details>
            @endif

            {{-- LIVRAISON --}}
            <details class="pdp-accordion" open>
                <summary>Livraison <span class="acc-ico">+</span></summary>
                <div class="pdp-accordion-body">
                    <p><strong>Abidjan :</strong> 1 à 3 jours ouvrés</p>
                    <p><strong>Côte d'Ivoire :</strong> 2 à 4 jours ouvrés</p>
                    <p><strong>Afrique de l'Ouest :</strong> 5 à 8 jours ouvrés</p>
                </div>
            </details>

            {{-- ENTRETIEN --}}
            <details class="pdp-accordion">
                <summary>Entretien <span class="acc-ico">+</span></summary>
                <div class="pdp-accordion-body">
                    <p>Nettoyer avec un chiffon légèrement humide.</p>
                    <p>Appliquer une crème nourrissante cuir tous les 3 mois.</p>
                    <p>Éviter l'exposition prolongée au soleil.</p>
                </div>
            </details>

            @if($product->description)
            <details class="pdp-accordion">
                <summary>Description <span class="acc-ico">+</span></summary>
                <div class="pdp-accordion-body">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </details>
            @endif
        </div>
    </div>

    {{-- PRODUITS SIMILAIRES --}}
    @if($related->isNotEmpty())
    <section class="pdp-related">
        <div class="pdp-related-head">
            <span class="nh-eyebrow">À découvrir</span>
            <h2>Vous aimerez aussi</h2>
        </div>
        <div class="pdp-related-grid">
            @foreach($related as $item)
                <a href="{{ route('products.show', $item) }}" class="pdp-related-card">
                    <div class="pdp-related-thumb">
                        @if($item->image)
                            <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->name }}">
                        @else
                            <span class="placeholder-ico">◈</span>
                        @endif
                    </div>
                    <div class="pdp-related-name">{{ strtoupper($item->name) }}</div>
                    <div class="pdp-related-price">{{ $item->formatted_price }}</div>
                    <span class="pdp-related-btn">Voir le produit</span>
                </a>
            @endforeach
        </div>
    </section>
  @endif
</div>
</section>

<script>
function pdpSwapImage(src, btn) {
    document.getElementById('pdpMainImg').src = src;
    document.querySelectorAll('.pdp-thumb').forEach(t => t.classList.remove('is-active'));
    btn.classList.add('is-active');
}

/**
 * Changement de couleur sur la fiche produit : met à jour l'image, le nom, le prix
 * et les actions (panier / WhatsApp) INSTANTANÉMENT, sans recharger la page.
 * L'URL est aussi mise à jour (history.pushState) pour que le lien reste partageable
 * et que le rechargement de la page affiche bien la bonne variante.
 */
function pdpSelectColor(event, swatch) {
    event.preventDefault();

    // Couleur déjà sélectionnée : rien à faire.
    if (swatch.classList.contains('is-active')) {
        return false;
    }

    const image = swatch.dataset.image;
    const mainImg = document.getElementById('pdpMainImg');
    const placeholder = document.getElementById('pdpMainPlaceholder');
    const mainHolder = document.getElementById('pdpMainImage');
    if (mainHolder && swatch.dataset.ratio) mainHolder.style.aspectRatio = swatch.dataset.ratio;

    if (image) {
        if (!mainImg) {
            // Le produit précédent n'avait pas d'image : on (re)crée la balise <img>.
            const holder = document.getElementById('pdpMainImage');
            if (placeholder) placeholder.remove();
            const img = document.createElement('img');
            img.id = 'pdpMainImg';
            img.src = image;
            img.alt = swatch.dataset.name || '';
            holder.appendChild(img);
        } else {
            mainImg.src = image;
            mainImg.alt = swatch.dataset.name || '';
        }
    } else if (mainImg) {
        // La variante choisie n'a pas encore de photo côté admin.
        mainImg.remove();
        const holder = document.getElementById('pdpMainImage');
        const span = document.createElement('span');
        span.id = 'pdpMainPlaceholder';
        span.className = 'placeholder-ico';
        span.setAttribute('aria-hidden', 'true');
        span.textContent = '◈';
        holder.appendChild(span);
    }

    // Nom, prix, fil d'ariane, libellé couleur
    const nameEl = document.getElementById('pdpName');
    if (nameEl) nameEl.textContent = swatch.dataset.name;
    const breadcrumbEl = document.getElementById('pdpBreadcrumbName');
    if (breadcrumbEl) breadcrumbEl.textContent = (swatch.dataset.name || '').toUpperCase();
    const priceEl = document.getElementById('pdpPrice');
    if (priceEl) priceEl.textContent = swatch.dataset.price;
    const colorEl = document.getElementById('pdpColorName');
    if (colorEl) colorEl.textContent = swatch.dataset.color;

    // Formulaire "Ajouter au panier" -> pointe vers le bon produit / bonne couleur.
    const cartForm = document.getElementById('pdpCartForm');
    if (cartForm) cartForm.action = swatch.dataset.addUrl;

    // Disponibilité (stock)
    const actions = document.getElementById('pdpActions');
    if (actions) {
        if (swatch.dataset.inStock === '1') {
            actions.style.opacity = '';
        } else {
            actions.style.opacity = '.5';
            actions.style.pointerEvents = 'none';
        }
    }

    // État visuel des pastilles
    document.querySelectorAll('#pdpSwatches .pdp-swatch').forEach(s => s.classList.remove('is-active'));
    swatch.classList.add('is-active');

    // Met à jour l'URL affichée sans recharger la page.
    if (swatch.dataset.url && window.history && window.history.pushState) {
        window.history.pushState({}, '', swatch.dataset.url);
        document.title = swatch.dataset.name + ' — Blac Joyaux';
    }

    return false;
}
</script>
@endsection