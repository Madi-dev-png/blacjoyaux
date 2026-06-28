{{-- Carte produit réutilisable --}}
<article class="product-card">
    <a href="{{ route('products.show', $product) }}" class="product-thumb">
        @if($product->image)
            <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}">
        @else
            <span aria-hidden="true">◈</span>
        @endif
    </a>
    <div class="product-body">
        @if($product->category)
            <span class="product-cat">{{ $product->category->name }}</span>
        @endif
        <a href="{{ route('products.show', $product) }}" class="product-name">{{ $product->name }}</a>
        <div class="product-price">{{ $product->formatted_price }}</div>
        @if($product->in_stock)
            <a href="{{ route('products.show', $product) }}" class="btn btn-outline btn-sm">Voir le sac</a>
        @else
            <span class="badge-soldout">Épuisé — bientôt de retour</span>
        @endif
    </div>
</article>
