<?php

namespace App\Actions\Report;

use App\Repositories\SaleRepository;
use App\Repositories\StockMovementRepository;
use App\Repositories\ProductRepository;

class GenerateSalesReportAction
{
    public function __construct(
        private SaleRepository $saleRepository,
        private StockMovementRepository $movementRepository,
        private ProductRepository $productRepository
    ) {}

    /**
     * Generate comprehensive sales report for a period.
     */
    public function execute(string $startDate, string $endDate): array
    {
        $sales = $this->saleRepository->byDateRange($startDate, $endDate);
        $completedSales = $sales->where('status', 'completed');
        $paidSales = $completedSales->where('payment_status', 'paid');

        // Sales statistics
        $salesStats = [
            'total_sales' => $sales->count(),
            'completed_sales' => $completedSales->count(),
            'cancelled_sales' => $sales->where('status', 'cancelled')->count(),
            'pending_sales' => $sales->where('status', 'pending')->count(),
        ];

        // Revenue statistics
        $revenueStats = [
            'gross_revenue' => $paidSales->sum('subtotal'),
            'total_discounts' => $paidSales->sum('discount'),
            'total_tax' => $paidSales->sum('tax'),
            'net_revenue' => $paidSales->sum('total'),
            'average_sale_value' => $paidSales->avg('total'),
        ];

        // Payment method breakdown
        $paymentMethods = [];
        foreach (['cash', 'card', 'transfer', 'cheque'] as $method) {
            $methodSales = $paidSales->where('payment_method', $method);
            $paymentMethods[$method] = [
                'count' => $methodSales->count(),
                'amount' => $methodSales->sum('total'),
            ];
        }

        // Top selling products
        $productSales = [];
        foreach ($completedSales as $sale) {
            foreach ($sale->items as $item) {
                $variantId = $item->product_variant_id;
                if (!isset($productSales[$variantId])) {
                    $productSales[$variantId] = [
                        'variant' => $item->productVariant,
                        'quantity_sold' => 0,
                        'revenue' => 0,
                    ];
                }
                $productSales[$variantId]['quantity_sold'] += $item->quantity;
                $productSales[$variantId]['revenue'] += $item->subtotal;
            }
        }

        // Sort by quantity sold
        usort($productSales, function ($a, $b) {
            return $b['quantity_sold'] - $a['quantity_sold'];
        });

        $topProducts = array_slice($productSales, 0, 10);

        // Daily breakdown
        $dailyBreakdown = $paidSales->groupBy(function ($sale) {
            return $sale->sale_date->format('Y-m-d');
        })->map(function ($daySales) {
            return [
                'sales_count' => $daySales->count(),
                'total_revenue' => $daySales->sum('total'),
            ];
        });

        return [
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'sales_statistics' => $salesStats,
            'revenue_statistics' => $revenueStats,
            'payment_methods' => $paymentMethods,
            'top_products' => $topProducts,
            'daily_breakdown' => $dailyBreakdown,
        ];
    }
}
