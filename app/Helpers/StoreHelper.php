<?php

if (!function_exists('current_store_id')) {
    /**
     * Get the current store ID for the authenticated user
     * Can be overridden by request parameter 'store_id' for API calls
     */
    function current_store_id(?int $overrideStoreId = null): ?int
    {
        // Si un store_id est passé en override, l'utiliser
        if ($overrideStoreId !== null) {
            return $overrideStoreId;
        }

        $user = auth()->user();

        return $user?->current_store_id;
    }
}

if (!function_exists('get_request_store_id')) {
    /**
     * Get the store_id from the current request
     * Validates that the user has access to the requested store
     *
     * @return int|null The validated store_id or null
     */
    function get_request_store_id(): ?int
    {
        $requestStoreId = request()->input('store_id');

        if (!$requestStoreId) {
            return null;
        }

        $storeId = (int) $requestStoreId;
        $user = auth()->user();

        if (!$user) {
            return null;
        }

        // Vérifier que l'utilisateur a accès à ce store
        if ($user->isAdmin()) {
            // Admin peut accéder à tous les stores de son organisation
            $hasAccess = \App\Models\Store::where('id', $storeId)
                ->where('organization_id', $user->default_organization_id)
                ->exists();
        } else {
            // Utilisateur régulier peut accéder seulement à ses stores assignés
            $hasAccess = $user->stores()->where('stores.id', $storeId)->exists();
        }

        return $hasAccess ? $storeId : null;
    }
}

if (!function_exists('effective_store_id')) {
    /**
     * Get the effective store_id to use for queries
     * Priority: request store_id > user's current_store_id
     *
     * @return int|null
     */
    function effective_store_id(): ?int
    {
        // D'abord vérifier si un store_id est passé dans la requête
        $requestStoreId = get_request_store_id();

        if ($requestStoreId !== null) {
            return $requestStoreId;
        }

        // Sinon utiliser le current_store_id de l'utilisateur
        return current_store_id();
    }
}

if (!function_exists('current_store')) {
    /**
     * Get the current store for the authenticated user
     */
    function current_store(): ?\App\Models\Store
    {
        $user = auth()->user();

        return $user?->currentStore;
    }
}

if (!function_exists('user_can_access_all_stores')) {
    /**
     * Check if the current user can access all stores
     * Returns true only if:
     * - User has global admin role AND current_store_id is NULL (viewing all stores)
     */
    function user_can_access_all_stores(): bool
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        // Si l'utilisateur a sélectionné un store spécifique, on filtre par ce store
        if ($user->current_store_id !== null) {
            return false;
        }

        // Check if user has global admin role (admin, super-admin, or manager)
        // Force le chargement des rôles pour éviter les problèmes de cache
        if (!$user->relationLoaded('roles')) {
            $user->load('roles');
        }

        $isAdmin = $user->isAdmin();

        // Retourne true seulement si admin ET current_store_id est null
        if ($isAdmin) {
            return true;
        }

        // For store-level filtering, only admins with role 'admin' in the store can see all data
        // Cashiers and staff should always see filtered data
        return false;
    }
}

if (!function_exists('user_role_in_current_store')) {
    /**
     * Get the user's role in the current store
     * Returns: 'admin', 'manager', 'cashier', 'staff', or null
     */
    function user_role_in_current_store(): ?string
    {
        $user = auth()->user();

        if (!$user || !$user->current_store_id) {
            return null;
        }

        return $user->getRoleInStore($user->current_store_id);
    }
}

if (!function_exists('user_is_cashier_or_staff')) {
    /**
     * Check if the current user is a cashier or staff in their current store
     */
    function user_is_cashier_or_staff(): bool
    {
        $role = user_role_in_current_store();

        return in_array($role, ['cashier', 'staff']);
    }
}

if (!function_exists('should_filter_by_store')) {
    /**
     * Check if the current user should have store filtering applied
     * Returns true if:
     * - User is not admin AND
     * - User has a current_store_id set
     *
     * If user is not admin and has no store_id, queries should return empty results
     */
    function should_filter_by_store(): bool
    {
        return !user_can_access_all_stores() && current_store_id() !== null;
    }
}

if (!function_exists('user_has_no_store_access')) {
    /**
     * Check if user should see NO data because they have no store assigned
     * Returns true if user is not admin AND has no current_store_id
     */
    function user_has_no_store_access(): bool
    {
        return !user_can_access_all_stores() && current_store_id() === null;
    }
}

if (!function_exists('current_organization_id')) {
    /**
     * Get the current organization ID for the authenticated user
     *
     * @return int|null The organization ID or null if not available
     */
    function current_organization_id(): ?int
    {
        // First try from app container
        try {
            $organization = app('current_organization');
            if ($organization) {
                return $organization->id;
            }
        } catch (\Exception $e) {
            // Fallback silently
        }

        // Try from session
        $orgId = session('current_organization_id');
        if ($orgId) {
            return (int) $orgId;
        }

        // Try from authenticated user
        $user = auth()->user();
        if ($user) {
            // Try user's current store's organization
            if ($user->current_store_id && $user->currentStore) {
                return $user->currentStore->organization_id;
            }

            // Try user's first organization
            $userOrg = $user->organizations()->first();
            if ($userOrg) {
                return $userOrg->id;
            }
        }

        return null;
    }
}

if (!function_exists('current_organization')) {
    /**
     * Get the current organization for the authenticated user
     *
     * @return \App\Models\Organization|null
     */
    function current_organization(): ?\App\Models\Organization
    {
        // First try from app container
        try {
            $organization = app('current_organization');
            if ($organization) {
                return $organization;
            }
        } catch (\Exception $e) {
            // Fallback silently
        }

        // Try to get from ID
        $orgId = current_organization_id();
        if ($orgId) {
            return \Illuminate\Support\Facades\Cache::remember(
                "organization_{$orgId}",
                3600,
                fn () => \App\Models\Organization::find($orgId)
            );
        }

        return null;
    }
}
