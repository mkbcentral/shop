<?php

namespace App\Repositories;

use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Collection;

class ProductVariantRepository
{
    /**
     * Get all variants.
     */
    public function all(): Collection
    {
        return ProductVariant::with('product')->get();
    }

    /**
     * Get query builder.
     */
    public function query()
    {
        return ProductVariant::query();
    }

    /**
     * Find variant by ID.
     */
    public function find(int $id): ?ProductVariant
    {
        return ProductVariant::with('product')->find($id);
    }

    /**
     * Find variant by SKU.
     */
    public function findBySku(string $sku): ?ProductVariant
    {
        return ProductVariant::where('sku', $sku)->with('product')->first();
    }

    /**
     * Get all variants for a product.
     */
    public function byProduct(int $productId): Collection
    {
        return ProductVariant::where('product_id', $productId)->get();
    }

    /**
     * Create a new variant.
     */
    public function create(array $data): ProductVariant
    {
        return ProductVariant::create($data);
    }

    /**
     * Update a variant.
     */
    public function update(ProductVariant $variant, array $data): bool
    {
        return $variant->update($data);
    }

    /**
     * Delete a variant.
     */
    public function delete(ProductVariant $variant): bool
    {
        return $variant->delete();
    }

    /**
     * Get variants with stock.
     */
    public function inStock(): Collection
    {
        return ProductVariant::inStock()->with('product')->get();
    }

    /**
     * Get out of stock variants.
     */
    public function outOfStock(): Collection
    {
        return ProductVariant::outOfStock()->with('product')->get();
    }

    /**
     * Get low stock variants.
     */
    public function lowStock(int $threshold = 10): Collection
    {
        return ProductVariant::where('stock_quantity', '<=', $threshold)
            ->where('stock_quantity', '>', 0)
            ->with('product')
            ->get();
    }

    /**
     * Update stock quantity.
     */
    public function updateStock(ProductVariant $variant, int $quantity): bool
    {
        return $variant->update(['stock_quantity' => $quantity]);
    }

    /**
     * Get variants for stock report with optional filters.
     */
    public function forStockReport(?string $stockLevel = null, ?int $categoryId = null): Collection
    {
        $query = ProductVariant::with(['product.category'])
            ->orderBy('stock_quantity', 'asc');

        if ($stockLevel) {
            switch ($stockLevel) {
                case 'out':
                    $query->where('stock_quantity', '<=', 0);
                    break;
                case 'low':
                    $query->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
                          ->where('stock_quantity', '>', 0);
                    break;
                case 'normal':
                    $query->whereColumn('stock_quantity', '>', 'low_stock_threshold');
                    break;
            }
        }

        if ($categoryId) {
            $query->whereHas('product', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }

        return $query->get();
    }

    /**
     * Get variants with stock for inventory report.
     */
    public function forInventoryReport(): Collection
    {
        return ProductVariant::with(['product.category'])
            ->where('stock_quantity', '>', 0)
            ->orderBy('stock_quantity', 'desc')
            ->get();
    }

    /**
     * Get out of stock variants with product and category.
     */
    public function outOfStockWithDetails(): Collection
    {
        return ProductVariant::with(['product.category'])
            ->where('stock_quantity', '<=', 0)
            ->orderBy('stock_quantity', 'asc')
            ->get();
    }

    /**
     * Get low stock variants based on threshold.
     */
    public function lowStockWithDetails(): Collection
    {
        return ProductVariant::with(['product.category'])
            ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->where('stock_quantity', '>', 0)
            ->orderBy('stock_quantity', 'asc')
            ->get();
    }
}
