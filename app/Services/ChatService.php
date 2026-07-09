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

        if (empty($apiKey)) {
            return [
                'reply'  => $this->fallbackFromFaq($userMessage),
                'source' => 'faq',
            ];
        }

        $payload = [
            'model'      => config('services.anthropic.model', 'claude-sonnet-4-6'),
            'max_tokens' => 600,
            // L'API Anthropic prend le prompt système à part (pas dans messages[]).
            'system'     => $this->systemPrompt(),
            'messages'   => $this->buildMessages($userMessage, $history),
        ];

        try {
            $response = Http::withHeaders([
                'x-api-key'         => $apiKey,
                'anthropic-version' => '2023-06-01',
                'Content-Type'      => 'application/json',
            ])->timeout(30)->post('https://api.anthropic.com/v1/messages', $payload);

            if ($response->failed()) {
                return ['reply' => $this->fallbackFromFaq($userMessage), 'source' => 'faq'];
            }

            // La réponse est une liste de blocs ; on concatène les blocs texte.
            $text = collect($response->json('content', []))
                ->where('type', 'text')
                ->pluck('text')
                ->implode("\n");

            return [
                'reply'  => $text !== '' ? $text : $this->fallbackFromFaq($userMessage),
                'source' => 'ia',
            ];

        } catch (\Throwable) {
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
- Son ADN repose sur l'héritage culturel Ashanti, symbolisé par la poupée "Joyau de Bla" (poupée Akua'ba, symbole de fécondité et de beauté).
- Positionnement : maroquinerie culturellement distinctive, joyeuse et accessible.
- Valeurs : héritage, féminité, qualité, fierté africaine.

