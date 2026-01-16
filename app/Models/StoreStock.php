<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreStock extends Model
{
    protected $table = 'store_stock';

    protected $fillable = [
        'store_id',
        'product_variant_id',
        'quantity',
        'low_stock_threshold',
        'min_stock_threshold',
        'last_inventory_date',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'low_stock_threshold' => 'integer',
        'min_stock_threshold' => 'integer',
        'last_inventory_date' => 'date',
    ];

    /**
     * Get the store this stock belongs to
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Get the product variant
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    /**
     * Check if stock is low
     */
    public function isLowStock(): bool
    {
        return $this->quantity > 0 && $this->quantity <= $this->low_stock_threshold;
    }

    /**
     * Check if out of stock
     */
    public function isOutOfStock(): bool
    {
        return $this->quantity <= $this->min_stock_threshold;
    }

    /**
     * Check if has sufficient stock
     */
    public function hasSufficientStock(int $requiredQuantity): bool
    {
        return $this->quantity >= $requiredQuantity;
    }

    /**
     * Get stock status
     */
    public function getStockStatus(): string
    {
        if ($this->isOutOfStock()) {
            return 'out_of_stock';
        }

        if ($this->isLowStock()) {
            return 'low_stock';
        }

        return 'in_stock';
    }

    /**
     * Get stock level percentage
     */
    public function getStockLevelPercentage(): float
    {
        if ($this->low_stock_threshold == 0) {
            return 100;
        }

        return min(100, ($this->quantity / $this->low_stock_threshold) * 100);
    }

    /**
     * Increase stock quantity
     */
    public function increaseStock(int $quantity): void
    {
        $this->increment('quantity', $quantity);
        $this->syncVariantStock();
    }

    /**
     * Decrease stock quantity
     */
    public function decreaseStock(int $quantity): void
    {
        $this->decrement('quantity', $quantity);
        $this->syncVariantStock();
    }

    /**
     * Set exact stock quantity
     */
    public function setStock(int $quantity): void
    {
        $this->update(['quantity' => $quantity]);
        $this->syncVariantStock();
    }

    /**
     * Synchronize variant stock_quantity with total stock across all stores
     */
    protected function syncVariantStock(): void
    {
        if (!$this->product_variant_id) {
            return;
        }

        // Calculate total stock across all stores for this variant
        $totalStock = static::where('product_variant_id', $this->product_variant_id)
            ->sum('quantity');

        // Update the variant's stock_quantity
        ProductVariant::where('id', $this->product_variant_id)
            ->update(['stock_quantity' => $totalStock]);
    }

    /**
     * Scopes
     */
    public function scopeLowStock($query)
    {
        return $query->whereColumn('quantity', '<=', 'low_stock_threshold')
            ->where('quantity', '>', 0);
    }

    public function scopeOutOfStock($query)
    {
        return $query->whereColumn('quantity', '<=', 'min_stock_threshold');
    }

    public function scopeInStock($query)
    {
        return $query->whereColumn('quantity', '>', 'low_stock_threshold');
    }
}
