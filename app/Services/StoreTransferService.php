<?php

namespace App\Services;

use App\Models\StoreTransfer;
use App\Models\StoreTransferItem;
use App\Models\StockMovement;
use App\Repositories\StoreTransferRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class StoreTransferService
{
    public function __construct(
        private StoreTransferRepository $transferRepository,
        private StoreService $storeService
    ) {}

    /**
     * Create a new transfer
     */
    public function createTransfer(array $data): StoreTransfer
    {
        DB::beginTransaction();

        try {
            // Validate stores are different
            if ($data['from_store_id'] === $data['to_store_id']) {
                throw new \Exception('Cannot transfer to the same store');
            }

            // Extract items before creating transfer
            $items = $data['items'] ?? [];
            unset($data['items']);

            // Generate transfer number
            $data['transfer_number'] = $this->transferRepository->generateNextNumber();
            $data['status'] = 'pending';
            $data['transfer_date'] = now();

            // Create the transfer
            $transfer = $this->transferRepository->create($data);

            // Add items
            if (!empty($items)) {
                foreach ($items as $item) {
                    StoreTransferItem::create([
                        'store_transfer_id' => $transfer->id,
                        'product_variant_id' => $item['product_variant_id'],
                        'quantity_requested' => $item['quantity'],
                        'notes' => $item['notes'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return $transfer->fresh(['items', 'fromStore', 'toStore']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Approve a transfer
     */
    public function approveTransfer(int $transferId, int $userId): StoreTransfer
    {
        DB::beginTransaction();

        try {
            $transfer = $this->transferRepository->find($transferId);

            if (!$transfer->canBeApproved()) {
                throw new \Exception('Transfer cannot be approved in current status');
            }

            // Check stock availability in source store
            foreach ($transfer->items as $item) {
                $hasStock = $this->storeService->checkStockAvailability(
                    $transfer->from_store_id,
                    $item->product_variant_id,
                    $item->quantity_requested
                );

                if (!$hasStock) {
                    throw new \Exception("Insufficient stock for {$item->getProductName()}");
                }
            }

            // Update transfer status
            $this->transferRepository->update($transferId, [
                'status' => 'in_transit',
                'approved_by' => $userId,
            ]);

            // Update items with sent quantities and remove stock from source
            foreach ($transfer->items as $item) {
                $item->update(['quantity_sent' => $item->quantity_requested]);

                // Create stock movement (OUT) - stock is automatically updated by StockMovement::creating()
                StockMovement::create([
                    'store_id' => $transfer->from_store_id,
                    'product_variant_id' => $item->product_variant_id,
                    'type' => 'out',
                    'movement_type' => 'transfer',
                    'quantity' => $item->quantity_requested,
                    'reference' => $transfer->transfer_number,
                    'reason' => "Transfert vers {$transfer->toStore->name}",
                    'date' => now(),
                    'user_id' => $userId,
                ]);
            }

            DB::commit();

            return $transfer->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Receive a transfer
     */
    public function receiveTransfer(int $transferId, array $receivedQuantities, int $userId, ?string $notes = null): StoreTransfer
    {
        DB::beginTransaction();

        try {
            $transfer = $this->transferRepository->find($transferId);

            if (!$transfer->canBeReceived()) {
                throw new \Exception('Transfer cannot be received in current status');
            }

            // Update items with received quantities and add stock to destination
            foreach ($transfer->items as $item) {
                // receivedQuantities can be keyed by item ID or product_variant_id
                $receivedQty = $receivedQuantities[$item->id] 
                    ?? $receivedQuantities[$item->product_variant_id] 
                    ?? $item->quantity_sent;

                $item->update(['quantity_received' => $receivedQty]);

                // Create stock movement (IN) - stock is automatically updated by StockMovement::creating()
                StockMovement::create([
                    'store_id' => $transfer->to_store_id,
                    'product_variant_id' => $item->product_variant_id,
                    'type' => 'in',
                    'movement_type' => 'transfer',
                    'quantity' => $receivedQty,
                    'reference' => $transfer->transfer_number,
                    'reason' => "Transfert depuis {$transfer->fromStore->name}",
                    'date' => now(),
                    'user_id' => $userId,
                ]);
            }

            // Update transfer status
            $this->transferRepository->update($transferId, [
                'status' => 'completed',
                'received_by' => $userId,
                'actual_arrival_date' => now(),
                'notes' => $notes ?? $transfer->notes,
            ]);

            DB::commit();

            return $transfer->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Cancel a transfer
     */
    public function cancelTransfer(int $transferId, int $userId, string $reason): StoreTransfer
    {
        DB::beginTransaction();

        try {
            $transfer = $this->transferRepository->find($transferId);

            if (!$transfer->canBeCancelled()) {
                throw new \Exception('Transfer cannot be cancelled in current status');
            }

            // If transfer was approved (in_transit), restore stock to source store
            if ($transfer->isInTransit()) {
                foreach ($transfer->items as $item) {
                    if ($item->quantity_sent) {
                        $this->storeService->addStockToStore(
                            $transfer->from_store_id,
                            $item->product_variant_id,
                            $item->quantity_sent
                        );

                        // Create stock movement (IN) to restore
                        StockMovement::create([
                            'store_id' => $transfer->from_store_id,
                            'product_variant_id' => $item->product_variant_id,
                            'type' => 'in',
                            'movement_type' => 'adjustment',
                            'quantity' => $item->quantity_sent,
                            'reference' => $transfer->transfer_number,
                            'reason' => "Annulation du transfert - $reason",
                            'date' => now(),
                            'user_id' => $userId,
                        ]);
                    }
                }
            }

            // Update transfer status
            $this->transferRepository->update($transferId, [
                'status' => 'cancelled',
                'notes' => ($transfer->notes ? $transfer->notes . "\n\n" : '') . "Annulé: $reason",
            ]);

            DB::commit();

            return $transfer->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get pending transfers for a store
     */
    public function getPendingTransfers(int $storeId): Collection
    {
        return $this->transferRepository->getPendingForStore($storeId);
    }

    /**
     * Get all transfers with filters
     */
    public function getAllTransfers(
        ?string $search = null,
        ?string $status = null,
        string $sortBy = 'created_at',
        string $sortDirection = 'desc'
    ) {
        $query = StoreTransfer::query();

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('transfer_number', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%")
                    ->orWhereHas('fromStore', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('toStore', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Apply status filter
        if ($status) {
            $query->where('status', $status);
        }

        // Apply sorting
        $query->orderBy($sortBy, $sortDirection);

        return $query;
    }

    /**
     * Get transfer statistics for a store
     */
    public function getTransferStatistics(int $storeId): array
    {
        return $this->transferRepository->getStatistics($storeId);
    }

    /**
     * Find transfer by ID
     */
    public function findTransfer(int $id): ?StoreTransfer
    {
        return $this->transferRepository->find($id);
    }

    /**
     * Get outgoing transfers for a store
     */
    public function getStoreOutgoingTransfers(int $storeId)
    {
        return StoreTransfer::where('from_store_id', $storeId)
            ->with(['toStore', 'requester', 'approver'])
            ->latest();
    }

    /**
     * Get incoming transfers for a store
     */
    public function getStoreIncomingTransfers(int $storeId)
    {
        return StoreTransfer::where('to_store_id', $storeId)
            ->with(['fromStore', 'requester', 'receiver'])
            ->latest();
    }
}

