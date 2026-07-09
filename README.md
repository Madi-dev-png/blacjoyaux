# Blac Joyaux — Boutique e-commerce (Laravel 11)

Prototype e-commerce pour **Blac Joyaux**, marque ivoirienne de maroquinerie féminine
inspirée de l'héritage Ashanti (poupée *Joyaux de Bla* / Akua'ba).
Projet pédagogique IFRAN — *Mode Agence* — **Groupe 8**.

---

## Fonctionnalités

**Boutique (front-office)**
- Page d'accueil (héros, pièces phares, nouveautés, valeurs de marque)
- Collection avec filtres (catégorie, recherche, tri par prix) + pagination
- Fiche produit détaillée (galerie, coloris, matière, stock, CTA WhatsApp)
- Panier de session **sans création de compte** (guest checkout)
- Tunnel de commande : coordonnées, choix de livraison, choix de paiement
- Page de confirmation avec référence unique (ex. `BJ-2026-0001`)
- Page « Notre histoire » + **FAQ**

**Assistant IA (sur toutes les pages)**
- Widget de chat flottant en bas à droite de chaque page
- Répond aux questions sur la marque, les produits, la livraison, le paiement
- Connaît le **catalogue en temps réel** et la **FAQ** (injectés dans le contexte)
- Utilise l'API Anthropic (Claude). **Mode dégradé** : si aucune clé n'est configurée,
  il répond automatiquement à partir de la FAQ — la démo fonctionne donc sans clé.

**Référencement (SEO)**
- Balises `title`, `meta description`, canonical et Open Graph sur chaque page
- Données structurées JSON-LD (Product sur les fiches, FAQPage sur la FAQ)
- `sitemap.xml` dynamique + `robots.txt`
- **Formulaire produit admin avec assistant SEO en temps réel** : génère
  automatiquement le slug, le meta-title et la meta-description, affiche un
  **score de qualité 0–100** avec checklist et aperçu Google en direct.

**Back-office administrateur (CRUD)**
- Connexion sécurisée (réservée aux administrateurs)
- Tableau de bord (statistiques, dernières commandes, stock faible)
- Gestion des produits (créer / éditer / supprimer + upload image + SEO auto)
- Gestion des commandes (filtrer par statut, voir le détail, changer le statut)
- Gestion de la FAQ (ajouter / éditer / supprimer)

---

## Installation (sur votre Mac)

> Pré-requis : PHP ≥ 8.2 et Composer installés.
> (Vérifiez avec `php -v` et `composer --version`.)

```bash
# 1. Se placer dans le dossier du projet
cd blacjoyaux

# 2. Installer les dépendances PHP (génère le dossier vendor/)
composer install

# 3. Créer le fichier .env à partir de l'exemple
cp .env.example .env

# 4. Générer la clé d'application
php artisan key:generate

# 5. Créer la base SQLite (fichier vide)
touch database/database.sqlite

# 6. Créer les tables + insérer les données de démo
php artisan migrate --seed

# 7. Lier le dossier de stockage (pour les images produits)
php artisan storage:link

# 8. Lancer le serveur
php artisan serve
```

Le site est accessible sur **http://localhost:8000**

---

##  Connexion administrateur

- URL : **http://localhost:8000/login**
- Email : `konatekader319@gmail.com`
- Mot de passe : celui du compte admin (non documenté ici pour des raisons de sécurité).
  Sur une base fraîchement seedée, un mot de passe aléatoire est généré — définissez
  `ADMIN_SEED_PASSWORD` dans `.env` avant `php artisan migrate --seed` pour le choisir.

(Le back-office est ensuite sur **http://localhost:8000/admin**)

---

## Activer le vrai chat IA (facultatif)

Par défaut, l'assistant répond depuis la FAQ. Pour activer les réponses
intelligentes via Claude :

1. Récupérez une clé sur https://console.anthropic.com/
2. Dans le fichier `.env`, renseignez :
   ```
   ANTHROPIC_API_KEY=sk-ant-votre-cle-ici
   ANTHROPIC_MODEL=claude-sonnet-4-6
   ```
3. Rechargez la page : le chat utilise désormais l'IA.

> Pensez aussi à mettre votre vrai numéro WhatsApp dans `.env` :
> `BJ_WHATSAPP=2250153864606`

---

## Stack technique

| Élément        | Choix                          |
|----------------|--------------------------------|
| Framework      | Laravel 11                     |
| Base de données| SQLite (zéro configuration)    |
| Front          | Blade + CSS maison (vanilla)   |
| Chat IA        | API Anthropic (Claude)         |
| Authentification | Native Laravel (admin only)  |

---

## Organisation rapide

```
app/Http/Controllers/      → boutique (Home, Product, Cart, Checkout, Faq, Chat…)
app/Http/Controllers/Admin/→ back-office (Dashboard, Product, Order, Faq)
app/Services/              → SeoService (SEO auto), ChatService (IA), CartService
app/Models/                → Product, Category, Order, OrderItem, Faq, User
database/seeders/          → données de démonstration
resources/views/shop/      → pages de la boutique
resources/views/admin/     → pages du back-office
public/css/app.css         → identité visuelle Blac Joyaux
public/js/chat.js          → logique du widget de chat
```

---

*Les pages de design personnalisées peuvent être intégrées dans les vues Blade
correspondantes (`resources/views/`).*
