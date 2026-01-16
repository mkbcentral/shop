<?php

namespace App\Actions\Purchase;

use App\Services\PurchaseService;

class DeletePurchaseAction
{
    public function __construct(
        private PurchaseService $purchaseService
    ) {}

    /**
     * Delete a purchase.
     */
    public function execute(int $purchaseId): bool
    {
        return $this->purchaseService->deletePurchase($purchaseId);
    }
}
