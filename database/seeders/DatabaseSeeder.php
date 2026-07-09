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
        // Ne touche jamais au mot de passe d'un admin déjà existant : un reseed ne
        // doit pas pouvoir écraser silencieusement un vrai mot de passe en production.
        if (! User::where('email', 'konatekader319@gmail.com')->exists()) {
            User::create([
                'email' => 'konatekader319@gmail.com',
                'name' => 'Manuela Kouadio',
                'password' => Hash::make(env('ADMIN_SEED_PASSWORD', Str::random(20))),
                'is_admin' => true,
            ]);
        }

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

            // Blac Héritage est seedé plus bas avec ses variantes, son storytelling
            // et ses dossiers 360° générés depuis les planches de la nouvelle collection.
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

        // ---- Blac Héritage (3 modèles, 9 coloris) ----
        $heritageCategory = $categories->firstWhere('name', 'Sacs à main');
        $heritageProducts = [
            [
                'aliases' => ['sac-bureau-homme-cognac'],
                'name' => 'Empire – Cognac Héritage',
                'price' => 89000,
                'color' => 'Marron Cognac',
                'variant_group' => 'empire',
                'image' => 'products/360/nouveau-produit-v2/empire-cognac-heritage/frame-000.png',
                'spin_folder' => 'products/360/nouveau-produit-v2/empire-cognac-heritage',
                'short' => 'Sac business premium 2-en-1 en cuir cognac.',
                'description' => 'Un sac professionnel modulable avec pochette détachable, pensé pour accompagner les dirigeants entre réunion stratégique, déplacement et rendez-vous d\'affaires.',
                'story' => implode("\n", [
                    'Le leadership se construit dans les décisions du quotidien.',
                    'Pensé pour les dirigeants, entrepreneurs et professionnels en mouvement, Empire associe innovation et élégance dans un design modulable unique.',
                    'Sa pochette détachable permet de passer naturellement d\'une réunion stratégique à un déjeuner d\'affaires ou à un déplacement professionnel, sans jamais compromettre son style.',
                    'Inspiré du symbole de la poupée ashanti, il incarne la résilience, la vision et la capacité à construire un héritage durable.',
                    'Fabriqué en cuir premium avec des finitions métalliques raffinées, Empire accompagne ceux qui ne se contentent pas de réussir : ils souhaitent laisser une empreinte.',
                    'Parce qu\'un héritage ne se transmet pas seulement par les mots, mais aussi par les choix que l\'on porte chaque jour.',
                ]),
                'material' => 'Cuir pleine fleur embossé',
                'dimensions' => '42 × 30 × 14 cm',
                'closure' => 'Fermeture premium gravée BLAC JOYAUX',
                'lining' => 'Organisation intérieure optimisée',
                'featured' => true,
                'stock' => 6,
            ],
            [
                'aliases' => ['sac-bureau-homme-marine'],
                'name' => 'Empire – Bleu Impérial',
                'price' => 92000,
                'color' => 'Bleu Nuit',
                'variant_group' => 'empire',
                'image' => 'products/360/nouveau-produit-v2/empire-bleu-imperial/frame-000.png',
                'spin_folder' => 'products/360/nouveau-produit-v2/empire-bleu-imperial',
                'short' => 'Sac business premium 2-en-1 en cuir bleu nuit.',
                'description' => 'Un bleu profond et structuré pour les professionnels qui veulent conjuguer présence, organisation et élégance dans leurs journées les plus exigeantes.',
                'story' => implode("\n", [
                    'Le leadership se construit dans les décisions du quotidien.',
                    'Pensé pour les dirigeants, entrepreneurs et professionnels en mouvement, Empire associe innovation et élégance dans un design modulable unique.',
                    'Sa pochette détachable permet de passer naturellement d\'une réunion stratégique à un déjeuner d\'affaires ou à un déplacement professionnel, sans jamais compromettre son style.',
                    'Inspiré du symbole de la poupée ashanti, il incarne la résilience, la vision et la capacité à construire un héritage durable.',
                    'Fabriqué en cuir premium avec des finitions métalliques raffinées, Empire accompagne ceux qui ne se contentent pas de réussir : ils souhaitent laisser une empreinte.',
                    'Parce qu\'un héritage ne se transmet pas seulement par les mots, mais aussi par les choix que l\'on porte chaque jour.',
                ]),
                'material' => 'Cuir pleine fleur embossé',
                'dimensions' => '42 × 30 × 14 cm',
                'closure' => 'Fermeture premium gravée BLAC JOYAUX',
                'lining' => 'Organisation intérieure optimisée',
                'featured' => true,
                'stock' => 6,
            ],
            [
                'aliases' => ['sac-bureau-homme-noir'],
                'name' => 'Empire – Ébène Croco',
                'price' => 99000,
                'color' => 'Noir Croco-Lézard',
                'variant_group' => 'empire',
                'image' => 'products/360/nouveau-produit-v2/empire-ebene-croco/frame-000.png',
                'spin_folder' => 'products/360/nouveau-produit-v2/empire-ebene-croco',
                'short' => 'Sac business premium 2-en-1 effet croco-lézard.',
                'description' => 'Une finition noire texturée, affirmée et sophistiquée, conçue pour porter les essentiels professionnels avec caractère.',
                'story' => implode("\n", [
                    'Le leadership se construit dans les décisions du quotidien.',
                    'Pensé pour les dirigeants, entrepreneurs et professionnels en mouvement, Empire associe innovation et élégance dans un design modulable unique.',
                    'Sa pochette détachable permet de passer naturellement d\'une réunion stratégique à un déjeuner d\'affaires ou à un déplacement professionnel, sans jamais compromettre son style.',
                    'Inspiré du symbole de la poupée ashanti, il incarne la résilience, la vision et la capacité à construire un héritage durable.',
                    'Fabriqué en cuir premium avec des finitions métalliques raffinées, Empire accompagne ceux qui ne se contentent pas de réussir : ils souhaitent laisser une empreinte.',
                    'Parce qu\'un héritage ne se transmet pas seulement par les mots, mais aussi par les choix que l\'on porte chaque jour.',
                ]),
                'material' => 'Cuir pleine fleur embossé croco-lézard',
                'dimensions' => '42 × 30 × 14 cm',
                'closure' => 'Fermeture premium gravée BLAC JOYAUX',
                'lining' => 'Organisation intérieure optimisée',
                'featured' => true,
                'stock' => 5,
            ],
            [
                'aliases' => ['sac-bureau-femme-marron'],
                'name' => 'ÉLAN – Cognac Héritage',
                'price' => 75000,
                'color' => 'Marron Cognac',
                'variant_group' => 'elan',
                'image' => 'products/360/nouveau-produit-v2/elan-cognac-heritage/frame-000.png',
                'spin_folder' => 'products/360/nouveau-produit-v2/elan-cognac-heritage',
                'short' => 'Sac de bureau premium pour femme en cuir cognac.',
                'description' => 'Un cognac profond et chaleureux qui sublime le cuir pleine fleur, avec une organisation pensée pour l\'ordinateur, les documents et les essentiels.',
                'story' => implode("\n", [
                    'Certaines femmes n\'attendent pas que les opportunités se présentent. Elles les créent.',
                    'ÉLAN est né pour accompagner celles qui avancent avec ambition, élégance et détermination.',
                    'Son architecture épurée symbolise la confiance, tandis que ses lignes équilibrées traduisent la maîtrise et la sérénité face aux défis du quotidien.',
                    'Inspiré de la poupée ashanti, il rappelle que la véritable force réside dans la persévérance et la foi en ses capacités.',
                    'Conçu pour accueillir un ordinateur 16 pouces, des documents et tous les essentiels professionnels, ÉLAN accompagne les femmes qui bâtissent leur avenir avec assurance.',
                ]),
                'material' => 'Cuir pleine fleur premium',
                'dimensions' => '41 × 36 × 13 cm',
                'closure' => 'Métallerie champagne satinée',
                'lining' => 'Poche zippée et rangements dédiés',
                'featured' => true,
                'stock' => 6,
            ],
            [
                'aliases' => ['sac-bureau-femme-rouge-bordeaux'],
                'name' => 'ÉLAN – Bordeaux Impérial',
                'price' => 78000,
                'color' => 'Rouge Bordeaux',
                'variant_group' => 'elan',
                'image' => 'products/360/nouveau-produit-v2/elan-bordeaux-imperial/frame-000.png',
                'spin_folder' => 'products/360/nouveau-produit-v2/elan-bordeaux-imperial',
                'short' => 'Sac de bureau premium pour femme en cuir bordeaux.',
                'description' => 'Un bordeaux intense et raffiné, symbole d\'audace, de féminité et de distinction pour les journées professionnelles affirmées.',
                'story' => implode("\n", [
                    'Certaines femmes n\'attendent pas que les opportunités se présentent. Elles les créent.',
                    'ÉLAN est né pour accompagner celles qui avancent avec ambition, élégance et détermination.',
                    'Son architecture épurée symbolise la confiance, tandis que ses lignes équilibrées traduisent la maîtrise et la sérénité face aux défis du quotidien.',
                    'Inspiré de la poupée ashanti, il rappelle que la véritable force réside dans la persévérance et la foi en ses capacités.',
                    'Conçu pour accueillir un ordinateur 16 pouces, des documents et tous les essentiels professionnels, ÉLAN accompagne les femmes qui bâtissent leur avenir avec assurance.',
                ]),
                'material' => 'Cuir pleine fleur premium',
                'dimensions' => '41 × 36 × 13 cm',
                'closure' => 'Métallerie champagne satinée',
                'lining' => 'Poche zippée et rangements dédiés',
                'featured' => true,
                'stock' => 6,
            ],
            [
                'aliases' => ['sac-bureau-femme-noir-croco-lezard'],
                'name' => 'ÉLAN – Ébène Croco',
                'price' => 82000,
                'color' => 'Noir Croco',
                'variant_group' => 'elan',
                'image' => 'products/360/nouveau-produit-v2/elan-ebene-croco/frame-000.png',
                'spin_folder' => 'products/360/nouveau-produit-v2/elan-ebene-croco',
                'short' => 'Sac de bureau premium pour femme effet croco.',
                'description' => 'Un noir profond à la texture croco embossée, pensé pour les femmes dirigeantes et les professionnelles exigeantes.',
                'story' => implode("\n", [
                    'Certaines femmes n\'attendent pas que les opportunités se présentent. Elles les créent.',
                    'ÉLAN est né pour accompagner celles qui avancent avec ambition, élégance et détermination.',
                    'Son architecture épurée symbolise la confiance, tandis que ses lignes équilibrées traduisent la maîtrise et la sérénité face aux défis du quotidien.',
                    'Inspiré de la poupée ashanti, il rappelle que la véritable force réside dans la persévérance et la foi en ses capacités.',
                    'Conçu pour accueillir un ordinateur 16 pouces, des documents et tous les essentiels professionnels, ÉLAN accompagne les femmes qui bâtissent leur avenir avec assurance.',
                ]),
                'material' => 'Cuir pleine fleur embossé croco',
                'dimensions' => '41 × 36 × 13 cm',
                'closure' => 'Métallerie champagne satinée',
                'lining' => 'Poche zippée et rangements dédiés',
                'featured' => true,
                'stock' => 5,
            ],
            [
                'aliases' => ['sac-lifestyle-joyaux-kaki'],
                'name' => 'L’Ami – Vert Canopée',
                'price' => 72000,
                'color' => 'Vert Canopée',
                'variant_group' => 'lami',
                'image' => 'products/360/nouveau-produit-v2/lami-vert-canopee/frame-000.png',
                'spin_folder' => 'products/360/nouveau-produit-v2/lami-vert-canopee',
                'short' => 'Sac de voyage premium en toile imperméable vert canopée.',
                'description' => 'Inspiré des forêts tropicales africaines, ce coloris porte une sensation de sérénité, d\'exploration et de mouvement.',
                'story' => implode("\n", [
                    'Chaque voyage est une occasion de grandir.',
                    'Pensé pour les escapades, les voyages d\'affaires et les moments de déconnexion, L’Ami accompagne celles et ceux qui savent que la réussite se nourrit aussi des expériences vécues.',
                    'Son design généreux offre tout l\'espace nécessaire pour partir quelques jours, tandis que ses lignes sobres et contemporaines reflètent un style de vie où performance et équilibre coexistent.',
                    'Inspiré du symbole poupée ashanti, il rappelle que chaque nouveau départ est une promesse, chaque horizon une opportunité.',
                    'Parce que les plus belles destinations commencent toujours par un premier pas.',
                ]),
                'material' => 'Toile premium imperméable et cuir pleine fleur',
                'dimensions' => '50 × 27 × 27 cm',
                'closure' => 'Sangle ajustable et amovible',
                'lining' => 'Grand compartiment et poche zippée',
                'featured' => true,
                'stock' => 6,
            ],
            [
                'aliases' => ['sac-lifestyle-joyaux-beige'],
                'name' => 'L’Ami – Beige Dune',
                'price' => 69000,
                'color' => 'Beige Dune',
                'variant_group' => 'lami',
                'image' => 'products/360/nouveau-produit-v2/lami-beige-dune/frame-000.png',
                'spin_folder' => 'products/360/nouveau-produit-v2/lami-beige-dune',
                'short' => 'Sac de voyage premium en toile imperméable beige.',
                'description' => 'Une teinte sable élégante et intemporelle, inspirée des paysages sahéliens et pensée pour les départs de deux à trois jours.',
                'story' => implode("\n", [
                    'Chaque voyage est une occasion de grandir.',
                    'Pensé pour les escapades, les voyages d\'affaires et les moments de déconnexion, L’Ami accompagne celles et ceux qui savent que la réussite se nourrit aussi des expériences vécues.',
                    'Son design généreux offre tout l\'espace nécessaire pour partir quelques jours, tandis que ses lignes sobres et contemporaines reflètent un style de vie où performance et équilibre coexistent.',
                    'Inspiré du symbole poupée ashanti, il rappelle que chaque nouveau départ est une promesse, chaque horizon une opportunité.',
                    'Parce que les plus belles destinations commencent toujours par un premier pas.',
                ]),
                'material' => 'Toile premium imperméable et cuir pleine fleur',
                'dimensions' => '50 × 27 × 27 cm',
                'closure' => 'Sangle ajustable et amovible',
                'lining' => 'Grand compartiment et poche zippée',
                'featured' => true,
                'stock' => 6,
            ],
            [
                'aliases' => ['sac-lifestyle-joyaux-cognac'],
                'name' => 'L’Ami – Orange Terre d’Ambre',
                'price' => 74000,
                'color' => 'Orange Terre d’Ambre',
                'variant_group' => 'lami',
                'image' => 'products/360/nouveau-produit-v2/lami-orange-terre-dambre/frame-000.png',
                'spin_folder' => 'products/360/nouveau-produit-v2/lami-orange-terre-dambre',
                'short' => 'Sac de voyage premium en toile imperméable orange ambre.',
                'description' => 'Une couleur chaleureuse rappelant la terre latéritique africaine et le cuir patiné, pour voyager avec caractère.',
                'story' => implode("\n", [
                    'Chaque voyage est une occasion de grandir.',
                    'Pensé pour les escapades, les voyages d\'affaires et les moments de déconnexion, L’Ami accompagne celles et ceux qui savent que la réussite se nourrit aussi des expériences vécues.',
                    'Son design généreux offre tout l\'espace nécessaire pour partir quelques jours, tandis que ses lignes sobres et contemporaines reflètent un style de vie où performance et équilibre coexistent.',
                    'Inspiré du symbole poupée ashanti, il rappelle que chaque nouveau départ est une promesse, chaque horizon une opportunité.',
                    'Parce que les plus belles destinations commencent toujours par un premier pas.',
                ]),
                'material' => 'Toile premium imperméable et cuir pleine fleur',
                'dimensions' => '50 × 27 × 27 cm',
                'closure' => 'Sangle ajustable et amovible',
                'lining' => 'Grand compartiment et poche zippée',
                'featured' => true,
                'stock' => 6,
            ],
        ];

        foreach ($heritageProducts as $item) {
            $slug = Str::slug($item['name']);
            $product = Product::whereIn('slug', array_merge([$slug], $item['aliases']))->first() ?? new Product;
            $metaTitle = $seo->generateMetaTitle($item['name'], $heritageCategory?->name);
            $metaDesc = $seo->generateMetaDescription($item['name'], $item['short'], $item['price']);
            $score = $seo->score([
                'name' => $item['name'],
                'slug' => $slug,
                'meta_title' => $metaTitle,
                'meta_description' => $metaDesc,
                'description' => $item['description'],
            ]);

            $product->fill([
                'category_id' => $heritageCategory?->id,
                'collection' => 'capsule',
                'name' => $item['name'],
                'slug' => $slug,
                'short_description' => $item['short'],
                'description' => $item['description'],
                'story' => $item['story'],
                'price' => $item['price'],
                'stock' => $product->exists ? $product->stock : $item['stock'],
                'image' => $item['image'],
                'gallery' => null,
                'spin_folder' => $item['spin_folder'],
                'color' => $item['color'],
                'variant_group' => $item['variant_group'],
                'material' => $item['material'],
                'dimensions' => $item['dimensions'],
                'closure' => $item['closure'],
                'lining' => $item['lining'],
                'is_active' => true,
                'is_featured' => $item['featured'],
                'meta_title' => $metaTitle,
                'meta_description' => $metaDesc,
                'seo_score' => $score['score'],
            ]);
            $product->save();
        }

        // ---- FAQ de démonstration ----
        $faqs = [
            ['Quels sont les délais de livraison ?', "Nous livrons à Abidjan en 1 à 3 jours ouvrés. Pour l'intérieur du pays, comptez 5 à 7 jours.", 'livraison', 1],
            ['Combien coûte la livraison ?', "La livraison à Abidjan est à 1 500 F CFA, l'intérieur du pays à 3 000 F CFA. Le retrait en boutique à Cocody Palmeraie est gratuit.", 'livraison', 2],
            ['Quels moyens de paiement acceptez-vous ?', 'Vous pouvez payer à la livraison, ou via Wave et Orange Money. Le paiement se confirme avec notre équipe sur WhatsApp.', 'paiement', 1],
            ['Dois-je créer un compte pour commander ?', 'Non, vous pouvez commander directement en renseignant vos coordonnées au moment de la commande.', 'livraison', 1],
            ['Vos sacs sont-ils en cuir véritable ?', 'La plupart de nos modèles sont en cuir grainé, lisse ou premium. La matière est précisée sur chaque fiche produit.', 'produit', 1],
            ['Comment entretenir mon sac Blac Joyaux ?', "Évitez l'humidité prolongée, nettoyez avec un chiffon doux et rangez votre sac dans sa housse. Un entretien régulier prolonge sa beauté.", 'produit', 2],
            ['Puis-je échanger ou retourner un article ?', 'Oui, vous disposez de 7 jours après réception pour un échange, sous réserve que le sac soit neuf et non utilisé. Contactez-nous sur WhatsApp.', 'retours', 2],
            ['Quelle est l\'histoire de Blac Joyaux ?', "Blac Joyaux, fondée par Manuela Kouadio, s'inspire de la poupée Ashanti Joyau de Bla (Akua'ba), symbole de beauté et de transmission culturelle.", 'produit', 3],
            ['Le cuir vegan est-il aussi résistant que le cuir véritable ?', "Nos modèles en cuir vegan sont conçus avec des matières haut de gamme, traitées pour résister aux frottements et à l'humidité au quotidien. Ils demandent le même entretien qu'un cuir classique, mais restent, sur le long terme, légèrement moins résistants qu'un cuir véritable. La matière exacte est toujours précisée sur la fiche de chaque produit.", 'produit', 4],
            ['Quel est le délai réel de livraison ?', "En moyenne, comptez 1 à 3 jours ouvrés pour Abidjan et 5 à 7 jours ouvrés pour l'intérieur du pays. Ce délai peut légèrement varier selon la disponibilité du modèle et votre zone de livraison ; notre équipe vous tient informé(e) sur WhatsApp en cas de retard.", 'livraison', 3],
            ['Peut-on payer par Wave ou Orange Money ?', "Oui, Wave et Orange Money sont acceptés pour toutes les commandes. Il vous suffit d'effectuer le transfert puis d'envoyer la capture de paiement à notre équipe sur WhatsApp pour confirmer votre commande.", 'paiement', 2],
            ['Blac Joyaux est-elle protégée juridiquement ?', "Oui, Blac Joyaux est une marque déposée. Notre nom, notre logo ainsi que nos créations sont protégés, et toute reproduction ou contrefaçon fait l'objet de poursuites.", 'general', 1],
            ['Quelle est la signification de la poupée Joyau de Bla ?', "Elle s'inspire de l'Akua'ba, une poupée traditionnelle ashanti symbole de fécondité, de beauté et de transmission entre générations de femmes. Elle incarne l'identité de la marque : l'héritage africain porté avec élégance et modernité.", 'produit', 5],
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
