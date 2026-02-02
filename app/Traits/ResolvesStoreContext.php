<?php

namespace App\Traits;

use App\Models\Store;

/**
 * Trait ResolvesStoreContext
 * 
 * Centralizes the logic for resolving the current store context
 * across multiple services. Provides consistent fallback mechanisms
 * for determining which store to use when processing operations.
 */
trait ResolvesStoreContext
{
    /**
     * Resolve the store ID to use for operations.
     * 
     * Priority order:
     * 1. Explicitly provided store_id
     * 2. Current session store_id (current_store_id())
     * 3. Authenticated user's current_store_id
     * 4. User's first assigned store
     * 5. Organization's main store
     * 6. Organization's first active store
     * 
     * @param int|null $providedStoreId Explicitly provided store ID
     * @return int The resolved store ID
     * @throws \Exception When no store is available
     */
    protected function resolveStoreId(?int $providedStoreId = null): int
    {
        // Priority 1: Use provided store_id if given
        if ($providedStoreId) {
            return $providedStoreId;
        }

        // Priority 2: Use current session store
        $currentStoreId = current_store_id();
        if ($currentStoreId) {
            return $currentStoreId;
        }

        // Priority 3: Use authenticated user's current store
        $user = auth()->user();
        if ($user && $user->current_store_id) {
            return $user->current_store_id;
        }

        // Priority 4: Use user's first assigned store
        if ($user && $user->stores && $user->stores->isNotEmpty()) {
            return $user->stores->first()->id;
        }

        // Priority 5 & 6: Use organization's main or first active store
        return $this->resolveOrganizationStore();
    }

    /**
     * Resolve store from current organization context.
     * 
     * @return int The organization's store ID
     * @throws \Exception When no store is available
     */
    protected function resolveOrganizationStore(): int
    {
        $currentOrganization = app()->bound('current_organization') 
            ? app('current_organization') 
            : null;

        $storeQuery = Store::where('is_active', true);

        if ($currentOrganization) {
            $storeQuery->where('organization_id', $currentOrganization->id);
        }

        // Try to get the main store first
        $mainStore = (clone $storeQuery)->where('is_main', true)->first();

        if ($mainStore) {
            return $mainStore->id;
        }

        // If no main store, get the first active store
        $firstStore = $storeQuery->first();
        
        if ($firstStore) {
            return $firstStore->id;
        }

        throw new \Exception(
            'Aucun magasin disponible dans cette organisation. ' .
            'Veuillez créer un magasin ou sélectionner un magasin actuel.'
        );
    }

    /**
     * Resolve the organization ID from various contexts.
     * 
     * Priority order:
     * 1. Explicitly provided organization_id
     * 2. Organization from resolved store
     * 3. Current application organization context
     * 4. User's default organization
     * 
     * @param int|null $providedOrganizationId
     * @param int|null $storeId
     * @return int|null
     */
    protected function resolveOrganizationId(
        ?int $providedOrganizationId = null, 
        ?int $storeId = null
    ): ?int {
        // Priority 1: Use provided organization_id
        if ($providedOrganizationId) {
            return $providedOrganizationId;
        }

        // Priority 2: Get from store
        if ($storeId) {
            $store = Store::find($storeId);
            if ($store) {
                return $store->organization_id;
            }
        }

        // Priority 3: Get from application context
        if (app()->bound('current_organization')) {
            $org = app('current_organization');
            if ($org) {
                return $org->id;
            }
        }

        // Priority 4: Get from authenticated user
        $user = auth()->user();
        if ($user && $user->default_organization_id) {
            return $user->default_organization_id;
        }

        return null;
    }

    /**
     * Validate that a store belongs to the current organization context.
     * 
     * @param int $storeId
     * @return bool
     */
    protected function validateStoreOrganization(int $storeId): bool
    {
        $store = Store::find($storeId);
        
        if (!$store) {
            return false;
        }

        $currentOrganization = app()->bound('current_organization') 
            ? app('current_organization') 
            : null;

        // If no organization context, allow any store
        if (!$currentOrganization) {
            return true;
        }

        // Validate store belongs to current organization
        return $store->organization_id === $currentOrganization->id;
    }
}
