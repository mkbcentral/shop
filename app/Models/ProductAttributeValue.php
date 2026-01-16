<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAttributeValue extends Model
{
    protected $fillable = [
        'product_attribute_id',
        'product_variant_id',
        'value',
    ];

    /**
     * Get the product attribute that owns this value
     */
    public function productAttribute(): BelongsTo
    {
        return $this->belongsTo(ProductAttribute::class);
    }

    /**
     * Alias for productAttribute relation (for backward compatibility)
     */
    public function attribute(): BelongsTo
    {
        return $this->productAttribute();
    }

    /**
     * Get the product variant that owns this value
     */
    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    /**
     * Get formatted value based on attribute type
     */
    public function getFormattedValueAttribute(): string
    {
        $attribute = $this->productAttribute;

        if (!$attribute) {
            return $this->value;
        }

        return match($attribute->type) {
            'boolean' => $this->value ? 'Oui' : 'Non',
            'number' => $attribute->unit ? "{$this->value} {$attribute->unit}" : $this->value,
            'date' => \Carbon\Carbon::parse($this->value)->format('d/m/Y'),
            default => $this->value,
        };
    }
}
