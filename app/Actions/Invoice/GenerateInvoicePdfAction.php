<?php

namespace App\Actions\Invoice;

use App\Services\InvoiceService;

class GenerateInvoicePdfAction
{
    public function __construct(
        private InvoiceService $invoiceService
    ) {}

    /**
     * Generate invoice PDF.
     */
    public function execute(int $invoiceId): string
    {
        return $this->invoiceService->generatePdf($invoiceId);
    }
}
