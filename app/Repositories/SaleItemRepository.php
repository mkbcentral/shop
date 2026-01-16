<?php

namespace App\Repositories;

use App\Models\SaleItem;
use Illuminate\Database\Eloquent\Collection;

class SaleItemRepository
{
    /**
     * Get all sale items for a sale.
     */
    public function getBySaleId(int $saleId): Collection
    {
        return SaleItem::where('sale_id', $saleId)
            ->with('productVariant.product')
            ->orderBy('id')
            ->get();
    }

    /**
     * Find sale item by ID.
     */
    public function find(int $id): ?SaleItem
    {
        return SaleItem::with('productVariant.product', 'sale')->find($id);
    }

    /**
     * Create a new sale item.
     */
    public function create(array $data): SaleItem
    {
        return SaleItem::create($data);
    }

    /**
     * Create multiple sale items.
     */
    public function createMany(array $items): bool
    {
        return SaleItem::insert($items);
    }

    /**
     * Update sale item.
     */
    public function update(int $id, array $data): bool
    {
        $item = SaleItem::find($id);
        if (!$item) {
            return false;
        }
        return $item->update($data);
    }

    /**
     * Delete sale item.
     */
    public function delete(int $id): bool
    {
        $item = SaleItem::find($id);
        if (!$item) {
            return false;
        }
        return $item->delete();
    }

    /**
     * Get sale items by variant.
     */
    public function getByVariantId(int $variantId): Collection
    {
        return SaleItem::where('product_variant_id', $variantId)
            ->with('sale')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get total quantity sold for a variant.
     */
    public function getTotalQuantityByVariant(int $variantId): int
    {
        return SaleItem::where('product_variant_id', $variantId)->sum('quantity');
    }

    /**
     * Delete all items for a sale.
     */
    public function deleteBySaleId(int $saleId): bool
    {
        return SaleItem::where('sale_id', $saleId)->delete();
    }

    /**
     * Get best selling variants.
     */
    public function getBestSellers(int $limit = 10): Collection
    {
        return SaleItem::selectRaw('product_variant_id, SUM(quantity) as total_sold, SUM(subtotal) as total_revenue')
            ->with('productVariant.product')
            ->groupBy('product_variant_id')
            ->orderBy('total_sold', 'desc')
            ->limit($limit)
            ->get();
    }
}
