<?php

namespace App\Repositories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

class SupplierRepository
{
    /**
     * Count all suppliers.
     */
    public function count(): int
    {
        return Supplier::count();
    }

    /**
     * Get a new query builder for Supplier.
     */
    public function query(): Builder
    {
        return Supplier::query();
    }

    /**
     * Get all suppliers.
     */
    public function all(): Collection
    {
        return Supplier::orderBy('name')->get();
    }

    /**
     * Find supplier by ID.
     */
    public function find(int $id): ?Supplier
    {
        return Supplier::find($id);
    }

    /**
     * Create a new supplier.
     */
    public function create(array $data): Supplier
    {
        return Supplier::create($data);
    }

    /**
     * Update a supplier.
     */
    public function update(Supplier $supplier, array $data): bool
    {
        return $supplier->update($data);
    }

    /**
     * Delete a supplier.
     */
    public function delete(Supplier $supplier): bool
    {
        return $supplier->delete();
    }

    /**
     * Search suppliers.
     */
    public function search(string $query): Collection
    {
        return Supplier::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orderBy('name')
            ->get();
    }

    /**
     * Get suppliers with purchases.
     */
    public function withPurchases(): Collection
    {
        return Supplier::with('purchases')->orderBy('name')->get();
    }
}
