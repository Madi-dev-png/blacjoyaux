<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'min_subtotal', 'max_uses', 'used_count', 'expires_at', 'is_active',
    ];

    protected $casts = [
        'value' => 'integer',
        'min_subtotal' => 'integer',
        'max_uses' => 'integer',
        'used_count' => 'integer',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public static function findUsable(string $code): ?self
    {
        return static::where('code', strtoupper(trim($code)))->first();
    }

    /** Pourquoi ce code ne peut pas être utilisé pour ce sous-total, ou null s'il est valide. */
    public function invalidReason(int $subtotal): ?string
    {
        if (! $this->is_active) {
            return 'Ce code promo n\'est plus actif.';
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return 'Ce code promo a expiré.';
        }

        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) {
            return 'Ce code promo a atteint sa limite d\'utilisation.';
        }

        if ($this->min_subtotal && $subtotal < $this->min_subtotal) {
            $min = number_format($this->min_subtotal, 0, ',', ' ');

            return "Ce code nécessite un panier d'au moins {$min} FCFA.";
        }

        return null;
    }

    public function isUsableFor(int $subtotal): bool
    {
        return $this->invalidReason($subtotal) === null;
    }

    /** Montant de la réduction (jamais plus que le sous-total lui-même). */
    public function discountFor(int $subtotal): int
    {
        $discount = $this->type === 'percent'
            ? (int) round($subtotal * $this->value / 100)
            : $this->value;

        return min($discount, $subtotal);
    }
}
