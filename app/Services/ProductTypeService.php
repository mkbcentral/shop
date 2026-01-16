<?php

namespace App\Services;

use App\Models\ProductType;
use App\Repositories\ProductTypeRepository;
use App\Repositories\ProductAttributeRepository;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductTypeService
{
    public function __construct(
        protected ProductTypeRepository $productTypeRepository,
        protected ProductAttributeRepository $productAttributeRepository
    ) {}

    /**
     * Get all product types
     */
    public function getAllProductTypes()
    {
        return $this->productTypeRepository->all();
    }

    /**
     * Get all active product types
     */
    public function getActiveProductTypes()
    {
        return $this->productTypeRepository->allActive();
    }

    /**
     * Get product types with counts for index page
     */
    public function getProductTypesWithCounts()
    {
        return $this->productTypeRepository->all()->load(['attributes', 'products']);
    }

    /**
     * Get product type by ID
     */
    public function getProductTypeById(int $id): ?ProductType
    {
        return $this->productTypeRepository->findById($id);
    }

    /**
     * Create a new product type with its attributes
     */
    public function createProductType(array $data): ProductType
    {
        DB::beginTransaction();

        try {
            // Generate slug if not provided
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            }

            // Extract attributes data
            $attributesData = $data['attributes'] ?? [];
            unset($data['attributes']);

            // Create product type
            $productType = $this->productTypeRepository->create($data);

            // Create attributes if provided
            if (!empty($attributesData)) {
                $this->productAttributeRepository->bulkCreate($productType->id, $attributesData);
            }

            DB::commit();

            return $productType->fresh(['attributes']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update a product type
     */
    public function updateProductType(int $id, array $data): ProductType
    {
        $productType = $this->productTypeRepository->findById($id);

        if (!$productType) {
            throw new \Exception("Product type not found");
        }

        // Generate slug if name changed
        if (isset($data['name']) && empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $this->productTypeRepository->update($productType, $data);

        return $productType->fresh();
    }

    /**
     * Delete a product type
     */
    public function deleteProductType(int $id): bool
    {
        $productType = $this->productTypeRepository->findById($id);

        if (!$productType) {
            throw new \Exception("Product type not found");
        }

        // Check if it can be deleted
        if ($productType->products()->exists()) {
            throw new \Exception("Cannot delete product type with existing products");
        }

        if ($productType->categories()->exists()) {
            throw new \Exception("Cannot delete product type with existing categories");
        }

        return $this->productTypeRepository->delete($productType);
    }

    /**
     * Toggle active status
     */
    public function toggleActiveStatus(int $id): bool
    {
        $productType = $this->productTypeRepository->findById($id);

        if (!$productType) {
            throw new \Exception("Product type not found");
        }

        return $this->productTypeRepository->toggleActive($productType);
    }

    /**
     * Get product types with statistics
     */
    public function getProductTypesWithStats()
    {
        return $this->productTypeRepository->all()->map(function ($productType) {
            return [
                'id' => $productType->id,
                'name' => $productType->name,
                'slug' => $productType->slug,
                'icon' => $productType->icon,
                'is_active' => $productType->is_active,
                'has_variants' => $productType->has_variants,
                'attributes_count' => $productType->attributes()->count(),
                'variant_attributes_count' => $productType->variantAttributes()->count(),
                'products_count' => $productType->products()->count(),
                'categories_count' => $productType->categories()->count(),
            ];
        });
    }

    /**
     * Add attribute to product type
     */
    public function addAttribute(int $productTypeId, array $attributeData): void
    {
        $attributeData['product_type_id'] = $productTypeId;

        // Generate code if not provided
        if (empty($attributeData['code'])) {
            $attributeData['code'] = Str::slug($attributeData['name'], '_');
        }

        $this->productAttributeRepository->create($attributeData);
    }

    /**
     * Update attribute
     */
    public function updateAttribute(int $attributeId, array $data): bool
    {
        $attribute = $this->productAttributeRepository->findById($attributeId);

        if (!$attribute) {
            throw new \Exception("Attribute not found");
        }

        return $this->productAttributeRepository->update($attribute, $data);
    }

    /**
     * Delete attribute
     */
    public function deleteAttribute(int $attributeId): bool
    {
        $attribute = $this->productAttributeRepository->findById($attributeId);

        if (!$attribute) {
            throw new \Exception("Attribute not found");
        }

        if ($attribute->values()->exists()) {
            throw new \Exception("Cannot delete attribute with existing values");
        }

        return $this->productAttributeRepository->delete($attribute);
    }

    /**
     * Reorder attributes
     */
    public function reorderAttributes(int $productTypeId, array $orderData): void
    {
        $this->productAttributeRepository->reorder($productTypeId, $orderData);
    }
}
