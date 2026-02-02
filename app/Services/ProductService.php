<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Repositories\ProductRepository;
use App\Services\Product\ProductCreationService;
use App\Services\Product\ProductUpdateService;
use App\Services\Product\ProductVariantService;
use App\Traits\ResolvesStoreContext;

/**
 * Main Product Service - Facade pattern
 * 
 * This service acts as the main entry point for product operations
 * and delegates to specialized services for better code organization.
 * 
 * @deprecated Consider using specialized services directly:
 * - ProductCreationService for creating products
 * - ProductUpdateService for updating products
 * - ProductVariantService for variant operations
 */
class ProductService
{
    use ResolvesStoreContext;

    public function __construct(
        private ProductRepository $productRepository,
        private ProductCreationService $creationService,
        private ProductUpdateService $updateService,
        private ProductVariantService $variantService
    ) {}

    /**
     * Create a product with variants
     * Delegates to ProductCreationService
     */
    public function createProduct(array $data): Product
    {
        return $this->creationService->createProduct($data);
    }

    /**
     * Update a product
     * Delegates to ProductUpdateService
     */
    public function updateProduct(int $productId, array $data): Product
    {
        return $this->updateService->updateProduct($productId, $data);
    }

    /**
     * Delete a product
     */
    public function deleteProduct(int $productId): bool
    {
        $product = $this->productRepository->find($productId);

        if (!$product) {
            throw new \Exception("Product not found");
        }

        // Check if product has sales
        $hasSales = $product->variants()
            ->whereHas('saleItems')
            ->exists();

        if ($hasSales) {
            throw new \Exception(
                "Cannot delete product with existing sales. " .
                "Please archive it instead by setting status to 'inactive'."
            );
        }

        // Delete all variants first
        $product->variants()->delete();

        return $this->productRepository->delete($product);
    }

    /**
     * Create a product variant
     * Delegates to ProductVariantService
     */
    public function createVariant(int $productId, array $data): ProductVariant
    {
        return $this->variantService->createVariant($productId, $data);
    }

    /**
     * Update a product variant
     * Delegates to ProductVariantService
     */
    public function updateVariant(int $variantId, array $data): ProductVariant
    {
        return $this->variantService->updateVariant($variantId, $data);
    }

    /**
     * Delete a product variant
     * Delegates to ProductVariantService
     */
    public function deleteVariant(int $variantId): bool
    {
        return $this->variantService->deleteVariant($variantId);
    }

    /**
     * Create a default variant for a product
     * Delegates to ProductVariantService
     */
    public function createDefaultVariant(Product $product): ProductVariant
    {
        return $this->variantService->createDefaultVariant($product);
    }

    /**
     * Toggle product status (active/inactive)
     * Delegates to ProductUpdateService
     */
    public function toggleStatus(int $productId): Product
    {
        return $this->updateService->toggleStatus($productId);
    }

    /**
     * Update product pricing
     * Delegates to ProductUpdateService
     */
    public function updatePricing(int $productId, array $pricingData): Product
    {
        return $this->updateService->updatePricing($productId, $pricingData);
    }

    /**
     * Bulk update products
     * Delegates to ProductUpdateService
     */
    public function bulkUpdate(array $updates): array
    {
        return $this->updateService->bulkUpdate($updates);
    }

    /**
     * Ensure all products have at least one variant
     * Useful for data migration
     */
    public function ensureAllProductsHaveVariants(): int
    {
        $productsWithoutVariants = Product::doesntHave('variants')->get();
        $count = 0;

        foreach ($productsWithoutVariants as $product) {
            $this->variantService->createDefaultVariant($product);
            $count++;
        }

        return $count;
    }

    /**
     * Get low stock products
     */
    public function getLowStockProducts(int $threshold = 10)
    {
        return $this->productRepository->lowStock($threshold);
    }

    /**
     * Search products
     */
    public function searchProducts(string $query)
    {
        return $this->productRepository->search($query);
    }
}