INFORMATIONS PRATIQUES :
- Livraison à Abidjan en 1 à 3 jours.
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
        // Contraintes de l'API Anthropic : le premier message doit être 'user'
        // et les rôles doivent alterner — on assainit donc l'historique reçu
        // du client (qui commence souvent par le message d'accueil de l'assistant).
        foreach (array_slice($history, -8) as $turn) {
            if (! isset($turn['role'], $turn['content'])) {
                continue;
            }
            if (! in_array($turn['role'], ['user', 'assistant'], true)) {
                continue;
            }

            $content = trim((string) $turn['content']);
            if ($content === '') {
                continue;
            }

            // Pas de message assistant en tête de conversation.
            if (empty($messages) && $turn['role'] === 'assistant') {
                continue;
            }

            // Deux tours consécutifs du même rôle : on fusionne.
            $last = array_key_last($messages);
            if ($last !== null && $messages[$last]['role'] === $turn['role']) {
                $messages[$last]['content'] .= "\n".$content;
                continue;
            }

            $messages[] = ['role' => $turn['role'], 'content' => $content];
        }

        // Le message courant est toujours un tour 'user' ; s'il suit déjà un
        // tour 'user' orphelin, on fusionne pour respecter l'alternance.
        $last = array_key_last($messages);
        if ($last !== null && $messages[$last]['role'] === 'user') {
            $messages[$last]['content'] .= "\n".$userMessage;
        } else {
            $messages[] = ['role' => 'user', 'content' => $userMessage];
        }

        return $messages;
    }

    /**
     * Réponse de secours quand l'API IA n'est pas configurée ou indisponible.
     * C'est le chemin le plus emprunté tant qu'aucune clé ANTHROPIC_API_KEY n'est
     * renseignée dans .env — d'où l'importance qu'il soit réellement utile
     * (salutations, prix/stock produit en direct, FAQ) plutôt qu'un simple
     * comptage de mots communs.
     */
    protected function fallbackFromFaq(string $message): string
    {
        $normalized = $this->normalize($message);
        $whatsapp = config('services.brand.whatsapp');

        if (preg_match('/\b(bonjour|bonsoir|salut|coucou|hello|hi|cc)\b/u', $normalized)) {
            return "Bonjour et bienvenue chez Blac Joyaux ! 👋 Je peux vous renseigner sur nos sacs, les délais de livraison, le paiement ou notre histoire Ashanti. Que souhaitez-vous savoir ?";
        }

        if (preg_match('/\b(merci|thanks|thank you)\b/u', $normalized)) {
            return "Avec plaisir ! N'hésitez pas si vous avez d'autres questions, ou écrivez-nous directement sur WhatsApp pour finaliser votre commande.";
        }

        // Question sur un produit précis (prix, dispo, couleur...) : on répond
        // avec les vraies données du catalogue plutôt que de renvoyer vers la FAQ.
        if (preg_match('/\b(prix|coute|combien|dispo|disponible|stock|couleur|taille|dimension|matiere)\b/u', $normalized)) {
            $product = $this->findProductInMessage($normalized);
            if ($product) {
                return $this->describeProduct($product);
            }
        }

        $best = $this->bestFaqMatch($normalized);
        if ($best) {
            return $best->answer;
        }

        return "Je n'ai pas une réponse assez précise sous la main pour cette question, mais notre équipe se fera un plaisir de vous aider directement sur WhatsApp : https://wa.me/{$whatsapp}. Vous pouvez aussi me demander nos best-sellers, les délais de livraison ou les moyens de paiement.";
    }

    /** Minuscules + accents retirés, pour une comparaison fiable quel que soit l'orthographe. */
    protected function normalize(string $text): string
    {
        $text = mb_strtolower(trim($text));
        $map = [
            'à' => 'a', 'â' => 'a', 'ä' => 'a',
            'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
            'î' => 'i', 'ï' => 'i',
            'ô' => 'o', 'ö' => 'o',
            'ù' => 'u', 'û' => 'u', 'ü' => 'u',
            'ç' => 'c', 'œ' => 'oe',
        ];

        return strtr($text, $map);
    }

    /** Mots vides ignorés dans les scores de correspondance (trop fréquents pour être discriminants). */
    protected function stopWords(): array
    {
        return ['sac', 'sacs', 'avec', 'pour', 'dans', 'quel', 'quelle', 'quels', 'quelles', 'votre', 'vous', 'nous', 'les', 'des', 'une', 'est', 'ce'];
    }

    /** Cherche, parmi le catalogue actif, le produit le plus probablement évoqué dans le message. */
    protected function findProductInMessage(string $normalizedMessage): ?Product
    {
        $stop = $this->stopWords();
        $best = null;
        $bestScore = 0;

        foreach (Product::active()->get() as $product) {
            $words = preg_split('/[\s\-–]+/u', $this->normalize($product->name));
            $score = 0;

            foreach ($words as $word) {
                if (mb_strlen($word) >= 3 && ! in_array($word, $stop, true) && str_contains($normalizedMessage, $word)) {
                    $score++;
                }
            }

            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $product;
            }
        }

        // Au moins 2 mots significatifs du nom retrouvés dans le message, pour éviter
        // qu'un mot isolé (ex: juste la couleur) ne déclenche un mauvais produit.
        return $bestScore >= 2 ? $best : null;
    }

    /** Fiche produit condensée en réponse de chat (prix, stock, couleur, matière). */
    protected function describeProduct(Product $product): string
    {
        $prix = number_format($product->price, 0, ',', ' ').' F CFA';
        $dispo = $product->stock > 0 ? "en stock ({$product->stock} disponible" . ($product->stock > 1 ? 's' : '') . ')' : 'malheureusement épuisé pour le moment';

        $details = [];
        if ($product->color) $details[] = "coloris {$product->color}";
        if ($product->material) $details[] = "matière : {$product->material}";
        if ($product->dimensions) $details[] = "dimensions : {$product->dimensions}";

        $detailsText = $details ? ' (' . implode(', ', $details) . ')' : '';

        return "Le « {$product->name} »{$detailsText} est à {$prix}, {$dispo}. Voulez-vous que je vous aide à finaliser une commande ?";
    }

    /** Meilleure FAQ correspondant au message (question + réponse), avec seuil minimum. */
    protected function bestFaqMatch(string $normalizedMessage): ?Faq
    {
        $stop = $this->stopWords();
        $best = null;
        $bestScore = 0;

        foreach (Faq::active()->get() as $faq) {
            $questionWords = array_filter(
                preg_split('/\s+/', $this->normalize($faq->question)),
                fn ($w) => mb_strlen($w) >= 4 && ! in_array($w, $stop, true)
            );

            if (empty($questionWords)) {
                continue;
            }

            $matches = 0;
            foreach ($questionWords as $word) {
                if (str_contains($normalizedMessage, $word)) {
                    $matches++;
                }
            }

            // Score = proportion des mots-clés de la question retrouvés dans le message.
            $ratio = $matches / count($questionWords);

            if ($matches >= 2 && $ratio > $bestScore) {
                $bestScore = $ratio;
                $best = $faq;
            }
        }

        // Au moins un tiers des mots-clés significatifs de la question doivent matcher.
        return $bestScore >= 0.34 ? $best : null;
    }
}
