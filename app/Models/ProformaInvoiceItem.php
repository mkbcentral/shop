<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProformaInvoiceItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'proforma_invoice_id',
        'product_variant_id',
        'description',
        'quantity',
        'unit_price',
        'discount',
        'tax_rate',
        'total',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            // Auto-calculate total
            $item->total = ($item->quantity * $item->unit_price) - $item->discount;
        });

        static::saved(function ($item) {
            // Recalculate proforma totals
            $item->proformaInvoice->calculateTotals();
        });

        static::deleted(function ($item) {
            // Recalculate proforma totals
            if ($item->proformaInvoice) {
                $item->proformaInvoice->calculateTotals();
            }
        });
    }

    /**
     * Relationships
     */
    public function proformaInvoice(): BelongsTo
    {
        return $this->belongsTo(ProformaInvoice::class);
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    /**
     * Get item name (from product or description).
     */
    public function getNameAttribute(): string
    {
        if ($this->productVariant) {
            return $this->productVariant->full_name;
        }

        return $this->description ?? 'Article';
    }
}
