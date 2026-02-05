<?php

namespace App\Services;

use App\Models\Store;
use App\Models\StoreStock;
use App\Models\User;
use App\Repositories\StoreRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class StoreService
{
    public function __construct(
        private StoreRepository $storeRepository
    ) {}

    /**
     * Get all stores
     */
    public function getAllStores(
        ?string $search = null,
        string $sortBy = 'name',
        string $sortDirection = 'asc',
        int $perPage = 10
    ) {
        return $this->storeRepository->getAllWithFilters($search, $sortBy, $sortDirection, $perPage);
    }

    /**
     * Get active stores
     */
    public function getActiveStores(): Collection
    {
        return $this->storeRepository->active();
    }

    /**
     * Get stores for a user
     */
    public function getStoresForUser(int $userId): Collection
    {
        return $this->storeRepository->getStoresForUser($userId);
    }

    /**
     * Find store by ID
     */
    public function findStore(int $id): ?Store
    {
        return $this->storeRepository->find($id);
    }

    /**
     * Create a new store
     */
    public function createStore(array $data): Store
    {
        // Vérifier la limite de magasins du plan
        $this->checkStoreLimit();

        DB::beginTransaction();

        try {
            // Generate code if not provided
            if (!isset($data['code'])) {
                $data['code'] = $this->storeRepository->generateNextCode();
            }

            // Generate slug from name
            if (!isset($data['slug'])) {
                $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
                // Ensure uniqueness
                $baseSlug = $data['slug'];
                $counter = 1;
                while (Store::where('slug', $data['slug'])->exists()) {
                    $data['slug'] = $baseSlug . '-' . $counter;
                    $counter++;
                }
            }

            // Create the store
            $store = $this->storeRepository->create($data);

            // If this is set as main store, update others
            if ($data['is_main'] ?? false) {
                Store::where('id', '!=', $store->id)->update(['is_main' => false]);
            }

            // Assign manager if specified
            if (isset($data['manager_id'])) {
                $this->assignUserToStore($store->id, $data['manager_id'], 'manager', true);
            }

            DB::commit();

            return $store;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update a store
     */
    public function updateStore(int $id, array $data): Store
    {
        DB::beginTransaction();

        try {
            // If setting as main store, update others first
            if (isset($data['is_main']) && $data['is_main']) {
                Store::where('id', '!=', $id)->update(['is_main' => false]);
            }

            // Regenerate slug if name changed
            if (isset($data['name']) && !isset($data['slug'])) {
                $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
                // Ensure uniqueness
                $baseSlug = $data['slug'];
                $counter = 1;
                while (Store::where('slug', $data['slug'])->where('id', '!=', $id)->exists()) {
                    $data['slug'] = $baseSlug . '-' . $counter;
                    $counter++;
                }
            }

            $this->storeRepository->update($id, $data);

            // Update manager assignment if changed
            if (isset($data['manager_id'])) {
                $store = $this->findStore($id);
                $this->assignUserToStore($id, $data['manager_id'], 'manager', true);
            }

            DB::commit();

            return $this->findStore($id);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete a store
     */
    public function deleteStore(int $id): bool
    {
        $store = $this->findStore($id);

        // Cannot delete main store
        if ($store->isMain()) {
            throw new \Exception('Cannot delete the main store');
        }

        // Check if store has data
        if ($store->products()->exists() || $store->sales()->exists()) {
            throw new \Exception('Cannot delete store with existing products or sales');
        }

        return $this->storeRepository->delete($id);
    }

    /**
     * Assign user to store
     */
    public function assignUserToStore(int $storeId, int $userId, string $role = 'staff', bool $isDefault = false): void
    {
        $this->storeRepository->assignUser($storeId, $userId, $role, $isDefault);

        // If this is set as default, update user's current store
        if ($isDefault) {
            User::where('id', $userId)->update(['current_store_id' => $storeId]);
        }
    }

    /**
     * Remove user from store
     */
    public function removeUserFromStore(int $storeId, int $userId): void
    {
        $this->storeRepository->removeUser($storeId, $userId);

        // If this was the user's current store, set to another store or null
        $user = User::find($userId);
        if ($user && $user->current_store_id === $storeId) {
            $otherStore = $this->getStoresForUser($userId)->first();
            $user->update(['current_store_id' => $otherStore?->id]);
        }
    }

    /**
     * Switch user's current store
     */
    public function switchUserStore(int $userId, int $storeId): void
    {
        $user = User::find($userId);

        // Verify user has access to this store
        // Admins, super-admins et managers ont accès à tous les stores
        $hasAccess = $user->hasAccessToStore($storeId);

        if (!$hasAccess) {
            throw new \Exception('User does not have access to this store');
        }

        $user->update(['current_store_id' => $storeId]);
    }

    /**
     * Get or create stock for variant in store
     */
    public function getOrCreateStoreStock(int $storeId, int $variantId): StoreStock
    {
        return StoreStock::firstOrCreate(
            [
                'store_id' => $storeId,
                'product_variant_id' => $variantId,
            ],
            [
                'quantity' => 0,
                'low_stock_threshold' => 10,
                'min_stock_threshold' => 0,
            ]
        );
    }

    /**
     * Add stock to a store
     */
    public function addStockToStore(int $storeId, int $variantId, int $quantity): StoreStock
    {
        $stock = $this->getOrCreateStoreStock($storeId, $variantId);
        $stock->increaseStock($quantity);

        return $stock->fresh();
    }

    /**
     * Remove stock from a store
     */
    public function removeStockFromStore(int $storeId, int $variantId, int $quantity): StoreStock
    {
        $stock = $this->getOrCreateStoreStock($storeId, $variantId);

        if (!$stock->hasSufficientStock($quantity)) {
            throw new \Exception('Insufficient stock in store');
        }

        $stock->decreaseStock($quantity);

        return $stock->fresh();
    }

    /**
     * Set exact stock quantity in a store
     */
    public function setStoreStock(int $storeId, int $variantId, int $quantity): StoreStock
    {
        $stock = $this->getOrCreateStoreStock($storeId, $variantId);
        $stock->setStock($quantity);

        return $stock->fresh();
    }

    /**
     * Check stock availability in a store
     */
    public function checkStockAvailability(int $storeId, int $variantId, int $requiredQuantity): bool
    {
        $stock = StoreStock::where('store_id', $storeId)
            ->where('product_variant_id', $variantId)
            ->first();

        return $stock && $stock->hasSufficientStock($requiredQuantity);
    }

    /**
     * Get main store or create if doesn't exist
     */
    public function getOrCreateMainStore(): Store
    {
        $mainStore = $this->storeRepository->getMainStore();

        if (!$mainStore) {
            $mainStore = $this->createStore([
                'name' => 'Magasin Principal',
                'code' => 'MAG-001',
                'is_main' => true,
                'is_active' => true,
            ]);
        }

        return $mainStore;
    }

    /**
     * Get store by ID (alias for findStore)
     */
    public function getStoreById(int $id): ?Store
    {
        return $this->findStore($id);
    }

    /**
     * Get store statistics
     */
    public function getStoreStatistics(int $storeId): array
    {
        $store = $this->findStore($storeId);

        if (!$store) {
            return [
                'total_products' => 0,
                'total_stock_items' => 0,
                'total_sales' => 0,
                'total_sales_amount' => 0,
                'total_purchases' => 0,
                'total_stock_value' => 0,
                'low_stock_count' => 0,
                'out_of_stock_count' => 0,
                'active_transfers_count' => 0,
            ];
        }

        // Calculate total stock value
        // Price = product.price + variant.additional_price
        $totalStockValue = $store->stock()
            ->join('product_variants', 'store_stock.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->selectRaw('SUM(store_stock.quantity * (products.price + COALESCE(product_variants.additional_price, 0))) as total_value')
            ->value('total_value') ?? 0;

        // Calculate total sales amount (sum of all completed sales)
        $totalSalesAmount = $store->sales()
            ->where('status', 'completed')
            ->sum('total') ?? 0;

        return [
            'total_products' => $store->products()->count(),
            'total_stock_items' => $store->stock()->count(),
            'total_sales' => $store->sales()->count(),
            'total_sales_amount' => $totalSalesAmount,
            'total_purchases' => $store->purchases()->count(),
            'total_stock_value' => $totalStockValue,
            'low_stock_count' => $store->stock()->whereRaw('quantity <= low_stock_threshold')->count(),
            'out_of_stock_count' => $store->stock()->whereRaw('quantity <= min_stock_threshold')->count(),
            'active_transfers_count' => $store->outgoingTransfers()->whereIn('status', ['pending', 'in_transit'])->count() +
                                       $store->incomingTransfers()->whereIn('status', ['pending', 'in_transit'])->count(),
        ];
    }

    /**
     * Get store stock query
     */
    public function getStoreStock(int $storeId)
    {
        return $this->storeRepository->find($storeId)
            ->stock()
            ->with(['variant.product', 'store']);
    }

    /**
     * Vérifie si l'organisation peut ajouter un magasin selon son plan
     * @throws \Exception si la limite est atteinte
     */
    protected function checkStoreLimit(): void
    {
        $user = auth()->user();
        
        if (!$user) {
            return; // Pas d'utilisateur connecté
        }

        $organizationId = session('current_organization_id') ?? $user->default_organization_id;
        $organization = \App\Models\Organization::find($organizationId);

        if (!$organization) {
            return; // Pas d'organisation
        }

        if (!$organization->canAddStore()) {
            $usage = $organization->getStoresUsage();
            $planLimitService = app(\App\Services\PlanLimitService::class);
            throw new \Exception(
                $planLimitService->getLimitReachedMessage('stores', $usage['current'], $usage['max'])
            );
        }
    }
}

