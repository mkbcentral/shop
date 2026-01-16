<?php

namespace App\Repositories;

use App\Models\StoreTransfer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class StoreTransferRepository
{
    /**
     * Get all transfers
     */
    public function all(): Collection
    {
        return StoreTransfer::with(['fromStore', 'toStore', 'requester'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get transfers with pagination and filters
     */
    public function paginate(
        int $perPage = 15,
        ?int $fromStoreId = null,
        ?int $toStoreId = null,
        ?string $status = null,
        ?string $dateFrom = null,
        ?string $dateTo = null
    ): LengthAwarePaginator {
        $query = StoreTransfer::with(['fromStore', 'toStore', 'requester', 'approver']);

        if ($fromStoreId) {
            $query->where('from_store_id', $fromStoreId);
        }

        if ($toStoreId) {
            $query->where('to_store_id', $toStoreId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($dateFrom) {
            $query->whereDate('transfer_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('transfer_date', '<=', $dateTo);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Find transfer by ID
     */
    public function find(int $id): ?StoreTransfer
    {
        return StoreTransfer::with([
            'fromStore',
            'toStore',
            'items.variant.product',
            'requester',
            'approver',
            'receiver'
        ])->find($id);
    }

    /**
     * Find by transfer number
     */
    public function findByNumber(string $transferNumber): ?StoreTransfer
    {
        return StoreTransfer::where('transfer_number', $transferNumber)->first();
    }

    /**
     * Create a new transfer
     */
    public function create(array $data): StoreTransfer
    {
        return StoreTransfer::create($data);
    }

    /**
     * Update a transfer
     */
    public function update(int $id, array $data): bool
    {
        return StoreTransfer::where('id', $id)->update($data);
    }

    /**
     * Delete a transfer
     */
    public function delete(int $id): bool
    {
        return StoreTransfer::destroy($id) > 0;
    }

    /**
     * Get pending transfers for a store
     */
    public function getPendingForStore(int $storeId): Collection
    {
        return StoreTransfer::pending()
            ->where(function ($query) use ($storeId) {
                $query->where('from_store_id', $storeId)
                    ->orWhere('to_store_id', $storeId);
            })
            ->with(['fromStore', 'toStore', 'items'])
            ->get();
    }

    /**
     * Get outgoing transfers from a store
     */
    public function getOutgoingTransfers(int $storeId, ?string $status = null): Collection
    {
        $query = StoreTransfer::fromStore($storeId)->with(['toStore', 'items']);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get incoming transfers to a store
     */
    public function getIncomingTransfers(int $storeId, ?string $status = null): Collection
    {
        $query = StoreTransfer::toStore($storeId)->with(['fromStore', 'items']);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Generate next transfer number
     */
    public function generateNextNumber(): string
    {
        $year = date('Y');
        $lastTransfer = StoreTransfer::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $lastTransfer ? ((int) substr($lastTransfer->transfer_number, -4)) + 1 : 1;

        return 'TRF-' . $year . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Check if transfer number exists
     */
    public function numberExists(string $transferNumber): bool
    {
        return StoreTransfer::where('transfer_number', $transferNumber)->exists();
    }

    /**
     * Get transfer statistics
     */
    public function getStatistics(int $storeId): array
    {
        return [
            'pending_outgoing' => StoreTransfer::where('from_store_id', $storeId)->pending()->count(),
            'pending_incoming' => StoreTransfer::where('to_store_id', $storeId)->pending()->count(),
            'in_transit' => StoreTransfer::where(function ($query) use ($storeId) {
                $query->where('from_store_id', $storeId)
                    ->orWhere('to_store_id', $storeId);
            })->inTransit()->count(),
            'completed_this_month' => StoreTransfer::where(function ($query) use ($storeId) {
                $query->where('from_store_id', $storeId)
                    ->orWhere('to_store_id', $storeId);
            })->completed()->whereMonth('created_at', now()->month)->count(),
        ];
    }
}
