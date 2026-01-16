<?php

namespace App\Repositories;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class SaleRepository
{
    /**
     * Get a new query builder for Sale.
     */
    public function query(): Builder
    {
        $query = Sale::query();

        // Filter by current store
        if (current_store_id()) {
            $query->where('store_id', current_store_id());
        }

        return $query;
    }

    /**
     * Get all sales.
     */
    public function all(): Collection
    {
        $query = Sale::with('client', 'user', 'items.productVariant');

        // Filter by current store if user is not admin
        if (current_store_id()) {
            $query->where('store_id', current_store_id());
        }

        return $query->orderBy('sale_date', 'desc')->get();
    }

    /**
     * Get paginated sales.
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        $query = Sale::with('client', 'user');

        // Filter by current store if user is not admin
        if (current_store_id()) {
            $query->where('store_id', current_store_id());
        }

        return $query->orderBy('sale_date', 'desc')->paginate($perPage);
    }

    /**
     * Find sale by ID.
     */
    public function find(int $id): ?Sale
    {
        return Sale::with('client', 'user', 'items.productVariant.product', 'invoice', 'payments')
            ->find($id);
    }

    /**
     * Find sale by sale number.
     */
    public function findBySaleNumber(string $saleNumber): ?Sale
    {
        return Sale::where('sale_number', $saleNumber)
            ->with('client', 'items.productVariant.product')
            ->first();
    }

    /**
     * Create a new sale.
     */
    public function create(array $data): Sale
    {
        return Sale::create($data);
    }

    /**
     * Update a sale.
     */
    public function update(Sale $sale, array $data): bool
    {
        return $sale->update($data);
    }

    /**
     * Delete a sale.
     */
    public function delete(Sale $sale): bool
    {
        return $sale->delete();
    }

    /**
     * Get sales by date range.
     */
    public function byDateRange(string $startDate, string $endDate): Collection
    {
        $query = Sale::betweenDates($startDate, $endDate)
            ->with('client', 'items');

        // Filter by current store if user is not admin
        if (current_store_id()) {
            $query->where('store_id', current_store_id());
        }

        return $query->orderBy('sale_date', 'desc')->get();
    }

    /**
     * Get sales by client.
     */
    public function byClient(int $clientId): Collection
    {
        $query = Sale::where('client_id', $clientId)
            ->with('items.productVariant');

        // Filter by current store if user is not admin
        if (current_store_id()) {
            $query->where('store_id', current_store_id());
        }

        return $query->orderBy('sale_date', 'desc')->get();
    }

    /**
     * Get completed sales.
     */
    public function completed(): Collection
    {
        $query = Sale::completed()
            ->with('client', 'items');

        // Filter by current store if user is not admin
        if (current_store_id()) {
            $query->where('store_id', current_store_id());
        }

        return $query->orderBy('sale_date', 'desc')->get();
    }

    /**
     * Get pending sales.
     */
    public function pending(): Collection
    {
        $query = Sale::pending()
            ->with('client', 'items');

        // Filter by current store if user is not admin
        if (current_store_id()) {
            $query->where('store_id', current_store_id());
        }

        return $query->orderBy('sale_date', 'desc')->get();
    }

    /**
     * Get unpaid sales.
     */
    public function unpaid(): Collection
    {
        $query = Sale::where('payment_status', '!=', 'paid')
            ->with('client');

        // Filter by current store if user is not admin
        if (current_store_id()) {
            $query->where('store_id', current_store_id());
        }

        return $query->orderBy('sale_date', 'desc')->get();
    }

    /**
     * Get sales statistics for a period.
     */
    public function statistics(string $startDate, string $endDate): array
    {
        $query = Sale::betweenDates($startDate, $endDate)
            ->completed()
            ->paid();

        // Filter by current store if user is not admin
        if (current_store_id()) {
            $query->where('store_id', current_store_id());
        }

        $sales = $query->get();

        return [
            'total_sales' => $sales->count(),
            'total_amount' => $sales->sum('total'),
            'total_discount' => $sales->sum('discount'),
            'average_sale' => $sales->avg('total'),
        ];
    }

    /**
     * Get today's sales.
     */
    public function today(): Collection
    {
        $query = Sale::whereDate('sale_date', today())
            ->with('client', 'items');

        // Filter by current store if user is not admin
        if (current_store_id()) {
            $query->where('store_id', current_store_id());
        }

        return $query->orderBy('sale_date', 'desc')->get();
    }
}
