<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

   protected $fillable = [
        'category_id', 'collection', 'name', 'slug', 'short_description', 'description',
        'price', 'stock', 'image', 'gallery', 'color', 'material',
        'dimensions', 'closure', 'lining',
        'is_active', 'is_featured',
        'meta_title', 'meta_description', 'seo_score',
    ];
    protected $casts = [
        'gallery' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'price' => 'integer',
        'stock' => 'integer',
        'seo_score' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Prix formaté en F CFA : 65000 -> "65 000 F CFA" */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', ' ').' F CFA';
    }

    public function getInStockAttribute(): bool
    {
        return $this->stock > 0;
    }

    /**
     * Nom "de base" du modèle, sans le suffixe de couleur.
     * Ex: "Sac à main – Nouvelle version – Rouge" -> "Sac à main – Nouvelle version"
     * Sert à regrouper les vraies variantes de couleur d'un même sac (et uniquement celles-ci),
     * au lieu de mélanger tous les produits d'une même collection.
     */
    public function getBaseNameAttribute(): string
    {
        $parts = preg_split('/\s+[–-]\s+/u', trim($this->name));

        if (count($parts) < 2) {
            return trim($this->name);
        }

        array_pop($parts);

        return trim(implode(' – ', $parts));
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /** Convertit le champ texte "color" (ex: "Vert", "Noir & doré") en code hexadécimal pour affichage. */
    public function getColorHexAttribute(): string
    {
        $map = [
            'noir' => '#1a1a1a', 'blanc' => '#f5f5f5', 'rouge' => '#b3261e',
            'vert' => '#3d6b4f', 'bleu' => '#2b4b7e', 'jaune' => '#e0b23a',
            'orange' => '#d16a2c', 'marron' => '#5c3d2e', 'beige' => '#d8c6a8',
            'camel' => '#b98a52', 'or' => '#c8902f', 'doré' => '#c8902f',
            'dore' => '#c8902f', 'bordeaux' => '#5e1f2e', 'aubergine' => '#3f2436',
            'rose' => '#e0a1b0', 'gris' => '#8a8a8a', 'cognac' => '#9a5b2e',
            'croco' => '#5c3d2e', 'terre cuite' => '#b5622f', 'émeraude' => '#1f6b4f',
        ];

        $name = strtolower($this->color ?? '');
        foreach ($map as $key => $hex) {
            if (str_contains($name, $key)) {
                return $hex;
            }
        }

        return '#cccccc'; // couleur par défaut si non reconnue
    }
}