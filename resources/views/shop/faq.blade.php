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
<div class="container faq-section">
    <div class="section-head">
        <span class="eyebrow">On vous répond</span>
        <h2>Questions fréquentes</h2>
        <p style="color:var(--gris);">Vous ne trouvez pas votre réponse ? Notre assistante est en bas à droite, ou écrivez-nous sur WhatsApp.</p>
    </div>

    @forelse($faqs as $category => $items)
        <div class="faq-group">
            <h2>{{ $labels[$category] ?? ucfirst($category) }}</h2>
            @foreach($items as $faq)
                <details class="faq-item">
                    <summary>{{ $faq->question }}</summary>
                    <div class="answer">{!! nl2br(e($faq->answer)) !!}</div>
                </details>
            @endforeach
        </div>
    @empty
        <p>La FAQ sera bientôt disponible.</p>
    @endforelse

    <div style="text-align:center; margin-top:2rem;">
        <a href="https://wa.me/{{ $brandWhatsapp }}" target="_blank" rel="noopener" class="btn btn-whatsapp">Poser une question sur WhatsApp</a>
    </div>
</div>
@endsection
