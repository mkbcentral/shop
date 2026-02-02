<?php

namespace App\Actions\Sale;

use App\Dtos\Sale\RefundSaleDto;
use App\Models\Sale;
use App\Services\Sale\SaleRefundService;

/**
 * Action to refund a sale
 * 
 * Validates refund data and delegates to SaleRefundService
 */
class RefundSaleAction
{
    public function __construct(
        private SaleRefundService $refundService
    ) {}

    /**
     * Refund a sale and restore stock
     * 
     * @param int $saleId Sale to refund
     * @param array $data Refund data (reason, restore_stock)
     * @return Sale The refunded sale
     */
    public function execute(int $saleId, array $data): Sale
    {
        // Validate DTO if it exists, otherwise use direct data
        if (class_exists(RefundSaleDto::class)) {
            $dto = RefundSaleDto::fromArray(array_merge($data, ['sale_id' => $saleId]));
            $reason = $dto->reason;
            $restoreStock = $dto->restore_stock ?? true;
        } else {
            $reason = $data['reason'] ?? 'No reason provided';
            $restoreStock = $data['restore_stock'] ?? true;
        }

        // Check if refund is allowed
        if (!$this->refundService->canRefund($saleId)) {
            throw new \Exception("This sale cannot be refunded");
        }

        // Delegate to specialized service
        return $this->refundService->refundSale($saleId, $reason, $restoreStock);
    }

    /**
     * Partial refund with specific items
     */
    public function executePartial(int $saleId, array $items, string $reason, bool $restoreStock = true): Sale
    {
        return $this->refundService->partialRefund($saleId, $items, $reason, $restoreStock);
    }
}

