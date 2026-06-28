<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Faq;
use App\Models\Product;
use App\Models\User;
use App\Services\SeoService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---- Compte administrateur ----
        User::updateOrCreate(
            ['email' => 'admin@blacjoyaux.com'],
            [
                'name' => 'Manuela Kouadio',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );

        // ---- Catégories ----
        $categories = collect([
            ['name' => 'Sacs à main',  'description' => 'Nos sacs à main structurés, signature Blac Joyaux.'],
            ['name' => 'Pochettes',    'description' => 'Pochettes élégantes pour vos soirées.'],
            ['name' => 'Cabas',        'description' => 'Grands sacs pour le quotidien.'],
            ['name' => 'Bandoulières', 'description' => 'Sacs bandoulière pratiques et chics.'],
        ])->map(fn ($c) => Category::create([
            'name' => $c['name'],
            'slug' => Str::slug($c['name']),
            'description' => $c['description'],
        ]));

        $seo = app(SeoService::class);

        // ---- Produits de démonstration ----
        $products = [
            ['Sac Akua\'ba',        'Sacs à main',  72000, 'Sac à main structuré inspiré de la poupée Ashanti.', 'Pièce phare de la maison, le sac Akua\'ba rend hommage à la poupée de fécondité Ashanti. Cuir grainé, fermoir doré, anse rigide.', 'Or', 'Cuir grainé', true, 8],
            ['Pochette Kente',      'Pochettes',    38000, 'Pochette de soirée aux motifs inspirés du tissu Kente.', 'Une pochette raffinée qui capte la lumière. Parfaite pour vos sorties et cérémonies.', 'Multicolore', 'Cuir verni', true, 12],
            ['Cabas Adinkra',       'Cabas',        65000, 'Grand cabas du quotidien, symboles Adinkra discrets.', 'Spacieux et élégant, le cabas Adinkra vous accompagne partout. Doublure intérieure, poche zippée.', 'Terre cuite', 'Cuir souple', true, 6],
            ['Bandoulière Bla',     'Bandoulières', 45000, 'Sac bandoulière compact et féminin.', 'Le compagnon idéal des journées chargées. Bandoulière ajustable, format compact.', 'Aubergine', 'Cuir lisse', true, 10],
            ['Sac Joyaux Royal',    'Sacs à main',  89000, 'Notre sac le plus prestigieux, finitions dorées.', 'L\'excellence Blac Joyaux. Un sac d\'exception aux finitions soignées, pour les grandes occasions.', 'Noir & or', 'Cuir premium', true, 4],
            ['Pochette Soirée Or',  'Pochettes',    42000, 'Pochette dorée pour vos événements.', 'Brillez en soirée avec cette pochette dorée à la chaîne amovible.', 'Or', 'Satin & cuir', true, 7],
            ['Cabas Abidjan',       'Cabas',        58000, 'Cabas léger et résistant pour la ville.', 'Pensé pour le rythme d\'Abidjan : léger, résistant, élégant.', 'Camel', 'Toile enduite', true, 9],
            ['Sac Mini Bla',        'Sacs à main',  52000, 'Version mini de notre best-seller.', 'Tout le charme du sac Bla dans un format mini, ultra tendance.', 'Rose poudré', 'Cuir grainé', false, 5],
        ];

        foreach ($products as $p) {
            [$name, $catName, $price, $short, $desc, $color, $material, $featured, $stock] = $p;
            $category = $categories->firstWhere('name', $catName);

            $slug = $seo->generateSlug($name);
            $metaTitle = $seo->generateMetaTitle($name, $catName);
            $metaDesc = $seo->generateMetaDescription($name, $short, $price);
            $score = $seo->score([
                'name' => $name, 'slug' => $slug,
                'meta_title' => $metaTitle, 'meta_description' => $metaDesc,
                'description' => $desc,
            ]);

            Product::create([
                'category_id' => $category?->id,
                'name' => $name,
                'slug' => $slug,
                'short_description' => $short,
                'description' => $desc,
                'price' => $price,
                'stock' => $stock,
                'color' => $color,
                'material' => $material,
                'is_active' => true,
                'is_featured' => $featured,
                'meta_title' => $metaTitle,
                'meta_description' => $metaDesc,
                'seo_score' => $score['score'],
            ]);
        }

        // ---- FAQ de démonstration ----
        $faqs = [
            ['Quels sont les délais de livraison ?', "Nous livrons à Abidjan en 3 à 5 jours ouvrés. Pour l'intérieur du pays, comptez 5 à 7 jours.", 'livraison', 1],
            ['Combien coûte la livraison ?', "La livraison à Abidjan est à 1 500 F CFA, l'intérieur du pays à 3 000 F CFA. Le retrait en boutique à Cocody Palmeraie est gratuit.", 'livraison', 2],
            ['Quels moyens de paiement acceptez-vous ?', "Vous pouvez payer à la livraison, ou via Wave et Orange Money. Le paiement se confirme avec notre équipe sur WhatsApp.", 'paiement', 1],
            ['Dois-je créer un compte pour commander ?', "Non, vous pouvez commander directement en renseignant vos coordonnées au moment de la commande.", 'general', 1],
            ['Vos sacs sont-ils en cuir véritable ?', "La plupart de nos modèles sont en cuir grainé, lisse ou premium. La matière est précisée sur chaque fiche produit.", 'produit', 1],
            ['Comment entretenir mon sac Blac Joyaux ?', "Évitez l'humidité prolongée, nettoyez avec un chiffon doux et rangez votre sac dans sa housse. Un entretien régulier prolonge sa beauté.", 'produit', 2],
            ['Puis-je échanger ou retourner un article ?', "Oui, vous disposez de 7 jours après réception pour un échange, sous réserve que le sac soit neuf et non utilisé. Contactez-nous sur WhatsApp.", 'general', 2],
            ['Quelle est l\'histoire de Blac Joyaux ?', "Blac Joyaux, fondée par Manuela Kouadio, s'inspire de la poupée Ashanti Joyaux de Bla (Akua'ba), symbole de beauté et de transmission culturelle.", 'general', 3],
        ];

        foreach ($faqs as $f) {
            Faq::create([
                'question' => $f[0],
                'answer' => $f[1],
                'category' => $f[2],
                'sort_order' => $f[3],
                'is_active' => true,
            ]);
        }
    }
}
