<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

   protected $fillable = [
        'category_id', 'collection', 'name', 'slug', 'short_description', 'description', 'story',
        'price', 'stock', 'image', 'gallery', 'spin_folder', 'color', 'variant_group', 'material',
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
     * Format réel largeur/hauteur de la photo (ex: "608/1080"), pour que la case qui
     * l'affiche épouse toujours la vraie forme du fichier — même si la photo est
     * remplacée plus tard sous un autre nom. Repli sur 3/4 si le fichier est absent
     * ou illisible.
     */
    public function getImageRatioAttribute(): string
    {
        if (! $this->image) {
            return '3/4';
        }

        $path = storage_path('app/public/'.$this->image);

        if (! file_exists($path)) {
            return '3/4';
        }

        $size = @getimagesize($path);

        if (! $size) {
            return '3/4';
        }

        return $size[0].'/'.$size[1];
    }

    /**
     * URLs des images de la rotation 360° (triées par angle), ou tableau vide
     * si le produit n'a pas de dossier "spin_folder" renseigné.
     */
    public function getSpinFramesAttribute(): array
    {
        if (! $this->spin_folder) {
            return [];
        }

        $files = Storage::disk('public')->files($this->spin_folder);
        sort($files, SORT_NATURAL);

        return array_map(fn ($file) => asset('storage/'.$file), $files);
    }

    /**
     * Nom "de base" du modèle, sans le suffixe de couleur (utilisé uniquement
     * comme information secondaire désormais ; le regroupement réel des couleurs
     * se fait via le champ variant_group, plus fiable).
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
            'vert' => '#3d6b4f', 'bleu marine' => '#1b2a4a', 'marine' => '#1b2a4a', 'bleu' => '#2b4b7e', 'jaune' => '#e0b23a',
            'orange' => '#d16a2c', 'marron' => '#5c3d2e', 'beige' => '#d8c6a8',
            'camel' => '#b98a52', 'bordeaux' => '#5e1f2e', 'aubergine' => '#3f2436',
            'rose' => '#e0a1b0', 'gris' => '#8a8a8a', 'cognac' => '#9a5b2e',
            'croco' => '#5c3d2e', 'terre cuite' => '#b5622f', 'émeraude' => '#1f6b4f',
            'kaki' => '#6b6f3a',
            'doré' => '#c8902f', 'dore' => '#c8902f', 'or' => '#c8902f',
        ];

        $name = strtolower($this->color ?? '');

        // On teste les mots-clés du plus long au plus court, pour éviter qu'un mot court
        // (ex: "or") ne matche par erreur à l'intérieur d'un mot plus long (ex: "bORdeaux").
        $keys = array_keys($map);
        usort($keys, fn ($a, $b) => mb_strlen($b) <=> mb_strlen($a));

        foreach ($keys as $key) {
            if (str_contains($name, $key)) {
                return $map[$key];
            }
        }

        return '#cccccc'; // couleur par défaut si non reconnue
    }
}