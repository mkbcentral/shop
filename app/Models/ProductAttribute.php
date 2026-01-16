<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductAttribute extends Model
{
    protected $fillable = [
        'product_type_id',
        'name',
        'code',
        'type',
        'options',
        'unit',
        'default_value',
        'is_required',
        'is_variant_attribute',
        'is_filterable',
        'is_visible',
        'display_order',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'is_variant_attribute' => 'boolean',
        'is_filterable' => 'boolean',
        'is_visible' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Get the product type that owns this attribute
     */
    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    /**
     * Get all values for this attribute
     */
    public function values(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    /**
     * Scope to get only variant attributes
     */
    public function scopeVariant($query)
    {
        return $query->where('is_variant_attribute', true);
    }

    /**
     * Scope to get only filterable attributes
     */
    public function scopeFilterable($query)
    {
        return $query->where('is_filterable', true);
    }

    /**
     * Scope to get only visible attributes
     */
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    /**
     * Scope to order by display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }

    /**
     * Check if this attribute has predefined options (select type)
     */
    public function hasOptions(): bool
    {
        return $this->type === 'select' && !empty($this->options);
    }

    /**
     * Get formatted label with unit if available
     */
    public function getFormattedLabelAttribute(): string
    {
        return $this->unit ? "{$this->name} ({$this->unit})" : $this->name;
    }
}
