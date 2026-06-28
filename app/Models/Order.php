<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference', 'customer_name', 'customer_phone', 'customer_email',
        'shipping_address', 'city', 'delivery_method', 'delivery_fee',
        'payment_method', 'status', 'subtotal', 'total', 'notes',
    ];

    protected $casts = [
        'subtotal' => 'integer',
        'total' => 'integer',
        'delivery_fee' => 'integer',
    ];

    public const STATUSES = [
        'en_attente' => 'En attente',
        'confirmee'  => 'Confirmée',
        'expediee'   => 'Expédiée',
        'livree'     => 'Livrée',
        'annulee'    => 'Annulée',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total, 0, ',', ' ').' F CFA';
    }

    /** Génère une référence unique type BJ-2026-0001 */
    public static function generateReference(): string
    {
        $year = date('Y');
        $count = static::whereYear('created_at', $year)->count() + 1;
        return sprintf('BJ-%s-%04d', $year, $count);
    }
}
