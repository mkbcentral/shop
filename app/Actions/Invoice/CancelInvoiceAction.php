<?php

namespace App\Actions\Invoice;

use App\Models\Invoice;
use App\Services\InvoiceService;

class CancelInvoiceAction
{
    public function __construct(
        private InvoiceService $invoiceService
    ) {}

    /**
     * Cancel an invoice.
     */
    public function execute(int $invoiceId): Invoice
    {
        return $this->invoiceService->cancelInvoice($invoiceId);
    }
}
