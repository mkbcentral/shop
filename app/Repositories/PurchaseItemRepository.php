<?php

namespace App\Repositories;

use App\Models\PurchaseItem;
use Illuminate\Database\Eloquent\Collection;

class PurchaseItemRepository
{
    /**
     * Get all purchase items for a purchase.
     */
    public function getByPurchaseId(int $purchaseId): Collection
    {
        return PurchaseItem::where('purchase_id', $purchaseId)
            ->with('productVariant.product')
            ->orderBy('id')
            ->get();
    }

    /**
     * Find purchase item by ID.
     */
    public function find(int $id): ?PurchaseItem
    {
        return PurchaseItem::with('productVariant.product', 'purchase')->find($id);
    }

    /**
     * Create a new purchase item.
     */
    public function create(array $data): PurchaseItem
    {
        return PurchaseItem::create($data);
    }

    /**
     * Create multiple purchase items.
     */
    public function createMany(array $items): bool
    {
        return PurchaseItem::insert($items);
    }

    /**
     * Update purchase item.
     */
    public function update(int $id, array $data): bool
    {
        $item = PurchaseItem::find($id);
        if (!$item) {
            return false;
        }
        return $item->update($data);
    }

    /**
     * Delete purchase item.
     */
    public function delete(int $id): bool
    {
        $item = PurchaseItem::find($id);
        if (!$item) {
            return false;
        }
        return $item->delete();
    }

    /**
     * Get purchase items by variant.
     */
    public function getByVariantId(int $variantId): Collection
    {
        return PurchaseItem::where('product_variant_id', $variantId)
            ->with('purchase')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get total quantity purchased for a variant.
     */
    public function getTotalQuantityByVariant(int $variantId): int
    {
        return PurchaseItem::where('product_variant_id', $variantId)->sum('quantity');
    }

    /**
     * Delete all items for a purchase.
     */
    public function deleteByPurchaseId(int $purchaseId): bool
    {
        return PurchaseItem::where('purchase_id', $purchaseId)->delete();
    }
}
