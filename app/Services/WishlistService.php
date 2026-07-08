<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

/**
 * Liste d'envies basée sur la session (guest — aucun compte requis), même
 * principe que CartService mais sans quantité : juste un ensemble d'ids.
 */
class WishlistService
{
    protected string $key = 'wishlist';

    public function ids(): array
    {
        return Session::get($this->key, []);
    }

    public function has(int $productId): bool
    {
        return in_array($productId, $this->ids(), true);
    }

    /** Ajoute ou retire le produit de la liste. Retourne le nouvel état (true = ajouté). */
    public function toggle(int $productId): bool
    {
        $ids = $this->ids();

        if (in_array($productId, $ids, true)) {
            Session::put($this->key, array_values(array_diff($ids, [$productId])));

            return false;
        }

        $ids[] = $productId;
        Session::put($this->key, $ids);

        return true;
    }

    public function items(): \Illuminate\Support\Collection
    {
        $ids = $this->ids();

        if (empty($ids)) {
            return collect();
        }

        return Product::whereIn('id', $ids)->get()->sortBy(fn ($p) => array_search($p->id, $ids))->values();
    }

    public function count(): int
    {
        return count($this->ids());
    }
}
