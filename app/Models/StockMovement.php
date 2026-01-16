<?php

namespace App\Models;

use App\Traits\HasStoreScope;
use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory, HasStoreScope, BelongsToOrganization;

    /**
     * Movement type constants.
     */
    const TYPE_IN = 'in';
    const TYPE_OUT = 'out';

    /**
     * Movement type constants.
     */
    const MOVEMENT_PURCHASE = 'purchase';
    const MOVEMENT_SALE = 'sale';
    const MOVEMENT_ADJUSTMENT = 'adjustment';
    const MOVEMENT_TRANSFER = 'transfer';
    const MOVEMENT_RETURN = 'return';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'organization_id',
        'store_id',
        'product_variant_id',
        'type',
        'movement_type',
        'quantity',
        'reference',
        'reason',
        'unit_price',
        'total_price',
        'date',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'date' => 'date',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically calculate total_price if not provided
        static::creating(function ($movement) {
            if (!$movement->total_price && $movement->unit_price && $movement->quantity) {
                $movement->total_price = $movement->unit_price * $movement->quantity;
            }

            // Update store stock quantity when movement is created
            if ($movement->store_id && $movement->product_variant_id) {
                $storeStock = StoreStock::firstOrCreate(
                    [
                        'store_id' => $movement->store_id,
                        'product_variant_id' => $movement->product_variant_id,
                    ],
                    [
                        'quantity' => 0,
                        'low_stock_threshold' => 10,
                        'min_stock_threshold' => 0,
                    ]
                );

                if ($movement->type === self::TYPE_IN) {
                    $storeStock->increaseStock($movement->quantity);
                } elseif ($movement->type === self::TYPE_OUT) {
                    $storeStock->decreaseStock($movement->quantity);
                }
            }
        });
    }

    /**
     * Get the store that owns the movement.
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Get the product variant that owns the movement.
     */
    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    /**
     * Get the user who performed the movement.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include in movements.
     */
    public function scopeIn($query)
    {
        return $query->where('type', self::TYPE_IN);
    }

    /**
     * Scope a query to only include out movements.
     */
    public function scopeOut($query)
    {
        return $query->where('type', self::TYPE_OUT);
    }

    /**
     * Scope a query to filter by movement type.
     */
    public function scopeOfType($query, string $movementType)
    {
        return $query->where('movement_type', $movementType);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }
}
