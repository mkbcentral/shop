<?php

namespace App\Repositories;

use App\Models\StockMovement;
use Illuminate\Database\Eloquent\Collection;

class StockMovementRepository
{
    /**
     * Get query builder.
     */
    public function query()
    {
        return StockMovement::query();
    }

    /**
     * Get all stock movements.
     */
    public function all(): Collection
    {
        $query = StockMovement::with('productVariant.product', 'user');

        // Filter by current store
        if (current_store_id()) {
            $query->where('store_id', current_store_id());
        }

        return $query->orderBy('date', 'desc')->get();
    }

    /**
     * Find movement by ID.
     */
    public function find(int $id): ?StockMovement
    {
        return StockMovement::with('productVariant.product', 'user')->find($id);
    }

    /**
     * Create a new stock movement.
     */
    public function create(array $data): StockMovement
    {
        return StockMovement::create($data);
    }

    /**
     * Get movements by product variant.
     */
    public function byProductVariant(int $variantId): Collection
    {
        $query = StockMovement::where('product_variant_id', $variantId)
            ->with('user');

        // Filter by current store
        if (current_store_id()) {
            $query->where('store_id', current_store_id());
        }

        return $query->orderBy('date', 'desc')->get();
    }

    /**
     * Get movements by date range.
     */
    public function byDateRange(string $startDate, string $endDate): Collection
    {
        $query = StockMovement::betweenDates($startDate, $endDate)
            ->with('productVariant.product', 'user');

        // Filter by current store
        if (current_store_id()) {
            $query->where('store_id', current_store_id());
        }

        return $query->orderBy('date', 'desc')->get();
    }

    /**
     * Get in movements (entries).
     */
    public function entries(): Collection
    {
        $query = StockMovement::in()
            ->with('productVariant.product');

        // Filter by current store
        if (current_store_id()) {
            $query->where('store_id', current_store_id());
        }

        return $query->orderBy('date', 'desc')->get();
    }

    /**
     * Get out movements (exits).
     */
    public function exits(): Collection
    {
        $query = StockMovement::out()
            ->with('productVariant.product');

        // Filter by current store
        if (current_store_id()) {
            $query->where('store_id', current_store_id());
        }

        return $query->orderBy('date', 'desc')->get();
    }

    /**
     * Get movements by type.
     */
    public function byType(string $movementType): Collection
    {
        $query = StockMovement::ofType($movementType)
            ->with('productVariant.product', 'user');

        // Filter by current store
        if (current_store_id()) {
            $query->where('store_id', current_store_id());
        }

        return $query->orderBy('date', 'desc')->get();
    }

    /**
     * Get movements for report with filters.
     */
    public function forReport(
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $type = null,
        ?string $movementType = null,
        ?int $productVariantId = null
    ): Collection {
        $query = StockMovement::with(['productVariant.product', 'user'])
            ->orderBy('date', 'desc');

        // Filter by current store
        if (current_store_id()) {
            $query->where('store_id', current_store_id());
        }

        if ($dateFrom) {
            $query->whereDate('date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('date', '<=', $dateTo);
        }
        if ($type) {
            $query->where('type', $type);
        }
        if ($movementType) {
            $query->where('movement_type', $movementType);
        }
        if ($productVariantId) {
            $query->where('product_variant_id', $productVariantId);
        }

        return $query->get();
    }

    /**
     * Get stock movement statistics for a period.
     */
    public function statistics(string $startDate, string $endDate): array
    {
        $query = StockMovement::betweenDates($startDate, $endDate);

        // Filter by current store
        if (current_store_id()) {
            $query->where('store_id', current_store_id());
        }

        $movements = $query->get();

        return [
            'total_in' => $movements->where('type', 'in')->sum('quantity'),
            'total_out' => $movements->where('type', 'out')->sum('quantity'),
            'net_movement' => $movements->where('type', 'in')->sum('quantity') -
                             $movements->where('type', 'out')->sum('quantity'),
            'total_value_in' => $movements->where('type', 'in')->sum('total_price'),
            'total_value_out' => $movements->where('type', 'out')->sum('total_price'),
            'total_value' => $movements->sum('total_price'),
            'total_movements' => $movements->count(),
        ];
    }

    /**
     * Get today's movements.
     */
    public function today(): Collection
    {
        $query = StockMovement::whereDate('date', today())
            ->with('productVariant.product', 'user');

        // Filter by current store
        if (current_store_id()) {
            $query->where('store_id', current_store_id());
        }

        return $query->orderBy('created_at', 'desc')->get();
    }
}

