<?php

namespace App\Actions\Purchase;

use App\Models\Purchase;
use App\Services\PurchaseService;

class UpdatePurchaseAction
{
    public function __construct(
        private PurchaseService $purchaseService
    ) {}

    /**
     * Update a purchase.
     */
    public function execute(int $purchaseId, array $data): Purchase
    {
        return $this->purchaseService->updatePurchase($purchaseId, $data);
    }
}
