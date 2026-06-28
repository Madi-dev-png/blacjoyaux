<?php

namespace App\Services;

use App\Models\Faq;
use App\Models\Product;
use Illuminate\Support\Facades\Http;

/**
 * Assistant conversationnel Blac Joyaux.
 * Appelle l'API Anthropic (Claude) avec un contexte métier riche :
 * identité de marque, produits en catalogue et FAQ.
 * Disponible sur toutes les pages via le widget de chat.
 */
class ChatService
{
    public function reply(string $userMessage, array $history = []): array
    {
        $apiKey = config('services.anthropic.key');

        // Mode dégradé si la clé n'est pas configurée :
        // on répond depuis la FAQ pour que la démo fonctionne sans clé.
        if (empty($apiKey)) {
            return [
                'reply' => $this->fallbackFromFaq($userMessage),
                'source' => 'faq',
            ];
        }

        $payload = [
            'model' => config('services.anthropic.model', 'claude-sonnet-4-6'),
            'max_tokens' => 600,
            'system' => $this->systemPrompt(),
            'messages' => $this->buildMessages($userMessage, $history),
        ];

        try {
            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])->timeout(30)->post('https://api.anthropic.com/v1/messages', $payload);

            if ($response->failed()) {
                return ['reply' => $this->fallbackFromFaq($userMessage), 'source' => 'faq'];
            }

            $text = collect($response->json('content', []))
                ->where('type', 'text')
                ->pluck('text')
                ->implode("\n");

            return [
                'reply' => $text !== '' ? $text : $this->fallbackFromFaq($userMessage),
                'source' => 'ia',
            ];
        } catch (\Throwable $e) {
            return ['reply' => $this->fallbackFromFaq($userMessage), 'source' => 'faq'];
        }
    }

    /** Construit le prompt système avec le contexte de marque + catalogue + FAQ. */
    protected function systemPrompt(): string
    {
        $products = Product::active()
            ->get(['name', 'price', 'color', 'material', 'short_description', 'stock'])
            ->map(function ($p) {
                $prix = number_format($p->price, 0, ',', ' ').' F CFA';
                $dispo = $p->stock > 0 ? 'en stock' : 'épuisé';
                return "- {$p->name} ({$prix}, {$dispo})"
                    . ($p->color ? ", coloris {$p->color}" : '')
                    . ($p->material ? ", matière {$p->material}" : '')
                    . ($p->short_description ? " : {$p->short_description}" : '');
            })->implode("\n");

        $faqs = Faq::active()->orderBy('sort_order')
            ->get(['question', 'answer'])
            ->map(fn ($f) => "Q : {$f->question}\nR : {$f->answer}")
            ->implode("\n\n");

        $whatsapp = config('services.brand.whatsapp');

        return <<<PROMPT
Tu es l'assistante virtuelle de Blac Joyaux, une marque ivoirienne de maroquinerie féminine (sacs à main) basée à Abidjan, Cocody Palmeraie. Tu réponds en français, avec un ton chaleureux, élégant et professionnel.

IDENTITÉ DE LA MARQUE :
- Blac Joyaux est une marque de sacs à main pour femmes, fondée par Manuela Kouadio.
- Son ADN repose sur l'héritage culturel Ashanti, symbolisé par la poupée "Joyaux de Bla" (poupée Akua'ba, symbole de fécondité et de beauté).
- Positionnement : maroquinerie culturellement distinctive, joyeuse et accessible.
- Valeurs : héritage, féminité, qualité, fierté africaine.

INFORMATIONS PRATIQUES :
- Livraison à Abidjan en 3 à 5 jours.
- Paiement : à la livraison, ou via Wave et Orange Money.
- Canal privilégié pour finaliser une commande ou poser une question urgente : WhatsApp ({$whatsapp}).
- On peut commander directement sur le site sans créer de compte.

CATALOGUE ACTUEL :
{$products}

QUESTIONS FRÉQUENTES :
{$faqs}

RÈGLES :
- Réponds de façon concise (3-5 phrases maximum sauf si on te demande plus de détails).
- Si on te demande un produit précis, base-toi sur le catalogue ci-dessus. N'invente jamais de prix ni de produit qui n'existe pas.
- Si tu ne connais pas la réponse ou si la cliente veut finaliser un achat, invite-la poliment à contacter la marque sur WhatsApp.
- Si on te pose une question hors-sujet (sans rapport avec Blac Joyaux ou la maroquinerie), ramène gentiment la conversation vers la marque.
PROMPT;
    }

    protected function buildMessages(string $userMessage, array $history): array
    {
        $messages = [];

        // On garde au maximum les 8 derniers échanges pour le contexte.
        foreach (array_slice($history, -8) as $turn) {
            if (! isset($turn['role'], $turn['content'])) {
                continue;
            }
            if (! in_array($turn['role'], ['user', 'assistant'], true)) {
                continue;
            }
            $messages[] = [
                'role' => $turn['role'],
                'content' => (string) $turn['content'],
            ];
        }

        $messages[] = ['role' => 'user', 'content' => $userMessage];

        return $messages;
    }

    /** Réponse de secours basée sur la FAQ (recherche par mots-clés). */
    protected function fallbackFromFaq(string $message): string
    {
        $message = mb_strtolower($message);
        $faqs = Faq::active()->get();

        $best = null;
        $bestScore = 0;

        foreach ($faqs as $faq) {
            $score = 0;
            $words = preg_split('/\s+/', mb_strtolower($faq->question));
            foreach ($words as $word) {
                if (mb_strlen($word) >= 4 && str_contains($message, $word)) {
                    $score++;
                }
            }
            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $faq;
            }
        }

        if ($best && $bestScore > 0) {
            return $best->answer;
        }

        $whatsapp = config('services.brand.whatsapp');
        return "Merci pour votre message ! Pour une réponse personnalisée, n'hésitez pas à nous écrire sur WhatsApp au {$whatsapp}. Notre équipe Blac Joyaux se fera un plaisir de vous accompagner.";
    }
}
