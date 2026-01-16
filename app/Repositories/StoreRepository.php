<?php

namespace App\Repositories;

use App\Models\Store;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class StoreRepository
{
    /**
     * Get all stores
     */
    public function all(): Collection
    {
        $query = Store::with('manager');

        // Filter by current organization
        $organizationId = session('current_organization_id') ?? auth()->user()?->default_organization_id;
        if ($organizationId) {
            $query->where('organization_id', $organizationId);
        }

        return $query->orderBy('name')->get();
    }

    /**
     * Get active stores
     */
    public function active(): Collection
    {
        $query = Store::active()->with('manager');

        // Filter by current organization
        $organizationId = session('current_organization_id') ?? auth()->user()?->default_organization_id;
        if ($organizationId) {
            $query->where('organization_id', $organizationId);
        }

        return $query->orderBy('name')->get();
    }

    /**
     * Get stores with pagination
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        $query = Store::with('manager')
            ->withCount(['products', 'sales', 'stock']);

        // Filter by current organization
        $organizationId = session('current_organization_id') ?? auth()->user()?->default_organization_id;
        if ($organizationId) {
            $query->where('organization_id', $organizationId);
        }

        return $query->orderBy('is_main', 'desc')
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Get stores with search, sort and pagination
     */
    public function getAllWithFilters(
        ?string $search = null,
        string $sortBy = 'name',
        string $sortDirection = 'asc',
        int $perPage = 10
    ): LengthAwarePaginator {
        $query = Store::query()
            ->with('manager')
            ->withCount(['products', 'sales', 'stock']);

        // Filter by current organization
        $organizationId = session('current_organization_id') ?? auth()->user()?->default_organization_id;
        if ($organizationId) {
            $query->where('organization_id', $organizationId);
        }

        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        // Sorting
        $query->orderBy($sortBy, $sortDirection);

        // Always prioritize main store
        if ($sortBy !== 'is_main') {
            $query->orderBy('is_main', 'desc');
        }

        return $query->paginate($perPage);
    }

    /**
     * Find store by ID
     */
    public function find(int $id): ?Store
    {
        return Store::with(['manager', 'users'])->find($id);
    }

    /**
     * Find store by code
     */
    public function findByCode(string $code): ?Store
    {
        return Store::where('code', $code)->first();
    }

    /**
     * Get main store
     */
    public function getMainStore(): ?Store
    {
        return Store::mainStore();
    }

    /**
     * Create a new store
     */
    public function create(array $data): Store
    {
        return Store::create($data);
    }

    /**
     * Update a store
     */
    public function update(int $id, array $data): bool
    {
        return Store::where('id', $id)->update($data);
    }

    /**
     * Delete a store
     */
    public function delete(int $id): bool
    {
        return Store::destroy($id) > 0;
    }

    /**
     * Get stores for a user
     */
    public function getStoresForUser(int $userId): Collection
    {
        $query = Store::whereHas('users', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->with('manager');

        // Filter by current organization
        $organizationId = session('current_organization_id') ?? auth()->user()?->default_organization_id;
        if ($organizationId) {
            $query->where('organization_id', $organizationId);
        }

        return $query->get();
    }

    /**
     * Assign user to store
     */
    public function assignUser(int $storeId, int $userId, string $role = 'staff', bool $isDefault = false): void
    {
        $store = $this->find($storeId);

        $store->users()->syncWithoutDetaching([
            $userId => [
                'role' => $role,
                'is_default' => $isDefault
            ]
        ]);
    }

    /**
     * Remove user from store
     */
    public function removeUser(int $storeId, int $userId): void
    {
        $store = $this->find($storeId);
        $store->users()->detach($userId);
    }

    /**
     * Check if code exists
     */
    public function codeExists(string $code, ?int $exceptId = null): bool
    {
        $query = Store::where('code', $code);

        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }

        return $query->exists();
    }

    /**
     * Generate next store code
     */
    public function generateNextCode(?int $organizationId = null): string
    {
        // Get organization prefix
        $organization = $organizationId
            ? \App\Models\Organization::find($organizationId)
            : auth()->user()->organization;

        $prefix = $organization ? strtoupper(substr($organization->name, 0, 3)) : 'MAG';

        // Get the last store for this organization
        $query = Store::orderBy('id', 'desc');

        if ($organizationId) {
            $query->where('organization_id', $organizationId);
        } elseif ($organization) {
            $query->where('organization_id', $organization->id);
        }

        $lastStore = $query->first();

        // Extract number from last code or start at 1
        $nextNumber = 1;
        if ($lastStore && $lastStore->code) {
            // Extract number after the last dash
            $parts = explode('-', $lastStore->code);
            if (count($parts) > 1) {
                $nextNumber = ((int) end($parts)) + 1;
            }
        }

        return $prefix . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get store statistics
     */
    public function getStatistics(int $storeId): array
    {
        $store = $this->find($storeId);

        return [
            'total_products' => $store->products()->count(),
            'total_sales' => $store->sales()->count(),
            'total_sales_amount' => $store->sales()->sum('total'),
            'total_stock_value' => $store->getTotalStockValue(),
            'low_stock_count' => $store->getLowStockCount(),
            'out_of_stock_count' => $store->getOutOfStockCount(),
        ];
    }
}
