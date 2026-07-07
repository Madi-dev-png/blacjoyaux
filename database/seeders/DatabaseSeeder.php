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
        ])->map(fn ($c) => Category::updateOrCreate(
            ['slug' => Str::slug($c['name'])],
            ['name' => $c['name'], 'description' => $c['description']]
        ));

        $seo = app(SeoService::class);

        // ---- Produits de démonstration ----
       $products = [
            // --- Joyau de Bla (4) — Best-seller ---
            ['Sac à main – Nouvelle version – Noir avec Bijoux Doré', 'Sacs à main', 'joyau_de_bla', 50000, 'Sac à main structuré, finitions dorées.', 'La nouvelle version de notre sac signature, en cuir noir rehaussé de bijoux dorés. Une pièce intemporelle et raffinée.', 'Noir', 'Cuir grainé', true, 8],
            ['Sac à main – Nouvelle version – Vert',                 'Sacs à main', 'joyau_de_bla', 50000, 'Sac à main coloré, nouvelle version.', 'Un coloris vert éclatant pour twister votre style avec élégance et caractère.', 'Vert', 'Cuir grainé', true, 6],
            ['Sac à main – Nouvelle version – Rouge',                'Sacs à main', 'joyau_de_bla', 50000, 'Sac à main coloré, nouvelle version.', 'Un rouge intense qui affirme votre personnalité, pour un look qui ne passe pas inaperçu.', 'Rouge', 'Cuir grainé', false, 6],
            ['Sac à main – Nouvelle version – Orange',               'Sacs à main', 'joyau_de_bla', 50000, 'Sac à main coloré, nouvelle version.', 'Une touche d\'orange vibrant, parfaite pour illuminer toutes vos tenues.', 'Orange', 'Cuir grainé', false, 5],

            // --- Collection DO (4) — Nouveauté ---
            ['Sac à main – Collection DO – Cuir Marron (avec boucle)', 'Sacs à main', 'collection_do', 50000, 'Sac à main en cuir marron avec boucle décorative.', 'Un sac élégant en cuir marron véritable, orné d\'une boucle qui apporte du caractère à chaque tenue.', 'Marron', 'Cuir véritable', true, 7],
            ['Sac à main – Collection DO – Cuir Marron',               'Sacs à main', 'collection_do', 50000, 'Sac à main en cuir marron, ligne épurée.', 'La sobriété du cuir marron dans une ligne épurée, pour un usage quotidien élégant.', 'Marron', 'Cuir véritable', true, 6],
            ['Sac à main – Collection DO – Croco Lézard',              'Sacs à main', 'collection_do', 70000, 'Sac à main effet croco/lézard, finition premium.', 'Un effet croco-lézard qui sublime ce sac au design contemporain, pour une allure sophistiquée.', 'Marron croco', 'Cuir effet croco', true, 4],
            ['DO Tote',                                                'Cabas',       'collection_do', 75000, 'Cabas Tote spacieux, cuir souple.', 'Le tote bag DO, spacieux et pratique, taillé pour accompagner vos journées chargées avec style.', 'Camel', 'Cuir souple', true, 5],

            // --- Capsule (3) — Exclusif ---
            ['Capsule Noir/Gye Nyame Horizon', 'Pochettes',   'capsule', 60000, 'Pièce capsule exclusive, motif Gye Nyame.', 'Une création capsule en noir, ornée du symbole Gye Nyame, pour une pièce unique et symbolique.', 'Noir & or', 'Cuir premium', true, 3],
            ['Gye Nyame Legacy',               'Sacs à main', 'capsule', 60000, 'Pièce capsule exclusive, héritage Gye Nyame.', 'Un sac d\'exception qui porte l\'héritage du symbole Gye Nyame, structuré et intemporel.', 'Marron cognac', 'Cuir premium', true, 3],
            ['Gye Nyame Élan',                 'Pochettes',   'capsule', 55000, 'Pièce capsule exclusive, motif Gye Nyame doré.', 'Une pochette raffinée aux motifs dorés inspirés du symbole Gye Nyame, pour un élan de style unique.', 'Noir & doré', 'Cuir verni', false, 4],
        ];

        foreach ($products as $p) {
            [$name, $catName, $collection, $price, $short, $desc, $color, $material, $featured, $stock] = $p;
            $category = $categories->firstWhere('name', $catName);

            $slug = Str::slug($name);
            $metaTitle = $seo->generateMetaTitle($name, $catName);
            $metaDesc = $seo->generateMetaDescription($name, $short, $price);
            $score = $seo->score([
                'name' => $name, 'slug' => $slug,
                'meta_title' => $metaTitle, 'meta_description' => $metaDesc,
                'description' => $desc,
            ]);

            Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $category?->id,
                    'collection' => $collection,
                    'name' => $name,
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
                ]
            );
        }

        // ---- FAQ de démonstration ----
        $faqs = [
            ['Quels sont les délais de livraison ?', "Nous livrons à Abidjan en 1 à 3 jours ouvrés. Pour l'intérieur du pays, comptez 5 à 7 jours.", 'livraison', 1],
            ['Combien coûte la livraison ?', "La livraison à Abidjan est à 1 500 F CFA, l'intérieur du pays à 3 000 F CFA. Le retrait en boutique à Cocody Palmeraie est gratuit.", 'livraison', 2],
            ['Quels moyens de paiement acceptez-vous ?', "Vous pouvez payer à la livraison, ou via Wave et Orange Money. Le paiement se confirme avec notre équipe sur WhatsApp.", 'paiement', 1],
            ['Dois-je créer un compte pour commander ?', "Non, vous pouvez commander directement en renseignant vos coordonnées au moment de la commande.", 'livraison', 1],
            ['Vos sacs sont-ils en cuir véritable ?', "La plupart de nos modèles sont en cuir grainé, lisse ou premium. La matière est précisée sur chaque fiche produit.", 'produit', 1],
            ['Comment entretenir mon sac Blac Joyaux ?', "Évitez l'humidité prolongée, nettoyez avec un chiffon doux et rangez votre sac dans sa housse. Un entretien régulier prolonge sa beauté.", 'produit', 2],
            ['Puis-je échanger ou retourner un article ?', "Oui, vous disposez de 7 jours après réception pour un échange, sous réserve que le sac soit neuf et non utilisé. Contactez-nous sur WhatsApp.",'retours', 2],
            ['Quelle est l\'histoire de Blac Joyaux ?', "Blac Joyaux, fondée par Manuela Kouadio, s'inspire de la poupée Ashanti Joyaux de Bla (Akua'ba), symbole de beauté et de transmission culturelle.", 'produit', 3],
            ['Le cuir vegan est-il aussi résistant que le cuir véritable ?', "Nos modèles en cuir vegan sont conçus avec des matières haut de gamme, traitées pour résister aux frottements et à l'humidité au quotidien. Ils demandent le même entretien qu'un cuir classique, mais restent, sur le long terme, légèrement moins résistants qu'un cuir véritable. La matière exacte est toujours précisée sur la fiche de chaque produit.", 'produit', 4],
            ['Quel est le délai réel de livraison ?', "En moyenne, comptez 1 à 3 jours ouvrés pour Abidjan et 5 à 7 jours ouvrés pour l'intérieur du pays. Ce délai peut légèrement varier selon la disponibilité du modèle et votre zone de livraison ; notre équipe vous tient informé(e) sur WhatsApp en cas de retard.", 'livraison', 3],
            ['Peut-on payer par Wave ou Orange Money ?', "Oui, Wave et Orange Money sont acceptés pour toutes les commandes. Il vous suffit d'effectuer le transfert puis d'envoyer la capture de paiement à notre équipe sur WhatsApp pour confirmer votre commande.", 'paiement', 2],
            ['Blac Joyaux est-elle protégée juridiquement ?', "Oui, Blac Joyaux est une marque déposée. Notre nom, notre logo ainsi que nos créations sont protégés, et toute reproduction ou contrefaçon fait l'objet de poursuites.", 'general', 1],
            ['Quelle est la signification de la poupée Joyaux de Bla ?', "Elle s'inspire de l'Akua'ba, une poupée traditionnelle ashanti symbole de fécondité, de beauté et de transmission entre générations de femmes. Elle incarne l'identité de la marque : l'héritage africain porté avec élégance et modernité.", 'produit', 5],
            ['Quelle est la politique de retour ?', "Vous disposez de 7 jours après réception de votre commande pour demander un échange ou un retour, à condition que le sac soit neuf, non utilisé et dans son emballage d'origine. Contactez notre équipe sur WhatsApp pour lancer la procédure.", 'retours', 3],
        ];

        foreach ($faqs as $f) {
            Faq::updateOrCreate(
                ['question' => $f[0]],
                [
                    'answer' => $f[1],
                    'category' => $f[2],
                    'sort_order' => $f[3],
                    'is_active' => true,
                ]
            );
        }
    }
}
