<?php

namespace App\Actions\Sale;

use App\Dtos\Sale\RecordPaymentDto;
use App\Models\Sale;
use App\Services\Sale\SalePaymentService;

/**
 * Action to record a payment for a sale
 * 
 * Validates payment data via DTO and delegates to SalePaymentService
 */
class RecordPaymentAction
{
    public function __construct(
        private SalePaymentService $paymentService
    ) {}

    /**
     * Execute the action to record a payment
     * 
     * @param int $saleId The sale ID
     * @param array $paymentData Payment data
     * @return Sale The updated sale
     */
    public function execute(int $saleId, array $paymentData): Sale
    {
        // Create and validate DTO
        $dto = RecordPaymentDto::fromArray(array_merge(
            $paymentData,
            ['sale_id' => $saleId]
        ));

        // Delegate to specialized service
        return $this->paymentService->recordPayment($saleId, $dto->toArray());
    }

    /**
     * Execute from validated request
     */
    public function executeFromRequest($request, int $saleId): Sale
    {
        $dto = RecordPaymentDto::fromRequest($request, $saleId);
        
        return $this->paymentService->recordPayment($saleId, $dto->toArray());
    }
}

