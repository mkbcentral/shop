<?php

namespace App\Models;

use App\Traits\HasStoreScope;
use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasStoreScope, BelongsToOrganization;

    /**
     * Product status constants.
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'organization_id',
        'store_id',
        'product_type_id',
        'category_id',
        'name',
        'description',
        'reference',
        'barcode',
        'qr_code',
        'slug',
        'price',
        'max_discount_amount',
        'cost_price',
        'image',
        'status',
        'stock_alert_threshold',
        'expiry_date',
        'manufacture_date',
        'weight',
        'length',
        'width',
        'height',
        'unit_of_measure',
        'brand',
        'model',
        // Service-specific fields
        'duration_minutes',
        'pricing_type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'weight' => 'decimal:3',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'expiry_date' => 'date',
        'manufacture_date' => 'date',
        // Service-specific casts
        'duration_minutes' => 'integer',
    ];

    /**
     * Get the category that owns the product.
     * Note: We use withoutGlobalScope to allow categories from any organization
     * since categories can be shared or belong to different organizations.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class)->withoutGlobalScope('organization');
    }

    /**
     * Get the store that owns the product.
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Get the product type that owns the product.
     */
    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    /**
     * Get the stock records for this product in different stores.
     */
    public function storeStock()
    {
        return $this->hasManyThrough(
            StoreStock::class,
            ProductVariant::class,
            'product_id',        // Foreign key on product_variants table
            'product_variant_id', // Foreign key on store_stock table
            'id',                // Local key on products table
            'id'                 // Local key on product_variants table
        );
    }

    /**
     * Get all variants for this product.
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Get the attribute values for this product (via default variant or direct).
     */
    public function attributeValues()
    {
        // Pour les produits sans variants, on retourne les valeurs du variant par défaut
        return $this->hasManyThrough(
            ProductAttributeValue::class,
            ProductVariant::class,
            'product_id',
            'product_variant_id',
            'id',
            'id'
        );
    }

    /**
     * Get the total stock across all variants.
     * If user is viewing a specific store, returns stock for that store only.
     * Services always return infinite stock (PHP_INT_MAX).
     */
    public function getTotalStockAttribute(): int
    {
        // Services have infinite stock
        if ($this->isService()) {
            return PHP_INT_MAX;
        }

        $storeId = current_store_id();

        if ($storeId && !user_can_access_all_stores()) {
            // Return stock for specific store from store_stock table
            return $this->getStoreStock($storeId);
        }

        // Admin viewing all stores - return global stock
        return $this->variants()->sum('stock_quantity');
    }

    /**
     * Get total stock for a specific store.
     * Services always return infinite stock.
     */
    public function getStoreStock(?int $storeId = null): int
    {
        // Services have infinite stock
        if ($this->isService()) {
            return PHP_INT_MAX;
        }

        $storeId = $storeId ?? current_store_id();

        if (!$storeId) {
            return $this->variants()->sum('stock_quantity');
        }

        return \App\Models\StoreStock::whereIn('product_variant_id', $this->variants()->pluck('id'))
            ->where('store_id', $storeId)
            ->sum('quantity');
    }

    /**
     * Get the profit margin percentage.
     * Formula: ((price - cost) / price) * 100
     *
     * @return float|null Returns null if cost_price is null or price is 0
     */
    public function getProfitMargin(): ?float
    {
        if (!$this->cost_price || $this->price <= 0) {
            return null;
        }

        return (($this->price - $this->cost_price) / $this->price) * 100;
    }

    /**
     * Get the profit margin formatted as a percentage string.
     *
     * @return string Returns 'N/A' if margin cannot be calculated
     */
    public function getProfitMarginFormatted(): string
    {
        $margin = $this->getProfitMargin();

        if ($margin === null) {
            return 'N/A';
        }

        return number_format($margin, 2, ',', '') . ' %';
    }

    /**
     * Determine if the profit margin is low (< 10%).
     */
    public function hasLowMargin(): bool
    {
        $margin = $this->getProfitMargin();
        return $margin !== null && $margin < 10;
    }

    /**
     * Determine if the profit margin is medium (10-30%).
     */
    public function hasMediumMargin(): bool
    {
        $margin = $this->getProfitMargin();
        return $margin !== null && $margin >= 10 && $margin < 30;
    }

    /**
     * Determine if the profit margin is high (>= 30%).
     */
    public function hasHighMargin(): bool
    {
        $margin = $this->getProfitMargin();
        return $margin !== null && $margin >= 30;
    }

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope a query to only include inactive products.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    /**
     * Scope to get only service products.
     */
    public function scopeServices($query)
    {
        return $query->whereHas('productType', function ($q) {
            $q->where('is_service', true);
        });
    }

    /**
     * Scope to get only physical products (non-services).
     */
    public function scopePhysicalProducts($query)
    {
        return $query->whereHas('productType', function ($q) {
            $q->where('is_service', false);
        });
    }

    /**
     * Check if this product is a service.
     */
    public function isService(): bool
    {
        return $this->productType && $this->productType->isService();
    }

    /**
     * Check if this product requires stock tracking.
     * Services don't need stock tracking.
     */
    public function requiresStockTracking(): bool
    {
        return !$this->isService();
    }

    /**
     * Scope to find product by barcode.
     */
    public function scopeByBarcode($query, string $barcode)
    {
        return $query->where('barcode', $barcode);
    }

    /**
     * Find a product by barcode (including variants).
     * Returns product if found by product barcode, or product with variant if found by variant barcode.
     */
    public static function findByBarcode(string $barcode): ?array
    {
        // Try to find by product barcode first
        $product = self::where('barcode', $barcode)->first();
        if ($product) {
            return [
                'product' => $product,
                'variant' => null,
            ];
        }

        // Try to find by variant barcode
        $variant = ProductVariant::where('barcode', $barcode)->with('product')->first();
        if ($variant) {
            return [
                'product' => $variant->product,
                'variant' => $variant,
            ];
        }

        return null;
    }

    /**
     * Vérifie si ce produit a une limite de remise configurée.
     */
    public function hasDiscountLimit(): bool
    {
        return $this->max_discount_amount !== null && $this->max_discount_amount > 0;
    }

    /**
     * Obtient le montant maximum de remise autorisé pour ce produit.
     * Retourne null si pas de limite.
     */
    public function getMaxDiscountAmount(): ?float
    {
        if (!$this->hasDiscountLimit()) {
            return null;
        }

        // Le montant max de remise ne peut jamais dépasser le prix de vente
        return min((float) $this->max_discount_amount, (float) $this->price);
    }

    /**
     * Obtient le prix minimum de vente (prix - remise max autorisée).
     */
    public function getMinSellingPrice(): float
    {
        $maxDiscount = $this->getMaxDiscountAmount();

        if ($maxDiscount === null) {
            // Pas de limite, le prix minimum est 0 (ou le prix d'achat si on veut éviter les pertes)
            return 0;
        }

        return max(0, (float) $this->price - $maxDiscount);
    }

    /**
     * Vérifie si une remise donnée est autorisée pour ce produit.
     *
     * @param float $discountAmount Montant de la remise à vérifier
     * @return bool True si la remise est autorisée
     */
    public function isDiscountAllowed(float $discountAmount): bool
    {
        // Si pas de limite configurée, toute remise est autorisée
        if (!$this->hasDiscountLimit()) {
            return true;
        }

        // La remise ne peut pas dépasser le montant maximum autorisé
        return $discountAmount <= $this->getMaxDiscountAmount();
    }

    /**
     * Calcule le montant de remise autorisé pour une quantité donnée.
     *
     * @param int $quantity Quantité de produits
     * @return float|null Montant maximum de remise pour cette quantité, null si pas de limite
     */
    public function getMaxDiscountForQuantity(int $quantity = 1): ?float
    {
        $maxPerUnit = $this->getMaxDiscountAmount();

        if ($maxPerUnit === null) {
            return null;
        }

        return $maxPerUnit * $quantity;
    }

    /**
     * Get the price history records for this product.
     */
    public function priceHistories(): HasMany
    {
        return $this->hasMany(PriceHistory::class)->orderBy('changed_at', 'desc');
    }

    /**
     * Get the latest price history entry.
     */
    public function latestPriceHistory()
    {
        return $this->hasOne(PriceHistory::class)->latestOfMany('changed_at');
    }

    /**
     * Get price at a specific date.
     *
     * @param \Carbon\Carbon|string $date
     * @param string $priceType
     * @return float|null
     */
    public function getPriceAt($date, string $priceType = 'price'): ?float
    {
        $history = $this->priceHistories()
            ->where('price_type', $priceType)
            ->where('changed_at', '<=', $date)
            ->first();

        return $history ? (float) $history->new_price : null;
    }
}
