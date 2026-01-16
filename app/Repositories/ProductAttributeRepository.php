<?php

namespace App\Repositories;

use App\Models\ProductAttribute;
use App\Models\ProductType;
use Illuminate\Database\Eloquent\Collection;

class ProductAttributeRepository
{
    /**
     * Get all attributes for a product type
     */
    public function getByProductType(int $productTypeId): Collection
    {
        return ProductAttribute::where('product_type_id', $productTypeId)
            ->orderBy('display_order')
            ->get();
    }

    /**
     * Get variant attributes for a product type
     */
    public function getVariantAttributes(int $productTypeId): Collection
    {
        return ProductAttribute::where('product_type_id', $productTypeId)
            ->where('is_variant_attribute', true)
            ->orderBy('display_order')
            ->get();
    }

    /**
     * Get filterable attributes for a product type
     */
    public function getFilterableAttributes(int $productTypeId): Collection
    {
        return ProductAttribute::where('product_type_id', $productTypeId)
            ->where('is_filterable', true)
            ->orderBy('display_order')
            ->get();
    }

    /**
     * Get visible attributes for a product type
     */
    public function getVisibleAttributes(int $productTypeId): Collection
    {
        return ProductAttribute::where('product_type_id', $productTypeId)
            ->where('is_visible', true)
            ->orderBy('display_order')
            ->get();
    }

    /**
     * Get attribute by ID
     */
    public function findById(int $id): ?ProductAttribute
    {
        return ProductAttribute::with(['productType', 'values'])->find($id);
    }

    /**
     * Get attribute by code for a product type
     */
    public function findByCode(int $productTypeId, string $code): ?ProductAttribute
    {
        return ProductAttribute::where('product_type_id', $productTypeId)
            ->where('code', $code)
            ->first();
    }

    /**
     * Create a new attribute
     */
    public function create(array $data): ProductAttribute
    {
        return ProductAttribute::create($data);
    }

    /**
     * Update an attribute
     */
    public function update(ProductAttribute $attribute, array $data): bool
    {
        return $attribute->update($data);
    }

    /**
     * Delete an attribute
     */
    public function delete(ProductAttribute $attribute): bool
    {
        // Check if it has values
        if ($attribute->values()->exists()) {
            return false;
        }

        return $attribute->delete();
    }

    /**
     * Reorder attributes for a product type
     */
    public function reorder(int $productTypeId, array $orderData): void
    {
        foreach ($orderData as $id => $order) {
            ProductAttribute::where('id', $id)
                ->where('product_type_id', $productTypeId)
                ->update(['display_order' => $order]);
        }
    }

    /**
     * Duplicate attribute to another product type
     */
    public function duplicate(ProductAttribute $attribute, int $newProductTypeId): ProductAttribute
    {
        $data = $attribute->toArray();
        unset($data['id'], $data['created_at'], $data['updated_at']);
        $data['product_type_id'] = $newProductTypeId;

        return $this->create($data);
    }

    /**
     * Get attributes with values count
     */
    public function withValuesCount(int $productTypeId): Collection
    {
        return ProductAttribute::where('product_type_id', $productTypeId)
            ->withCount('values')
            ->orderBy('display_order')
            ->get();
    }

    /**
     * Bulk create attributes
     */
    public function bulkCreate(int $productTypeId, array $attributesData): Collection
    {
        $attributes = [];

        foreach ($attributesData as $data) {
            $data['product_type_id'] = $productTypeId;
            $attributes[] = $this->create($data);
        }

        return collect($attributes);
    }
}
