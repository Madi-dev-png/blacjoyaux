@extends('layouts.shop')

@section('title', 'Questions fréquentes — Blac Joyaux')
@section('meta_description', "Livraison, paiement, entretien de votre sac : retrouvez les réponses aux questions les plus posées sur Blac Joyaux.")

@push('structured-data')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        @foreach($faqs->flatten() as $faq)
        {
            "@type": "Question",
            "name": @json($faq->question),
            "acceptedAnswer": { "@type": "Answer", "text": @json($faq->answer) }
        }@if(!$loop->last),@endif
        @endforeach
    ]
}
</script>
@endpush

@section('content')
<section class="faq-page">
<div class="container">

    {{-- EN-TÊTE + RECHERCHE --}}
    <div class="faq-header">
        <div class="faq-header-text">
            <h1>Questions fréquentes</h1>
            <p>Trouvez rapidement des réponses à vos questions concernant nos collections, la livraison et nos services.</p>
        </div>
        <div class="faq-search-box">
            <label for="faqSearch">Vous cherchez quelque chose en particulier ?</label>
            <div class="faq-search-input">
                <input type="text" id="faqSearch" placeholder="Comment suivre ma commande ?">
                <button type="button" aria-label="Rechercher">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div class="faq-layout">

        {{-- SIDEBAR CATÉGORIES --}}
        <aside class="faq-sidebar">
            <span class="faq-sidebar-title">Catégories</span>
            <nav>
                @foreach($faqs as $category => $items)
                    <a href="#faq-{{ $category }}">{{ strtoupper($labels[$category] ?? ucfirst($category)) }}</a>
                @endforeach
            </nav>
        </aside>

        {{-- GROUPES DE QUESTIONS --}}
        <div class="faq-content" id="faqContent">
            @forelse($faqs as $category => $items)
                <div class="faq-group" id="faq-{{ $category }}">
                    <h2>{{ strtoupper($labels[$category] ?? ucfirst($category)) }}</h2>
                    @foreach($items as $faq)
                        <details class="faq-item">
                            <summary>
                                <span class="faq-q">{{ $faq->question }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="faq-chevron"><polyline points="6 9 12 15 18 9"/></svg>
                            </summary>
                            <div class="answer">{!! nl2br(e($faq->answer)) !!}</div>
                        </details>
                    @endforeach
                </div>
            @empty
                <p>La FAQ sera bientôt disponible.</p>
            @endforelse
        </div>
    </div>

    {{-- CTA WHATSAPP --}}
    <div class="faq-cta">
        <h2>Vous n'avez pas trouvé votre réponse ?</h2>
        <p>Notre équipe est à votre disposition pour vous accompagner personnellement.</p>
        <a href="https://wa.me/{{ $brandWhatsapp }}" target="_blank" rel="noopener" class="btn-faq-whatsapp">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
            </svg>
            Nous contacter sur WhatsApp
        </a>
    </div>

</div>
</section>

<script>
document.getElementById('faqSearch').addEventListener('input', function (e) {
    const term = e.target.value.trim().toLowerCase();
    document.querySelectorAll('.faq-group').forEach(function (group) {
        let visibleCount = 0;
        group.querySelectorAll('.faq-item').forEach(function (item) {
            const text = item.textContent.toLowerCase();
            const match = text.includes(term);
            item.style.display = match ? '' : 'none';
            if (match) visibleCount++;
        });
        group.style.display = visibleCount > 0 ? '' : 'none';
    });
});
</script>
@endsection