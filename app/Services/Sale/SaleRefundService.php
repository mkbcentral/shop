<?php

namespace App\Services\Sale;

use App\Models\Sale;
use App\Repositories\SaleRepository;
use Illuminate\Support\Facades\DB;

/**
 * Service responsible for handling sale refunds
 * Manages refund processing, stock restoration, and invoice cancellation
 */
class SaleRefundService
{
    public function __construct(
        private SaleRepository $saleRepository
    ) {}

    /**
     * Refund a sale completely
     * 
     * @param int $saleId
     * @param string $reason Refund reason
     * @param bool $restoreStock Whether to restore stock
     * @return Sale Refunded sale
     * @throws \Exception If sale cannot be refunded
     */
    public function refundSale(int $saleId, string $reason, bool $restoreStock = true): Sale
    {
        return DB::transaction(function () use ($saleId, $reason, $restoreStock) {
            $sale = $this->saleRepository->find($saleId);

            if (!$sale) {
                throw new \Exception("Sale not found");
            }

            // Validate refund eligibility
            if ($sale->status === Sale::STATUS_CANCELLED) {
                throw new \Exception("Cannot refund a cancelled sale");
            }

            if ($sale->payment_status === Sale::PAYMENT_REFUNDED) {
                throw new \Exception("Sale is already refunded");
            }

            // Restore stock if requested
            if ($restoreStock) {
                $this->restoreStock($sale);
            }

            // Update sale status
            $sale->status = Sale::STATUS_CANCELLED;
            $sale->payment_status = Sale::PAYMENT_REFUNDED;
            $sale->notes = ($sale->notes ? $sale->notes . "\n" : '') . "Refunded: " . $reason;
            $sale->save();

            // Cancel associated invoice if exists
            if ($sale->invoice && $sale->invoice->status !== 'cancelled') {
                $sale->invoice->status = 'cancelled';
                $sale->invoice->notes = ($sale->invoice->notes ? $sale->invoice->notes . "\n" : '') . 
                                       "Cancelled due to sale refund: " . $reason;
                $sale->invoice->save();
            }

            return $sale->fresh('items.productVariant.product', 'invoice');
        });
    }

    /**
     * Partial refund for specific items
     * 
     * @param int $saleId
     * @param array $items Array of ['item_id' => int, 'quantity' => int]
     * @param string $reason
     * @param bool $restoreStock
     * @return Sale Updated sale
     */
    public function partialRefund(
        int $saleId, 
        array $items, 
        string $reason, 
        bool $restoreStock = true
    ): Sale {
        return DB::transaction(function () use ($saleId, $items, $reason, $restoreStock) {
            $sale = $this->saleRepository->find($saleId);

            if (!$sale) {
                throw new \Exception("Sale not found");
            }

            if ($sale->status === Sale::STATUS_CANCELLED) {
                throw new \Exception("Cannot refund a cancelled sale");
            }

            $refundAmount = 0;

            foreach ($items as $itemData) {
                $item = $sale->items()->find($itemData['item_id']);

                if (!$item) {
                    throw new \Exception("Item {$itemData['item_id']} not found in sale");
                }

                $quantityToRefund = $itemData['quantity'];

                if ($quantityToRefund > $item->quantity) {
                    throw new \Exception(
                        "Refund quantity ({$quantityToRefund}) exceeds original quantity ({$item->quantity})"
                    );
                }

                // Calculate refund amount for this item
                $itemRefundAmount = ($item->unit_price * $quantityToRefund) - 
                                   ($item->discount * $quantityToRefund / $item->quantity);
                $refundAmount += $itemRefundAmount;

                // Restore stock if requested
                if ($restoreStock) {
                    $item->productVariant->increaseStock($quantityToRefund);
                }

                // Update or remove item
                if ($quantityToRefund === $item->quantity) {
                    $item->delete();
                } else {
                    $item->quantity -= $quantityToRefund;
                    $item->save();
                }
            }

            // Recalculate sale totals
            $sale->calculateTotals();

            // Update payment status
            if ($sale->paid_amount > $sale->total) {
                $sale->paid_amount = $sale->total;
            }
            $sale->updatePaymentStatus();

            // Add refund notes
            $sale->notes = ($sale->notes ? $sale->notes . "\n" : '') . 
                          "Partial refund ({$refundAmount}): " . $reason;
            $sale->save();

            return $sale->fresh('items.productVariant.product');
        });
    }

    /**
     * Check if a sale can be refunded
     * 
     * @param int $saleId
     * @return array ['can_refund' => bool, 'reason' => string|null]
     */
    public function canRefund(int $saleId): array
    {
        $sale = $this->saleRepository->find($saleId);

        if (!$sale) {
            return ['can_refund' => false, 'reason' => 'Sale not found'];
        }

        if ($sale->status === Sale::STATUS_CANCELLED) {
            return ['can_refund' => false, 'reason' => 'Sale is already cancelled'];
        }

        if ($sale->payment_status === Sale::PAYMENT_REFUNDED) {
            return ['can_refund' => false, 'reason' => 'Sale is already refunded'];
        }

        // Check if sale is too old (configurable policy)
        $maxRefundDays = config('sales.max_refund_days', 30);
        $saleAge = now()->diffInDays($sale->sale_date);

        if ($saleAge > $maxRefundDays) {
            return [
                'can_refund' => false, 
                'reason' => "Sale is too old (>{$maxRefundDays} days)"
            ];
        }

        return ['can_refund' => true, 'reason' => null];
    }

    /**
     * Restore stock for all items in a sale
     */
    private function restoreStock(Sale $sale): void
    {
        $sale->load('items.productVariant');
        
        foreach ($sale->items as $item) {
            $item->productVariant->increaseStock($item->quantity);
        }
    }

    /**
     * Calculate refund amount for a sale
     * 
     * @param int $saleId
     * @return array Refund breakdown
     */
    public function calculateRefundAmount(int $saleId): array
    {
        $sale = $this->saleRepository->find($saleId);

        if (!$sale) {
            throw new \Exception("Sale not found");
        }

        $refundAmount = $sale->paid_amount; // Refund what was paid
        $remainingCredit = max(0, $sale->paid_amount - $sale->total); // If overpaid

        return [
            'total_paid' => $sale->paid_amount,
            'sale_total' => $sale->total,
            'refund_amount' => $refundAmount,
            'remaining_credit' => $remainingCredit,
        ];
    }

    /**
     * Process refund with payment gateway (placeholder)
     * 
     * @param int $saleId
     * @return array Gateway response
     */
    public function processRefundPayment(int $saleId): array
    {
        $sale = $this->saleRepository->find($saleId);

        if (!$sale) {
            throw new \Exception("Sale not found");
        }

        // TODO: Integrate with payment gateway
        // For now, return mock response
        return [
            'success' => true,
            'transaction_id' => 'REFUND-' . uniqid(),
            'amount' => $sale->paid_amount,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
