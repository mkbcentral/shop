<?php

namespace App\Contracts\Repositories;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface for Sale Repository
 * 
 * Defines the contract for sale data access operations
 */
interface SaleRepositoryInterface
{
    /**
     * Find a sale by ID
     */
    public function find(int $id): ?Sale;

    /**
     * Find a sale by ID or fail
     * 
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): Sale;

    /**
     * Create a new sale
     */
    public function create(array $data): Sale;

    /**
     * Update a sale
     */
    public function update(Sale $sale, array $data): Sale;

    /**
     * Delete a sale
     */
    public function delete(Sale $sale): bool;

    /**
     * Get all sales for a store
     */
    public function getByStore(int $storeId, array $filters = []): Collection;

    /**
     * Get sales for today
     */
    public function today(): Collection;

    /**
     * Get sales statistics for a date range
     */
    public function statistics(string $startDate, string $endDate): array;

    /**
     * Find sale by sale number
     */
    public function findBySaleNumber(string $saleNumber): ?Sale;

    /**
     * Get pending sales
     */
    public function getPending(int $storeId): Collection;

    /**
     * Get completed sales
     */
    public function getCompleted(int $storeId, ?string $startDate = null, ?string $endDate = null): Collection;

    /**
     * Get sales by client
     */
    public function getByClient(int $clientId): Collection;

    /**
     * Get sales by user
     */
    public function getByUser(int $userId): Collection;

    /**
     * Get sales with unpaid balance
     */
    public function getWithBalance(int $storeId): Collection;
}
