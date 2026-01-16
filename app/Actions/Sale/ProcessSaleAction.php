<?php

namespace App\Actions\Sale;

use App\Models\Sale;
use App\Services\SaleService;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\DB;

class ProcessSaleAction
{
    public function __construct(
        private SaleService $saleService,
        private InvoiceService $invoiceService
    ) {}

    /**
     * Process a complete sale with invoice generation.
     */
    public function execute(array $data): array
    {
        return DB::transaction(function () use ($data) {
            // Create the sale
            $sale = $this->saleService->createSale($data);

            // Complete the sale (mark as completed and create stock movements)
            if (isset($data['complete']) && $data['complete']) {
                $sale = $this->saleService->completeSale($sale->id);
            }

            // Generate invoice if requested
            $invoice = null;
            if (isset($data['generate_invoice']) && $data['generate_invoice']) {
                $invoiceData = [
                    'invoice_date' => $data['invoice_date'] ?? now(),
                    'due_date' => $data['due_date'] ?? null,
                    'status' => $data['invoice_status'] ?? 'sent',
                ];

                $invoice = $this->invoiceService->createFromSale($sale->id, $invoiceData);
            }

            return [
                'sale' => $sale,
                'invoice' => $invoice,
                'message' => 'Sale processed successfully',
            ];
        });
    }
}
