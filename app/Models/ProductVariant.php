<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    use HasFactory, BelongsToOrganization;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'organization_id',
        'product_id',
        'size',
        'color',
        'variant_name',
        'sku',
        'barcode',
        'stock_quantity',
        'additional_price',
        'low_stock_threshold',
        'min_stock_threshold',
        'serial_number',
        'expiry_date',
        'weight',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'stock_quantity' => 'integer',
        'additional_price' => 'decimal:2',
        'weight' => 'decimal:3',
        'expiry_date' => 'date',
    ];

    /**
     * Get the product that owns the variant.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get all attribute values for this variant.
     */
    public function attributeValues(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class, 'product_variant_id');
    }

    /**
     * Get all store stocks for this variant.
     */
    public function storeStocks(): HasMany
    {
        return $this->hasMany(StoreStock::class, 'product_variant_id');
    }

    /**
     * Get the stock quantity for the current store.
     * If user is viewing a specific store, returns stock for that store.
     * Otherwise returns global stock_quantity.
     */
    public function getCurrentStockAttribute(): int
    {
        $storeId = current_store_id();

        if ($storeId && !user_can_access_all_stores()) {
            return $this->getStoreStock($storeId);
        }

        return $this->stock_quantity;
    }

    /**
     * Get stock for a specific store.
     */
    public function getStoreStock(?int $storeId = null): int
    {
        $storeId = $storeId ?? current_store_id();

        if (!$storeId) {
            return $this->stock_quantity;
        }

        $storeStock = $this->storeStocks()->where('store_id', $storeId)->first();

        return $storeStock ? $storeStock->quantity : 0;
    }

    /**
     * Get all stock movements for this variant.
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Get all sale items for this variant.
     */
    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Get all purchase items for this variant.
     */
    public function purchaseItems(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    /**
     * Get the final price including additional price.
     */
    public function getFinalPriceAttribute(): float
    {
        return $this->product->price + $this->additional_price;
    }

    /**
     * Get the variant name (combines product name with size and color).
     */
    public function getFullNameAttribute(): string
    {
        $parts = [$this->product->name];

        if ($this->color) {
            $parts[] = $this->color;
        }

        if ($this->size) {
            $parts[] = $this->size;
        }

        return implode(' - ', $parts);
    }

    /**
     * Get the variant display name (size and/or color only).
     */
    public function getVariantName(): string
    {
        $parts = [];

        if ($this->color) {
            $parts[] = $this->color;
        }

        if ($this->size) {
            $parts[] = $this->size;
        }

        return !empty($parts) ? implode(' - ', $parts) : 'Standard';
    }

    /**
     * Get formatted attributes for display (from dynamic attributes)
     */
    public function getFormattedAttributes(): string
    {
        if ($this->attributeValues->isEmpty()) {
            // Fallback to legacy fields
            return $this->getVariantName();
        }

        $parts = [];
        foreach ($this->attributeValues as $attrValue) {
            $parts[] = $attrValue->productAttribute->name . ': ' . $attrValue->value;
        }

        return implode(', ', $parts);
    }

    /**
     * Get variant attribute value by code
     */
    public function getVariantAttributeValue(string $code): ?string
    {
        $attrValue = $this->attributeValues->first(function($av) use ($code) {
            return $av->productAttribute->code === $code;
        });

        return $attrValue ? $attrValue->value : null;
    }

    /**
     * Check if variant has sufficient stock.
     */
    public function hasStock(int $quantity = 1): bool
    {
        return $this->stock_quantity >= $quantity;
    }

    /**
     * Increase stock quantity.
     */
    public function increaseStock(int $quantity): void
    {
        $oldStock = $this->stock_quantity;
        $this->increment('stock_quantity', $quantity);
        $this->refresh();

        // Check if we went from out of stock to in stock
        if ($oldStock <= $this->min_stock_threshold && $this->stock_quantity > $this->min_stock_threshold) {
            // Stock restored - no alert needed
        }
    }

    /**
     * Decrease stock quantity.
     */
    public function decreaseStock(int $quantity): void
    {
        $oldStock = $this->stock_quantity;
        $this->decrement('stock_quantity', $quantity);
        $this->refresh();

        // Dispatch alerts if thresholds are crossed
        if ($this->stock_quantity <= $this->min_stock_threshold && $oldStock > $this->min_stock_threshold) {
            event(new \App\Events\OutOfStockAlert($this));
        } elseif ($this->stock_quantity <= $this->low_stock_threshold && $oldStock > $this->low_stock_threshold) {
            event(new \App\Events\LowStockAlert($this, 'low_stock'));
        }
    }

    /**
     * Scope a query to only include variants with stock.
     */
    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    /**
     * Scope a query to only include variants out of stock.
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('stock_quantity', '<=', 0);
    }

    /**
     * Check if stock is low.
     */
    public function isLowStock(): bool
    {
        return $this->stock_quantity > 0 && $this->stock_quantity <= $this->low_stock_threshold;
    }

    /**
     * Check if stock is out.
     */
    public function isOutOfStock(): bool
    {
        return $this->stock_quantity <= $this->min_stock_threshold;
    }

    /**
     * Get stock status.
     */
    public function getStockStatusAttribute(): string
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
     * Get stock level percentage.
     */
    public function getStockLevelPercentageAttribute(): float
    {
        if ($this->low_stock_threshold <= 0) {
            return 100;
        }

        return min(100, ($this->stock_quantity / $this->low_stock_threshold) * 100);
    }

    /**
     * Scope to find variant by barcode.
     */
    public function scopeByBarcode($query, string $barcode)
    {
        return $query->where('barcode', $barcode);
    }
}
