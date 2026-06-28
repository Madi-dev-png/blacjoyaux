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
<div class="container">
    <div class="product-detail">
        <div class="product-gallery">
            @if($product->image)
                <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}">
            @else
                <span aria-hidden="true">◈</span>
            @endif
        </div>

        <div class="product-info">
            @if($product->category)
                <span class="product-cat">{{ $product->category->name }}</span>
            @endif
            <h1>{{ $product->name }}</h1>
            <div class="price">{{ $product->formatted_price }}</div>

            @if($product->short_description)
                <p>{{ $product->short_description }}</p>
            @endif

            <div class="product-meta">
                @if($product->color)<span><strong>Coloris :</strong> {{ $product->color }}</span>@endif
                @if($product->material)<span><strong>Matière :</strong> {{ $product->material }}</span>@endif
                <span><strong>Disponibilité :</strong>
                    {{ $product->in_stock ? 'En stock ('.$product->stock.')' : 'Épuisé' }}
                </span>
            </div>

            @if($product->in_stock)
                <form method="POST" action="{{ route('cart.add', $product) }}">
                    @csrf
                    <div class="qty-row">
                        <label for="quantity">Quantité</label>
                        <input class="qty-input" type="number" id="quantity" name="quantity" value="1" min="1" max="{{ min(20, $product->stock) }}">
                    </div>
                    <div style="display:flex; gap:1rem; flex-wrap:wrap;">
                        <button type="submit" class="btn btn-primary">Ajouter au panier</button>
                        <a href="https://wa.me/{{ $brandWhatsapp }}?text={{ urlencode('Bonjour, je suis intéressée par le sac « '.$product->name.' »') }}"
                           target="_blank" rel="noopener" class="btn btn-whatsapp">Commander sur WhatsApp</a>
                    </div>
                </form>
            @else
                <a href="https://wa.me/{{ $brandWhatsapp }}?text={{ urlencode('Bonjour, le sac « '.$product->name.' » est épuisé. Quand sera-t-il de nouveau disponible ?') }}"
                   target="_blank" rel="noopener" class="btn btn-whatsapp">Me prévenir du retour</a>
            @endif

            @if($product->description)
                <div class="product-desc">
                    <h3>Description</h3>
                    {!! nl2br(e($product->description)) !!}
                </div>
            @endif
        </div>
    </div>

    @if($related->isNotEmpty())
    <section class="section">
        <div class="section-head"><span class="eyebrow">Vous aimerez aussi</span><h2>Dans le même esprit</h2></div>
        <div class="product-grid">
            @foreach($related as $item)
                @include('shop.partials.product-card', ['product' => $item])
            @endforeach
        </div>
    </section>
    @endif
</div>
@endsection
