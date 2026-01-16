<?php

namespace App\Actions\Invoice;

use App\Models\Invoice;
use App\Services\InvoiceService;

class SendInvoiceAction
{
    public function __construct(
        private InvoiceService $invoiceService
    ) {}

    /**
     * Send invoice (change status to sent).
     */
    public function execute(int $invoiceId): Invoice
    {
        return $this->invoiceService->sendInvoice($invoiceId);
    }
}
