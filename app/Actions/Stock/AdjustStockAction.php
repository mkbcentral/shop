<?php

namespace App\Actions\Stock;

use App\Models\StockMovement;
use App\Services\StockService;

class AdjustStockAction
{
    public function __construct(
        private StockService $stockService
    ) {}

    /**
     * Adjust stock to a specific quantity.
     */
    public function execute(array $data): StockMovement
    {
        // Validate required fields
        if (!isset($data['product_variant_id']) || !isset($data['new_quantity']) || !isset($data['user_id'])) {
            throw new \Exception("Product variant ID, new quantity, and user ID are required");
        }

        if ($data['new_quantity'] < 0) {
            throw new \Exception("Stock quantity cannot be negative");
        }

        if (!isset($data['reason'])) {
            throw new \Exception("Reason is required for stock adjustment");
        }

        return $this->stockService->adjustStock(
            $data['product_variant_id'],
            $data['new_quantity'],
            $data['user_id'],
            $data['reason']
        );
    }
}
