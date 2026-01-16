<?php

namespace App\Actions\Report;

use App\Repositories\ProductRepository;
use App\Repositories\ProductVariantRepository;
use App\Repositories\StockMovementRepository;

class GenerateStockReportAction
{
    public function __construct(
        private ProductRepository $productRepository,
        private ProductVariantRepository $variantRepository,
        private StockMovementRepository $movementRepository
    ) {}

    /**
     * Generate comprehensive stock report.
     */
    public function execute(array $options = []): array
    {
        $includeMovements = $options['include_movements'] ?? false;
        $startDate = $options['start_date'] ?? null;
        $endDate = $options['end_date'] ?? null;

        // Current stock levels
        $inStock = $this->variantRepository->inStock();
        $outOfStock = $this->variantRepository->outOfStock();
        $lowStock = $this->variantRepository->lowStock($options['low_stock_threshold'] ?? 10);

        // Stock value calculation
        $totalStockValue = $inStock->sum(function ($variant) {
            return $variant->stock_quantity * $variant->product->cost_price;
        });

        $retailValue = $inStock->sum(function ($variant) {
            return $variant->stock_quantity * $variant->final_price;
        });

        // Stock statistics
        $stockStats = [
            'total_variants' => $inStock->count() + $outOfStock->count(),
            'in_stock_variants' => $inStock->count(),
            'out_of_stock_variants' => $outOfStock->count(),
            'low_stock_variants' => $lowStock->count(),
            'total_stock_units' => $inStock->sum('stock_quantity'),
            'total_stock_value' => round($totalStockValue, 2),
            'total_retail_value' => round($retailValue, 2),
            'potential_profit' => round($retailValue - $totalStockValue, 2),
        ];

        // Product breakdown
        $productBreakdown = $this->productRepository->all()->map(function ($product) {
            $totalStock = $product->variants->sum('stock_quantity');
            $stockValue = $product->variants->sum(function ($variant) use ($product) {
                return $variant->stock_quantity * $product->cost_price;
            });

            return [
                'product_id' => $product->id,
                'name' => $product->name,
                'reference' => $product->reference,
                'variants_count' => $product->variants->count(),
                'total_stock' => $totalStock,
                'stock_value' => round($stockValue, 2),
                'variants' => $product->variants->map(fn ($variant) => [
                    'id' => $variant->id,
                    'sku' => $variant->sku,
                    'size' => $variant->size,
                    'color' => $variant->color,
                    'stock_quantity' => $variant->stock_quantity,
                    'value' => $variant->stock_quantity * $product->cost_price,
                ]),
            ];
        });

        $report = [
            'generated_at' => now()->toDateTimeString(),
            'stock_statistics' => $stockStats,
            'out_of_stock_items' => $outOfStock->map(function ($variant) {
                return [
                    'sku' => $variant->sku,
                    'product' => $variant->product->name,
                    'size' => $variant->size,
                    'color' => $variant->color,
                ];
            }),
            'low_stock_items' => $lowStock->map(function ($variant) {
                return [
                    'sku' => $variant->sku,
                    'product' => $variant->product->name,
                    'current_stock' => $variant->stock_quantity,
                    'size' => $variant->size,
                    'color' => $variant->color,
                ];
            }),
            'product_breakdown' => $productBreakdown,
        ];

        // Add movement statistics if requested
        if ($includeMovements && $startDate && $endDate) {
            $movements = $this->movementRepository->byDateRange($startDate, $endDate);

            $report['movement_statistics'] = [
                'period' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ],
                'total_in' => $movements->where('type', 'in')->sum('quantity'),
                'total_out' => $movements->where('type', 'out')->sum('quantity'),
                'by_type' => [
                    'purchases' => $movements->where('movement_type', 'purchase')->sum('quantity'),
                    'sales' => $movements->where('movement_type', 'sale')->sum('quantity'),
                    'adjustments' => $movements->where('movement_type', 'adjustment')->count(),
                    'transfers' => $movements->where('movement_type', 'transfer')->sum('quantity'),
                    'returns' => $movements->where('movement_type', 'return')->sum('quantity'),
                ],
            ];
        }

        return $report;
    }
}
