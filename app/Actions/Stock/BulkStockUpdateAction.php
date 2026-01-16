<?php

namespace App\Actions\Stock;

use App\Services\StockService;
use Illuminate\Support\Facades\DB;

class BulkStockUpdateAction
{
    public function __construct(
        private StockService $stockService
    ) {}

    /**
     * Update stock for multiple variants at once.
     */
    public function execute(array $updates, int $userId): array
    {
        return DB::transaction(function () use ($updates, $userId) {
            $results = [
                'success' => [],
                'failed' => [],
            ];

            foreach ($updates as $update) {
                try {
                    $variantId = $update['variant_id'];
                    $newStock = $update['quantity'];

                    // Get current stock through service
                    $variant = $this->stockService->getVariantStock($variantId);

                    if (!$variant) {
                        $results['failed'][] = [
                            'variant_id' => $variantId,
                            'error' => 'Variant not found',
                        ];
                        continue;
                    }

                    $currentStock = $variant->stock_quantity;

                    // Create stock movement
                    $movement = $this->stockService->adjustStock(
                        $variantId,
                        $newStock,
                        $userId,
                        $update['reason'] ?? 'Bulk stock update'
                    );

                    $results['success'][] = [
                        'variant_id' => $variantId,
                        'sku' => $variant->sku,
                        'old_quantity' => $currentStock,
                        'new_quantity' => $newStock,
                        'movement_id' => $movement->id,
                    ];

                } catch (\Exception $e) {
                    $results['failed'][] = [
                        'variant_id' => $update['variant_id'] ?? null,
                        'error' => $e->getMessage(),
                    ];
                }
            }

            return $results;
        });
    }
}
