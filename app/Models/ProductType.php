<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductType extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'description',
        'has_variants',
        'has_expiry_date',
        'has_weight',
        'has_dimensions',
        'has_serial_number',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'has_variants' => 'boolean',
        'has_expiry_date' => 'boolean',
        'has_weight' => 'boolean',
        'has_dimensions' => 'boolean',
        'has_serial_number' => 'boolean',
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Get all attributes for this product type
     */
    public function attributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class)->orderBy('display_order');
    }

    /**
     * Get only variant attributes (used to generate product variants)
     */
    public function variantAttributes(): HasMany
    {
        return $this->attributes()->where('is_variant_attribute', true);
    }

    /**
     * Get only filterable attributes
     */
    public function filterableAttributes(): HasMany
    {
        return $this->attributes()->where('is_filterable', true);
    }

    /**
     * Get categories of this product type
     */
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Get products of this product type
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scope to get only active product types
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }
}
