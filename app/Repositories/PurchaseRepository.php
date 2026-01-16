<?php

namespace App\Repositories;

use App\Models\Purchase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

class PurchaseRepository
{
    /**
     * Get a new query builder for Purchase.
     */
    public function query(): Builder
    {
        return Purchase::query();
    }

    /**
     * Get all purchases.
     */
    public function all(): Collection
    {
        $query = Purchase::with('supplier');

        // Filter by current store if user is not admin
        if (!user_can_access_all_stores() && current_store_id()) {
            $query->where('store_id', current_store_id());
        }

        return $query->orderBy('purchase_date', 'desc')->get();
    }

    /**
     * Find purchase by ID.
     */
    public function find(int $id): ?Purchase
    {
        return Purchase::with('supplier')->find($id);
    }

    /**
     * Create a new purchase.
     */
    public function create(array $data): Purchase
    {
        return Purchase::create($data);
    }

    /**
     * Update a purchase.
     */
    public function update(Purchase $purchase, array $data): bool
    {
        return $purchase->update($data);
    }

    /**
     * Delete a purchase.
     */
    public function delete(Purchase $purchase): bool
    {
        return $purchase->delete();
    }

    /**
     * Get purchases by supplier.
     */
    public function bySupplier(int $supplierId): Collection
    {
        $query = Purchase::where('supplier_id', $supplierId);

        // Filter by current store if user is not admin
        if (!user_can_access_all_stores() && current_store_id()) {
            $query->where('store_id', current_store_id());
        }

        return $query->orderBy('purchase_date', 'desc')->get();
    }

    /**
     * Get received purchases.
     */
    public function received(): Collection
    {
        $query = Purchase::received()
            ->with('supplier');

        // Filter by current store if user is not admin
        if (!user_can_access_all_stores() && current_store_id()) {
            $query->where('store_id', current_store_id());
        }

        return $query->orderBy('purchase_date', 'desc')->get();
    }

    /**
     * Get pending purchases.
     */
    public function pending(): Collection
    {
        $query = Purchase::pending()
            ->with('supplier');

        // Filter by current store if user is not admin
        if (!user_can_access_all_stores() && current_store_id()) {
            $query->where('store_id', current_store_id());
        }

        return $query->orderBy('purchase_date', 'desc')->get();
    }

    /**
     * Get purchases by date range.
     */
    public function byDateRange(string $startDate, string $endDate): Collection
    {
        $query = Purchase::betweenDates($startDate, $endDate)
            ->with('supplier');

        // Filter by current store if user is not admin
        if (!user_can_access_all_stores() && current_store_id()) {
            $query->where('store_id', current_store_id());
        }

        return $query->orderBy('purchase_date', 'desc')->get();
    }
}
