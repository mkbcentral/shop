<?php

namespace App\Contracts\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface for Product Repository
 * 
 * Defines the contract for product data access operations
 */
interface ProductRepositoryInterface
{
    /**
     * Find a product by ID
     */
    public function find(int $id): ?Product;

    /**
     * Find a product by ID or fail
     * 
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): Product;

    /**
     * Create a new product
     */
    public function create(array $data): Product;

    /**
     * Update a product
     */
    public function update(Product $product, array $data): Product;

    /**
     * Delete a product
     */
    public function delete(Product $product): bool;

    /**
     * Get all products for a store
     */
    public function getByStore(int $storeId, array $filters = []): Collection;

    /**
     * Find product by reference
     */
    public function findByReference(string $reference): ?Product;

    /**
     * Find product by slug
     */
    public function findBySlug(string $slug): ?Product;

    /**
     * Check if reference exists
     */
    public function referenceExists(string $reference, ?int $excludeId = null): bool;

    /**
     * Check if slug exists
     */
    public function slugExists(string $slug, ?int $excludeId = null): bool;

    /**
     * Get products by category
     */
    public function getByCategory(int $categoryId): Collection;

    /**
     * Get products by product type
     */
    public function getByProductType(int $productTypeId): Collection;

    /**
     * Get low stock products
     */
    public function getLowStock(int $threshold = 10): Collection;

    /**
     * Get out of stock products
     */
    public function getOutOfStock(): Collection;

    /**
     * Search products by query
     */
    public function search(string $query): Collection;

    /**
     * Get active products
     */
    public function getActive(int $storeId): Collection;

    /**
     * Bulk update products
     */
    public function bulkUpdate(array $updates): int;
}
