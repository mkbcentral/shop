<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Service responsible for updating existing products
 * Handles product updates, attribute management, and validation
 */
class ProductUpdateService
{
    public function __construct(
        private ProductRepository $productRepository,
        private ProductVariantService $variantService
    ) {}

    /**
     * Update an existing product
     * 
     * @param int $productId
     * @param array $data Update data
     * @return Product Updated product
     * @throws \Exception If product not found or validation fails
     */
    public function updateProduct(int $productId, array $data): Product
    {
        $product = $this->productRepository->find($productId);

        if (!$product) {
            throw new \Exception("Product not found");
        }

        // Extract attributes and variants before updating
        $attributes = $data['attributes'] ?? [];
        unset($data['attributes'], $data['variants']);

        // Clean nullable fields
        $data = $this->normalizeProductData($data);

        // Update slug if name changed
        if (isset($data['name']) && $data['name'] !== $product->name && !isset($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $product->id);
        }

        // Validate uniqueness if reference or barcode changed
        if (isset($data['reference']) && $data['reference'] !== $product->reference) {
            $this->validateReferenceUniqueness($data['reference'], $product->id);
        }

        if (isset($data['barcode']) && !empty($data['barcode']) && $data['barcode'] !== $product->barcode) {
            $this->validateBarcodeUniqueness($data['barcode'], $product->id);
        }

        // Update the product
        $this->productRepository->update($product, $data);

        // Update attributes on default variant if provided
        if (!empty($attributes)) {
            $defaultVariant = $product->variants()->where('is_default', true)->first();
            if ($defaultVariant) {
                $this->variantService->saveAttributeValues(
                    $defaultVariant->id,
                    $attributes,
                    false
                );
            }
        }

        return $product->fresh('variants', 'productType.attributes');
    }

    /**
     * Bulk update products
     * 
     * @param array $updates Array of ['id' => productId, 'data' => updateData]
     * @return array Updated products
     */
    public function bulkUpdate(array $updates): array
    {
        $updatedProducts = [];

        foreach ($updates as $update) {
            try {
                $productId = $update['id'] ?? null;
                $data = $update['data'] ?? [];

                if (!$productId || empty($data)) {
                    continue;
                }

                $updatedProducts[] = $this->updateProduct($productId, $data);
            } catch (\Exception $e) {
                // Log error but continue with other updates
                Log::error("Failed to update product {$update['id']}: " . $e->getMessage());
            }
        }

        return $updatedProducts;
    }

    /**
     * Toggle product status (active/inactive)
     */
    public function toggleStatus(int $productId): Product
    {
        $product = $this->productRepository->find($productId);

        if (!$product) {
            throw new \Exception("Product not found");
        }

        $newStatus = $product->status === 'active' ? 'inactive' : 'active';
        
        $this->productRepository->update($product, ['status' => $newStatus]);

        return $product->fresh();
    }

    /**
     * Update product pricing
     */
    public function updatePricing(int $productId, array $pricingData): Product
    {
        $product = $this->productRepository->find($productId);

        if (!$product) {
            throw new \Exception("Product not found");
        }

        $allowedFields = ['price', 'cost_price', 'discount_price'];
        $data = array_intersect_key($pricingData, array_flip($allowedFields));

        $this->productRepository->update($product, $data);

        // Update default variant price if price changed
        if (isset($data['price'])) {
            $defaultVariant = $product->variants()->where('is_default', true)->first();
            if ($defaultVariant) {
                $defaultVariant->update(['price' => $data['price']]);
            }
        }

        return $product->fresh('variants');
    }

    /**
     * Clean and normalize product data
     */
    private function normalizeProductData(array $data): array
    {
        $nullableFields = ['product_type_id', 'category_id', 'cost_price'];
        
        foreach ($nullableFields as $field) {
            if (isset($data[$field]) && $data[$field] === '') {
                $data[$field] = null;
            }
        }

        return $data;
    }

    /**
     * Validate reference uniqueness
     */
    private function validateReferenceUniqueness(string $reference, ?int $excludeId = null): void
    {
        $query = Product::withoutGlobalScope('organization')
            ->where('reference', $reference);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        if ($query->exists()) {
            throw new \Exception(
                "La référence {$reference} existe déjà. Veuillez en choisir une autre."
            );
        }
    }

    /**
     * Validate barcode uniqueness
     */
    private function validateBarcodeUniqueness(string $barcode, ?int $excludeId = null): void
    {
        $query = Product::withoutGlobalScope('organization')
            ->where('barcode', $barcode);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        if ($query->exists()) {
            throw new \Exception(
                "Le code-barres {$barcode} existe déjà. Veuillez en générer un autre."
            );
        }
    }

    /**
     * Generate a unique slug for a product globally
     */
    private function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $query = Product::withoutGlobalScope('organization')
                ->where('slug', $slug);

            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            if (!$query->exists()) {
                break;
            }

            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
