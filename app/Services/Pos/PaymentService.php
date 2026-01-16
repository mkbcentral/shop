<?php

declare(strict_types=1);

namespace App\Services\Pos;

use App\Actions\Sale\CreateSaleAction;
use App\Actions\Invoice\CreateInvoiceAction;
use App\Events\Pos\SaleCompleted;
use App\Events\Pos\PaymentReceived;
use App\Exceptions\Pos\CartEmptyException;
use App\Exceptions\Pos\InsufficientPaymentException;
use App\Exceptions\Pos\InsufficientStockException;
use App\Models\Sale;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Service responsable du traitement des paiements POS
 */
class PaymentService
{
    public function __construct(
        private readonly CreateSaleAction $createSaleAction,
        private readonly CreateInvoiceAction $createInvoiceAction,
        private readonly CalculationService $calculationService
    ) {}

    /**
     * Traite un paiement complet (vente + facture)
     *
     * @throws CartEmptyException
     * @throws InsufficientPaymentException
     * @throws InsufficientStockException
     */
    public function process(PaymentData $data): PaymentResult
    {
        try {
            // Validation du panier
            if (empty($data->items)) {
                throw new CartEmptyException();
            }

            // Validation du montant payé
            $this->validatePaymentAmount($data->paidAmount, $data->total);

            // Validation du stock
            $this->validateStock($data->stockValidation);

            // Transaction atomique
            DB::beginTransaction();

            try {
                // Créer la vente
                $sale = $this->createSale($data);

                // Créer la facture
                $invoice = $this->createInvoice($sale);

                DB::commit();

                // Calculer la monnaie rendue
                $change = $this->calculationService->calculateChange(
                    $data->paidAmount,
                    $data->total
                );

                // Déclencher les événements métier
                event(new PaymentReceived($sale, $data->paymentMethod, $data->paidAmount));
                event(new SaleCompleted($sale, $invoice, $change));

                return PaymentResult::success($sale, $invoice, $change);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (CartEmptyException | InsufficientPaymentException | InsufficientStockException $e) {
            Log::warning('Payment validation failed', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'user_id' => $data->userId
            ]);
            throw $e;

        } catch (\Exception $e) {
            Log::error('Payment processing error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $data->userId
            ]);
            throw new \RuntimeException('Erreur lors du traitement du paiement: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Valide que le montant payé est suffisant
     */
    private function validatePaymentAmount(float $paidAmount, float $total): void
    {
        if ($paidAmount < $total) {
            throw new InsufficientPaymentException($total, $paidAmount);
        }
    }

    /**
     * Valide le stock disponible
     */
    private function validateStock(array $stockValidation): void
    {
        if (!$stockValidation['valid']) {
            throw new InsufficientStockException(
                $stockValidation['product_name'] ?? 'Produit',
                $stockValidation['requested'] ?? 0,
                $stockValidation['available'] ?? 0
            );
        }
    }

    /**
     * Crée la vente
     */
    private function createSale(PaymentData $data): Sale
    {
        $saleData = [
            'user_id' => $data->userId,
            'client_id' => $data->clientId,
            'store_id' => $data->storeId ?? current_store_id(),
            'payment_method' => $data->paymentMethod,
            'items' => $data->items,
            'discount' => $data->discount,
            'tax' => $data->tax,
            'paid_amount' => $data->paidAmount,
            'payment_status' => 'paid',
            'status' => 'completed',
            'notes' => $data->notes,
        ];

        return $this->createSaleAction->execute($saleData);
    }

    /**
     * Crée la facture associée à la vente
     */
    private function createInvoice(Sale $sale): Invoice
    {
        return $this->createInvoiceAction->execute($sale->id, [
            'invoice_date' => now(),
            'status' => 'paid',
        ]);
    }
}
