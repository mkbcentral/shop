<?php

namespace App\Actions\Invoice;

use App\Models\Invoice;
use App\Services\InvoiceService;

class CreateInvoiceAction
{
    public function __construct(
        private InvoiceService $invoiceService
    ) {}

    /**
     * Create an invoice from a sale.
     */
    public function execute(int $saleId, array $data = []): Invoice
    {
        return $this->invoiceService->createFromSale($saleId, $data);
    }
}
