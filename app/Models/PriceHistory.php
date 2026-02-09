<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class PriceHistory extends Model
{
    use HasFactory, BelongsToOrganization;

    /**
     * Price type constants.
     */
    const TYPE_PRICE = 'price';
    const TYPE_COST_PRICE = 'cost_price';
    const TYPE_ADDITIONAL_PRICE = 'additional_price';

    /**
     * Source constants.
     */
    const SOURCE_MANUAL = 'manual';
    const SOURCE_IMPORT = 'import';
    const SOURCE_API = 'api';
    const SOURCE_BULK_UPDATE = 'bulk_update';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'organization_id',
        'product_id',
        'product_variant_id',
        'user_id',
        'price_type',
        'old_price',
        'new_price',
        'price_difference',
        'percentage_change',
        'reason',
        'source',
        'metadata',
        'changed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'old_price' => 'decimal:2',
        'new_price' => 'decimal:2',
        'price_difference' => 'decimal:2',
        'percentage_change' => 'decimal:2',
        'metadata' => 'array',
        'changed_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($history) {
            // Auto-calculate price difference
            if ($history->old_price !== null && $history->new_price !== null) {
                $history->price_difference = $history->new_price - $history->old_price;
                
                // Calculate percentage change
                if ($history->old_price > 0) {
                    $history->percentage_change = round(
                        (($history->new_price - $history->old_price) / $history->old_price) * 100,
                        2
                    );
                }
            }

            // Set changed_at if not provided
            if (!$history->changed_at) {
                $history->changed_at = now();
            }

            // Set user_id from auth if not provided
            if (!$history->user_id && auth()->check()) {
                $history->user_id = auth()->id();
            }

            // Set organization_id from product if not provided
            if (!$history->organization_id && $history->product_id) {
                $product = Product::find($history->product_id);
                if ($product) {
                    $history->organization_id = $product->organization_id;
                }
            }
        });
    }

    /**
     * Get the product that this history belongs to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the product variant that this history belongs to.
     */
    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    /**
     * Get the user who made the change.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to filter by price type.
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('price_type', $type);
    }

    /**
     * Scope to filter by product.
     */
    public function scopeForProduct(Builder $query, int $productId): Builder
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Scope to filter by variant.
     */
    public function scopeForVariant(Builder $query, int $variantId): Builder
    {
        return $query->where('product_variant_id', $variantId);
    }

    /**
     * Scope to filter by date range.
     */
    public function scopeBetweenDates(Builder $query, $startDate, $endDate): Builder
    {
        return $query->whereBetween('changed_at', [$startDate, $endDate]);
    }

    /**
     * Scope for increases only.
     */
    public function scopeIncreases(Builder $query): Builder
    {
        return $query->where('price_difference', '>', 0);
    }

    /**
     * Scope for decreases only.
     */
    public function scopeDecreases(Builder $query): Builder
    {
        return $query->where('price_difference', '<', 0);
    }

    /**
     * Get human-readable price type label.
     */
    public function getPriceTypeLabelAttribute(): string
    {
        return match ($this->price_type) {
            self::TYPE_PRICE => 'Prix de vente',
            self::TYPE_COST_PRICE => 'Prix d\'achat',
            self::TYPE_ADDITIONAL_PRICE => 'Supplément variante',
            default => $this->price_type,
        };
    }

    /**
     * Get formatted price change indicator.
     */
    public function getChangeIndicatorAttribute(): string
    {
        if ($this->price_difference > 0) {
            return '+' . number_format($this->price_difference, 2);
        }
        return number_format($this->price_difference, 2);
    }

    /**
     * Check if this was a price increase.
     */
    public function isIncrease(): bool
    {
        return $this->price_difference > 0;
    }

    /**
     * Check if this was a price decrease.
     */
    public function isDecrease(): bool
    {
        return $this->price_difference < 0;
    }

    /**
     * Record a price change for a product.
     *
     * @param Product $product
     * @param string $priceType
     * @param float|null $oldPrice
     * @param float $newPrice
     * @param string|null $reason
     * @param string $source
     * @param array|null $metadata
     * @return static
     */
    public static function recordProductChange(
        Product $product,
        string $priceType,
        ?float $oldPrice,
        float $newPrice,
        ?string $reason = null,
        string $source = self::SOURCE_MANUAL,
        ?array $metadata = null
    ): self {
        return self::create([
            'organization_id' => $product->organization_id,
            'product_id' => $product->id,
            'product_variant_id' => null,
            'price_type' => $priceType,
            'old_price' => $oldPrice,
            'new_price' => $newPrice,
            'reason' => $reason,
            'source' => $source,
            'metadata' => $metadata,
        ]);
    }

    /**
     * Record a price change for a variant.
     *
     * @param ProductVariant $variant
     * @param string $priceType
     * @param float|null $oldPrice
     * @param float $newPrice
     * @param string|null $reason
     * @param string $source
     * @param array|null $metadata
     * @return static
     */
    public static function recordVariantChange(
        ProductVariant $variant,
        string $priceType,
        ?float $oldPrice,
        float $newPrice,
        ?string $reason = null,
        string $source = self::SOURCE_MANUAL,
        ?array $metadata = null
    ): self {
        return self::create([
            'organization_id' => $variant->organization_id,
            'product_id' => $variant->product_id,
            'product_variant_id' => $variant->id,
            'price_type' => $priceType,
            'old_price' => $oldPrice,
            'new_price' => $newPrice,
            'reason' => $reason,
            'source' => $source,
            'metadata' => $metadata,
        ]);
    }
}
