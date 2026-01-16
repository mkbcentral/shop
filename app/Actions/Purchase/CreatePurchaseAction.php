<?php

namespace App\Actions\Purchase;

use App\Models\Purchase;
use App\Services\PurchaseService;

class CreatePurchaseAction
{
    public function __construct(
        private PurchaseService $purchaseService
    ) {}

    /**
     * Create a new purchase with items.
     */
    public function execute(array $data): Purchase
    {
        return $this->purchaseService->createPurchaseWithItems($data);
    }
}
