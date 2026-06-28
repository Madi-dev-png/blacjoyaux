<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Str;

/**
 * Génère automatiquement les métadonnées SEO d'un produit
 * (slug, meta_title, meta_description) et calcule un score de qualité 0-100.
 *
 * C'est le coeur de la fonctionnalité "formulaire boosté SEO" du back-office :
 * l'administrateur saisit le produit, et ces champs sont proposés automatiquement
 * avec un indicateur de qualité en temps réel.
 */
class SeoService
{
    /** Génère un slug unique à partir du nom du produit. */
    public function generateSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 2;

        while (
            Product::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    /** Propose un meta_title optimisé (50-60 caractères idéalement). */
    public function generateMetaTitle(string $name, ?string $category = null): string
    {
        $brand = 'Blac Joyaux';
        $title = $category
            ? "{$name} — {$category} | {$brand}"
            : "{$name} | {$brand}";

        // On vise <= 60 caractères pour ne pas être tronqué par Google.
        if (Str::length($title) > 60) {
            $title = Str::limit($name.' | '.$brand, 57, '...');
        }

        return $title;
    }

    /** Propose une meta_description (120-160 caractères idéalement). */
    public function generateMetaDescription(string $name, ?string $shortDescription = null, ?int $price = null): string
    {
        $base = $shortDescription
            ? trim($shortDescription)
            : "Découvrez {$name}, une pièce de maroquinerie féminine signée Blac Joyaux, inspirée de l'héritage Ashanti.";

        if ($price) {
            $base .= ' À partir de '.number_format($price, 0, ',', ' ').' F CFA. Livraison à Abidjan.';
        }

        return Str::limit($base, 157, '...');
    }

    /**
     * Calcule un score de qualité SEO sur 100.
     * Sert d'indicateur visuel (rouge / orange / vert) dans le formulaire admin.
     */
    public function score(array $data): array
    {
        $checks = [];
        $score = 0;

        // 1. Longueur du meta_title (idéal 30-60)
        $titleLen = Str::length($data['meta_title'] ?? '');
        if ($titleLen >= 30 && $titleLen <= 60) {
            $score += 25;
            $checks['title'] = ['ok' => true, 'msg' => "Titre SEO de bonne longueur ({$titleLen} car.)."];
        } else {
            $checks['title'] = ['ok' => false, 'msg' => "Titre SEO à ajuster ({$titleLen} car., visez 30-60)."];
        }

        // 2. Longueur de la meta_description (idéal 120-160)
        $descLen = Str::length($data['meta_description'] ?? '');
        if ($descLen >= 120 && $descLen <= 160) {
            $score += 25;
            $checks['description'] = ['ok' => true, 'msg' => "Description SEO de bonne longueur ({$descLen} car.)."];
        } else {
            $checks['description'] = ['ok' => false, 'msg' => "Description à ajuster ({$descLen} car., visez 120-160)."];
        }

        // 3. Présence d'un slug propre
        $slug = $data['slug'] ?? '';
        if ($slug !== '' && $slug === Str::slug($slug)) {
            $score += 20;
            $checks['slug'] = ['ok' => true, 'msg' => 'URL (slug) propre et lisible.'];
        } else {
            $checks['slug'] = ['ok' => false, 'msg' => 'Slug manquant ou mal formé.'];
        }

        // 4. Le nom du produit apparaît dans le meta_title
        $name = Str::lower($data['name'] ?? '');
        if ($name !== '' && Str::contains(Str::lower($data['meta_title'] ?? ''), $name)) {
            $score += 15;
            $checks['keyword'] = ['ok' => true, 'msg' => 'Le nom du produit est présent dans le titre.'];
        } else {
            $checks['keyword'] = ['ok' => false, 'msg' => 'Ajoutez le nom du produit dans le titre SEO.'];
        }

        // 5. Description longue présente (contenu = bon pour le SEO)
        if (Str::length(strip_tags($data['description'] ?? '')) >= 120) {
            $score += 15;
            $checks['content'] = ['ok' => true, 'msg' => 'Description produit suffisamment détaillée.'];
        } else {
            $checks['content'] = ['ok' => false, 'msg' => 'Étoffez la description produit (min. 120 car.).'];
        }

        $level = $score >= 80 ? 'bon' : ($score >= 50 ? 'moyen' : 'faible');

        return [
            'score' => $score,
            'level' => $level,
            'checks' => $checks,
        ];
    }
}
