<?php

namespace App\Actions\Stock;

use App\Services\StockService;
use App\Repositories\ProductVariantRepository;

class PerformInventoryAction
{
    public function __construct(
        private StockService $stockService,
        private ProductVariantRepository $variantRepository
    ) {}

    /**
     * Perform inventory count and adjust discrepancies.
     */
    public function execute(array $inventoryCounts, int $userId): array
    {
        $results = [
            'total_counted' => count($inventoryCounts),
            'adjustments_made' => 0,
            'no_change' => 0,
            'details' => [],
        ];

        foreach ($inventoryCounts as $count) {
            $variant = $this->variantRepository->find($count['variant_id']);

            if (!$variant) {
                continue;
            }

            $systemStock = $variant->stock_quantity;
            $physicalStock = $count['counted_quantity'];
            $difference = $physicalStock - $systemStock;

            if ($difference === 0) {
                $results['no_change']++;
                $results['details'][] = [
                    'variant_id' => $variant->id,
                    'sku' => $variant->sku,
                    'system_stock' => $systemStock,
                    'physical_stock' => $physicalStock,
                    'difference' => 0,
                    'status' => 'no_change',
                ];
                continue;
            }

            try {
                // Adjust stock
                $movement = $this->stockService->adjustStock(
                    $variant->id,
                    $physicalStock,
                    $userId,
                    "Inventory count: System={$systemStock}, Physical={$physicalStock}"
                );

                $results['adjustments_made']++;
                $results['details'][] = [
                    'variant_id' => $variant->id,
                    'sku' => $variant->sku,
                    'system_stock' => $systemStock,
                    'physical_stock' => $physicalStock,
                    'difference' => $difference,
                    'status' => 'adjusted',
                    'movement_id' => $movement->id,
                ];

            } catch (\Exception $e) {
                $results['details'][] = [
                    'variant_id' => $variant->id,
                    'sku' => $variant->sku,
                    'error' => $e->getMessage(),
                    'status' => 'failed',
                ];
            }
        }

        return $results;
    }
}
