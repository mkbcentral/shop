<?php

namespace App\Services\Sale;

use App\Models\Sale;
use App\Repositories\SaleRepository;
use Illuminate\Support\Facades\DB;

/**
 * Service responsible for managing sale payments
 * Handles payment recording, payment status updates, and balance tracking
 */
class SalePaymentService
{
    public function __construct(
        private SaleRepository $saleRepository
    ) {}

    /**
     * Record a payment for a sale
     * 
     * @param int $saleId
     * @param array $paymentData Payment information
     * @return Sale Updated sale with payment
     * @throws \Exception If validation fails
     */
    public function recordPayment(int $saleId, array $paymentData): Sale
    {
        return DB::transaction(function () use ($saleId, $paymentData) {
            $sale = $this->saleRepository->find($saleId);

            if (!$sale) {
                throw new \Exception("Sale not found");
            }

            // Validate sale status
            if ($sale->status === Sale::STATUS_CANCELLED) {
                throw new \Exception("Cannot record payment for a cancelled sale");
            }

            if ($sale->payment_status === Sale::PAYMENT_REFUNDED) {
                throw new \Exception("Cannot record payment for a refunded sale");
            }

            // Validate payment amount
            $amount = $paymentData['amount'];

            if ($amount <= 0) {
                throw new \Exception("Payment amount must be greater than 0");
            }

            if (($sale->paid_amount + $amount) > $sale->total) {
                throw new \Exception(
                    "Payment amount exceeds remaining balance. " .
                    "Remaining: " . ($sale->total - $sale->paid_amount)
                );
            }

            // Create payment record
            $sale->payments()->create([
                'user_id' => $paymentData['user_id'],
                'amount' => $amount,
                'payment_method' => $paymentData['payment_method'],
                'payment_date' => $paymentData['payment_date'] ?? now(),
                'notes' => $paymentData['notes'] ?? null,
            ]);

            // Update sale paid_amount by summing all payments
            $sale->paid_amount = $sale->payments()->sum('amount');
            $sale->updatePaymentStatus();

            return $sale->fresh(['items.productVariant.product', 'payments']);
        });
    }

    /**
     * Update payment method for a sale
     * 
     * @param int $saleId
     * @param string $paymentMethod New payment method
     * @return Sale Updated sale
     */
    public function updatePaymentMethod(int $saleId, string $paymentMethod): Sale
    {
        $sale = $this->saleRepository->find($saleId);

        if (!$sale) {
            throw new \Exception("Sale not found");
        }

        $sale->payment_method = $paymentMethod;
        $sale->save();

        return $sale;
    }

    /**
     * Mark sale as paid (set paid_amount to total)
     * 
     * @param int $saleId
     * @return Sale Updated sale
     */
    public function markAsPaid(int $saleId): Sale
    {
        return DB::transaction(function () use ($saleId) {
            $sale = $this->saleRepository->find($saleId);

            if (!$sale) {
                throw new \Exception("Sale not found");
            }

            if ($sale->payment_status === Sale::PAYMENT_PAID) {
                throw new \Exception("Sale is already fully paid");
            }

            if ($sale->status === Sale::STATUS_CANCELLED) {
                throw new \Exception("Cannot mark a cancelled sale as paid");
            }

            // Calculate remaining amount
            $remainingAmount = $sale->total - $sale->paid_amount;

            if ($remainingAmount > 0) {
                // Create a payment for the remaining amount
                $sale->payments()->create([
                    'user_id' => auth()->id() ?? 1,  // Use 1 as fallback if not authenticated
                    'amount' => $remainingAmount,
                    'payment_method' => $sale->payment_method,
                    'payment_date' => now(),
                    'notes' => 'Payment completed',
                ]);

                // Update paid amount
                $sale->paid_amount = $sale->total;
                $sale->payment_status = Sale::PAYMENT_PAID;
                $sale->save();
            }

            return $sale->fresh(['payments']);
        });
    }

    /**
     * Get remaining balance for a sale
     * 
     * @param int $saleId
     * @return float Remaining balance
     */
    public function getRemainingBalance(int $saleId): float
    {
        $sale = $this->saleRepository->find($saleId);

        if (!$sale) {
            throw new \Exception("Sale not found");
        }

        return max(0, $sale->total - $sale->paid_amount);
    }

    /**
     * Get payment history for a sale
     * 
     * @param int $saleId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPaymentHistory(int $saleId)
    {
        $sale = $this->saleRepository->find($saleId);

        if (!$sale) {
            throw new \Exception("Sale not found");
        }

        return $sale->payments()
            ->with('user')
            ->orderBy('payment_date', 'desc')
            ->get();
    }

    /**
     * Calculate change to return to customer
     * 
     * @param int $saleId
     * @param float $amountReceived
     * @return array ['change' => float, 'remaining' => float]
     */
    public function calculateChange(int $saleId, float $amountReceived): array
    {
        $sale = $this->saleRepository->find($saleId);

        if (!$sale) {
            throw new \Exception("Sale not found");
        }

        $remaining = $sale->total - $sale->paid_amount;
        $change = max(0, $amountReceived - $remaining);

        return [
            'change' => $change,
            'remaining' => max(0, $remaining - $amountReceived),
            'amount_to_record' => min($amountReceived, $remaining)
        ];
    }

    /**
     * Void a specific payment
     * 
     * @param int $saleId
     * @param int $paymentId
     * @param string $reason
     * @return Sale Updated sale
     */
    public function voidPayment(int $saleId, int $paymentId, string $reason): Sale
    {
        return DB::transaction(function () use ($saleId, $paymentId, $reason) {
            $sale = $this->saleRepository->find($saleId);

            if (!$sale) {
                throw new \Exception("Sale not found");
            }

            $payment = $sale->payments()->find($paymentId);

            if (!$payment) {
                throw new \Exception("Payment not found for this sale");
            }

            // Mark payment as voided instead of deleting
            $payment->status = 'voided';
            $payment->notes = ($payment->notes ? $payment->notes . "\n" : '') . 
                             "Voided: " . $reason;
            $payment->save();

            // Recalculate paid amount (exclude voided payments)
            $sale->paid_amount = $sale->payments()
                ->where('status', '!=', 'voided')
                ->sum('amount');
            
            $sale->updatePaymentStatus();

            return $sale->fresh(['payments']);
        });
    }
}
