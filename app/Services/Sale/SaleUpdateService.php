<?php

namespace App\Services\Sale;

use App\Models\Sale;
use App\Repositories\SaleRepository;
use Illuminate\Support\Facades\DB;

/**
 * Service responsible for updating existing sales
 * Handles sale updates, item modifications, and status changes
 */
class SaleUpdateService
{
    public function __construct(
        private SaleRepository $saleRepository,
        private SaleCreationService $creationService
    ) {}

    /**
     * Update an existing sale
     * 
     * @param int $saleId
     * @param array $data Update data
     * @return Sale Updated sale
     * @throws \Exception If sale not found
     */
    public function updateSale(int $saleId, array $data): Sale
    {
        $sale = $this->saleRepository->find($saleId);

        if (!$sale) {
            throw new \Exception("Sale not found");
        }

        $this->saleRepository->update($sale, $data);

        // Update payment status if paid_amount is set
        if (isset($data['paid_amount'])) {
            $sale->updatePaymentStatus();
        }

        return $sale->fresh('items.productVariant.product');
    }

    /**
     * Cancel a sale and optionally restore stock
     * 
     * @param int $saleId
     * @param string|null $reason Cancellation reason
     * @return Sale Cancelled sale
     * @throws \Exception If sale not found or already cancelled
     */
    public function cancelSale(int $saleId, string $reason = null): Sale
    {
        return DB::transaction(function () use ($saleId, $reason) {
            $sale = $this->saleRepository->find($saleId);

            if (!$sale) {
                throw new \Exception("Sale not found");
            }

            if ($sale->status === Sale::STATUS_CANCELLED) {
                throw new \Exception("Sale is already cancelled");
            }

            // If sale was completed, restore stock
            if ($sale->status === Sale::STATUS_COMPLETED) {
                $sale->load('items.productVariant');
                foreach ($sale->items as $item) {
                    $item->productVariant->increaseStock($item->quantity);
                }
            }

            // Update sale status
            $sale->status = Sale::STATUS_CANCELLED;
            $sale->notes = ($sale->notes ? $sale->notes . "\n" : '') . 
                          "Cancelled: " . ($reason ?? 'No reason provided');
            $sale->save();

            return $sale->fresh();
        });
    }

    /**
     * Remove item from sale and recalculate totals
     * 
     * @param int $saleId
     * @param int $itemId
     * @return Sale Updated sale
     * @throws \Exception If sale not found, item not found, or sale is not pending
     */
    public function removeItemFromSale(int $saleId, int $itemId): Sale
    {
        return DB::transaction(function () use ($saleId, $itemId) {
            $sale = $this->saleRepository->find($saleId);

            if (!$sale) {
                throw new \Exception("Sale not found");
            }

            if ($sale->status !== Sale::STATUS_PENDING) {
                throw new \Exception("Cannot remove items from a completed or cancelled sale");
            }

            $item = $sale->items()->find($itemId);

            if (!$item) {
                throw new \Exception("Item not found in this sale");
            }

            $item->delete();
            $sale->calculateTotals();

            return $sale->fresh('items.productVariant.product');
        });
    }

    /**
     * Update sale status
     * 
     * @param int $saleId
     * @param string $status New status
     * @return Sale Updated sale
     */
    public function updateStatus(int $saleId, string $status): Sale
    {
        $sale = $this->saleRepository->find($saleId);

        if (!$sale) {
            throw new \Exception("Sale not found");
        }

        $validStatuses = [Sale::STATUS_PENDING, Sale::STATUS_COMPLETED, Sale::STATUS_CANCELLED];
        
        if (!in_array($status, $validStatuses)) {
            throw new \Exception("Invalid status: {$status}");
        }

        $sale->status = $status;
        $sale->save();

        return $sale->fresh();
    }

    /**
     * Update sale notes
     */
    public function updateNotes(int $saleId, string $notes): Sale
    {
        $sale = $this->saleRepository->find($saleId);

        if (!$sale) {
            throw new \Exception("Sale not found");
        }

        $sale->notes = $notes;
        $sale->save();

        return $sale;
    }

    /**
     * Update sale discount
     */
    public function updateDiscount(int $saleId, float $discount): Sale
    {
        return DB::transaction(function () use ($saleId, $discount) {
            $sale = $this->saleRepository->find($saleId);

            if (!$sale) {
                throw new \Exception("Sale not found");
            }

            if ($sale->status !== Sale::STATUS_PENDING) {
                throw new \Exception("Cannot update discount for a completed or cancelled sale");
            }

            $sale->discount = (string) $discount;
            $sale->save();
            $sale->calculateTotals();

            return $sale->fresh('items.productVariant.product');
        });
    }

    /**
     * Update sale tax
     */
    public function updateTax(int $saleId, float $tax): Sale
    {
        return DB::transaction(function () use ($saleId, $tax) {
            $sale = $this->saleRepository->find($saleId);

            if (!$sale) {
                throw new \Exception("Sale not found");
            }

            if ($sale->status !== Sale::STATUS_PENDING) {
                throw new \Exception("Cannot update tax for a completed or cancelled sale");
            }

            $sale->tax = (string) $tax;
            $sale->save();
            $sale->calculateTotals();

            return $sale->fresh('items.productVariant.product');
        });
    }
}
