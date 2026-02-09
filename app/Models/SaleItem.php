<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sale_id',
        'product_variant_id',
        'variant_details',
        'quantity',
        'unit_price',
        'discount',
        'subtotal',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically calculate subtotal if not provided
        static::creating(function ($item) {
            if (!$item->subtotal) {
                $item->subtotal = ($item->unit_price * $item->quantity) - $item->discount;
            }
        });

        // Create stock movement when sale item is created
        static::created(function ($item) {
            // Load the sale relationship if not already loaded
            $sale = $item->sale ?? Sale::find($item->sale_id);

            if ($sale && $sale->status === Sale::STATUS_COMPLETED) {
                $item->setRelation('sale', $sale);
                $item->createStockMovement();
            }
        });
    }

    /**
     * Get the sale that owns the item.
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Get the product variant for this item.
     */
    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    /**
     * Create a stock movement for this sale item.
     * Skipped for service products as they don't track inventory.
     */
    public function createStockMovement(): void
    {
        // Skip stock movement for services
        $variant = $this->productVariant ?? ProductVariant::find($this->product_variant_id);
        if ($variant && $variant->isService()) {
            return;
        }

        StockMovement::create([
            'store_id' => $this->sale->store_id,
            'product_variant_id' => $this->product_variant_id,
            'type' => StockMovement::TYPE_OUT,
            'movement_type' => StockMovement::MOVEMENT_SALE,
            'quantity' => $this->quantity,
            'reference' => $this->sale->sale_number,
            'unit_price' => $this->unit_price,
            'total_price' => $this->subtotal,
            'date' => $this->sale->sale_date,
            'user_id' => $this->sale->user_id,
        ]);
    }

    /**
     * Check if this sale item is for a service.
     */
    public function isService(): bool
    {
        $variant = $this->productVariant ?? ProductVariant::find($this->product_variant_id);
        return $variant && $variant->isService();
    }

    /**
     * Get the total price before discount.
     */
    public function getTotalBeforeDiscountAttribute(): float
    {
        return $this->unit_price * $this->quantity;
    }

    /**
     * Recalculate the subtotal.
     */
    public function recalculateSubtotal(): void
    {
        $this->subtotal = ($this->unit_price * $this->quantity) - $this->discount;
        $this->save();
    }
}
