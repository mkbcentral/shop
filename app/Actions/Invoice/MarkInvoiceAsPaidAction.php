<?php

namespace App\Actions\Invoice;

use App\Models\Invoice;
use App\Services\InvoiceService;

class MarkInvoiceAsPaidAction
{
    public function __construct(
        private InvoiceService $invoiceService
    ) {}

    /**
     * Mark invoice as paid.
     */
    public function execute(int $invoiceId): Invoice
    {
        return $this->invoiceService->markAsPaid($invoiceId);
    }
}
