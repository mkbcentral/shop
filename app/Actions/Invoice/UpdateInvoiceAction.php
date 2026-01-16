<?php

namespace App\Actions\Invoice;

use App\Models\Invoice;
use App\Services\InvoiceService;

class UpdateInvoiceAction
{
    public function __construct(
        private InvoiceService $invoiceService
    ) {}

    /**
     * Update an invoice.
     */
    public function execute(int $invoiceId, array $data): Invoice
    {
        return $this->invoiceService->updateInvoice($invoiceId, $data);
    }
}
