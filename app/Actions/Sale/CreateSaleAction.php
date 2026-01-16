<?php

namespace App\Actions\Sale;

use App\Models\Sale;
use App\Services\SaleService;

class CreateSaleAction
{
    public function __construct(
        private SaleService $saleService
    ) {}

    /**
     * Create a new sale.
     */
    public function execute(array $data): Sale
    {
        // Validate required fields
        if (!isset($data['user_id'])) {
            throw new \Exception("User ID is required");
        }

        if (!isset($data['payment_method'])) {
            throw new \Exception("Payment method is required");
        }

        if (!isset($data['items']) || empty($data['items'])) {
            throw new \Exception("Sale must have at least one item");
        }

        return $this->saleService->createSale($data);
    }
}
