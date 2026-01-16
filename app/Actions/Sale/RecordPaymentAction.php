<?php

namespace App\Actions\Sale;

use App\Models\Sale;
use App\Services\SaleService;

class RecordPaymentAction
{
    public function __construct(
        private SaleService $saleService
    ) {}

    /**
     * Execute the action to record a payment.
     */
    public function execute(int $saleId, array $paymentData): Sale
    {
        return $this->saleService->recordPayment($saleId, $paymentData);
    }
}
