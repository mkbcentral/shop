<?php

namespace App\Actions\Invoice;

use App\Services\InvoiceService;

class DeleteInvoiceAction
{
    public function __construct(
        private InvoiceService $invoiceService
    ) {}

    /**
     * Delete an invoice (cancel it).
     */
    public function execute(int $invoiceId): bool
    {
        $this->invoiceService->cancelInvoice($invoiceId);
        return true;
    }
}
