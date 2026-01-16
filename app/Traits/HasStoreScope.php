<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait HasStoreScope
{
    /**
     * Scope a query to only include records for a specific store.
     *
     * @param Builder $query
     * @param int $storeId
     * @return Builder
     */
    public function scopeForStore(Builder $query, int $storeId): Builder
    {
        return $query->where($this->getTable() . '.store_id', $storeId);
    }

    /**
     * Scope a query to only include records for the current user's active store.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeForCurrentStore(Builder $query): Builder
    {
        $currentStoreId = auth()->user()?->current_store_id;

        if (!$currentStoreId) {
            // If no current store, return empty result
            return $query->whereRaw('1 = 0');
        }

        return $query->where($this->getTable() . '.store_id', $currentStoreId);
    }

    /**
     * Scope a query to only include records for stores the user has access to.
     *
     * @param Builder $query
     * @param int|null $userId
     * @return Builder
     */
    public function scopeForUserStores(Builder $query, ?int $userId = null): Builder
    {
        $userId = $userId ?? auth()->id();

        if (!$userId) {
            // If no user, return empty result
            return $query->whereRaw('1 = 0');
        }

        $user = User::find($userId);

        if (!$user) {
            return $query->whereRaw('1 = 0');
        }

        $storeIds = $user->stores()->pluck('stores.id')->toArray();

        if (empty($storeIds)) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereIn($this->getTable() . '.store_id', $storeIds);
    }

    /**
     * Scope a query to exclude records from a specific store.
     *
     * @param Builder $query
     * @param int $storeId
     * @return Builder
     */
    public function scopeExceptStore(Builder $query, int $storeId): Builder
    {
        return $query->where($this->getTable() . '.store_id', '!=', $storeId);
    }

    /**
     * Scope a query to only include records for multiple stores.
     *
     * @param Builder $query
     * @param array $storeIds
     * @return Builder
     */
    public function scopeForStores(Builder $query, array $storeIds): Builder
    {
        return $query->whereIn($this->getTable() . '.store_id', $storeIds);
    }

    /**
     * Scope a query to only include records without a store assigned.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithoutStore(Builder $query): Builder
    {
        return $query->whereNull($this->getTable() . '.store_id');
    }
}
