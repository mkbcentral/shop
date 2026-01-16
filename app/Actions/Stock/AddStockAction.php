<?php

namespace App\Actions\Stock;

use App\Models\StockMovement;
use App\Services\StockService;

class AddStockAction
{
    public function __construct(
        private StockService $stockService
    ) {}

    /**
     * Add stock (IN movement).
     */
    public function execute(array $data): StockMovement
    {
        // Validate required fields
        if (!isset($data['product_variant_id']) || !isset($data['quantity']) || !isset($data['user_id'])) {
            throw new \Exception("Product variant ID, quantity, and user ID are required");
        }

        if ($data['quantity'] <= 0) {
            throw new \Exception("Quantity must be greater than 0");
        }

        return $this->stockService->addStock($data);
    }
}
