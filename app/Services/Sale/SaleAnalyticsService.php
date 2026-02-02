<?php

namespace App\Services\Sale;

use App\Models\Sale;
use App\Repositories\SaleRepository;
use Illuminate\Support\Facades\DB;

/**
 * Service responsible for sale analytics and statistics
 * Provides reporting, summaries, and sales insights
 */
class SaleAnalyticsService
{
    public function __construct(
        private SaleRepository $saleRepository
    ) {}

    /**
     * Get sales statistics for a date range
     * 
     * @param string $startDate
     * @param string $endDate
     * @return array Statistics
     */
    public function getSalesStatistics(string $startDate, string $endDate): array
    {
        return $this->saleRepository->statistics($startDate, $endDate);
    }

    /**
     * Get today's sales summary
     * 
     * @return array Summary data
     */
    public function getTodaySummary(): array
    {
        $sales = $this->saleRepository->today();

        return [
            'total_sales' => $sales->count(),
            'completed_sales' => $sales->where('status', Sale::STATUS_COMPLETED)->count(),
            'pending_sales' => $sales->where('status', Sale::STATUS_PENDING)->count(),
            'cancelled_sales' => $sales->where('status', Sale::STATUS_CANCELLED)->count(),
            'total_amount' => $sales->where('status', Sale::STATUS_COMPLETED)->sum('total'),
            'pending_amount' => $sales->where('status', Sale::STATUS_PENDING)->sum('total'),
            'paid_amount' => $sales->sum('paid_amount'),
        ];
    }

    /**
     * Get sales by payment method
     * 
     * @param string $startDate
     * @param string $endDate
     * @return array Payment method breakdown
     */
    public function getSalesByPaymentMethod(string $startDate, string $endDate): array
    {
        $sales = Sale::whereBetween('sale_date', [$startDate, $endDate])
            ->where('status', Sale::STATUS_COMPLETED)
            ->get()
            ->groupBy('payment_method');

        $result = [];
        foreach ($sales as $method => $salesGroup) {
            $result[$method] = [
                'count' => $salesGroup->count(),
                'total' => $salesGroup->sum('total'),
                'avg' => $salesGroup->avg('total'),
            ];
        }

        return $result;
    }

    /**
     * Get top selling products
     * 
     * @param string $startDate
     * @param string $endDate
     * @param int $limit
     * @return array Top products
     */
    public function getTopSellingProducts(string $startDate, string $endDate, int $limit = 10): array
    {
        return \App\Models\SaleItem::query()
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('product_variants', 'sale_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->whereBetween('sales.sale_date', [$startDate, $endDate])
            ->where('sales.status', Sale::STATUS_COMPLETED)
            ->select(
                'products.id',
                'products.name',
                DB::raw('SUM(sale_items.quantity) as total_quantity'),
                DB::raw('SUM(sale_items.quantity * sale_items.unit_price) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_quantity')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get sales by store
     * 
     * @param string $startDate
     * @param string $endDate
     * @return array Store breakdown
     */
    public function getSalesByStore(string $startDate, string $endDate): array
    {
        $sales = Sale::with('store')
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->where('status', Sale::STATUS_COMPLETED)
            ->get()
            ->groupBy('store_id');

        $result = [];
        foreach ($sales as $storeId => $salesGroup) {
            $store = $salesGroup->first()->store;
            $result[] = [
                'store_id' => $storeId,
                'store_name' => $store->name ?? 'Unknown',
                'count' => $salesGroup->count(),
                'total' => $salesGroup->sum('total'),
                'avg' => $salesGroup->avg('total'),
            ];
        }

        return $result;
    }

    /**
     * Get sales performance by user
     * 
     * @param string $startDate
     * @param string $endDate
     * @return array User performance
     */
    public function getSalesByUser(string $startDate, string $endDate): array
    {
        $sales = Sale::with('user')
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->where('status', Sale::STATUS_COMPLETED)
            ->get()
            ->groupBy('user_id');

        $result = [];
        foreach ($sales as $userId => $salesGroup) {
            $user = $salesGroup->first()->user;
            $result[] = [
                'user_id' => $userId,
                'user_name' => $user->name ?? 'Unknown',
                'count' => $salesGroup->count(),
                'total' => $salesGroup->sum('total'),
                'avg' => $salesGroup->avg('total'),
            ];
        }

        return collect($result)->sortByDesc('total')->values()->toArray();
    }

    /**
     * Get hourly sales distribution
     * 
     * @param string $date
     * @return array Hourly breakdown
     */
    public function getHourlySales(string $date): array
    {
        $sales = Sale::whereDate('sale_date', $date)
            ->where('status', Sale::STATUS_COMPLETED)
            ->get();

        $hourlyData = array_fill(0, 24, ['count' => 0, 'total' => 0]);

        foreach ($sales as $sale) {
            $hour = (int) $sale->sale_date->format('H');
            $hourlyData[$hour]['count']++;
            $hourlyData[$hour]['total'] += $sale->total;
        }

        return $hourlyData;
    }

    /**
     * Get average sale value
     * 
     * @param string $startDate
     * @param string $endDate
     * @return float Average sale value
     */
    public function getAverageSaleValue(string $startDate, string $endDate): float
    {
        return Sale::whereBetween('sale_date', [$startDate, $endDate])
            ->where('status', Sale::STATUS_COMPLETED)
            ->avg('total') ?? 0;
    }

    /**
     * Get conversion rate (completed vs total)
     * 
     * @param string $startDate
     * @param string $endDate
     * @return array Conversion metrics
     */
    public function getConversionRate(string $startDate, string $endDate): array
    {
        $total = Sale::whereBetween('sale_date', [$startDate, $endDate])->count();
        $completed = Sale::whereBetween('sale_date', [$startDate, $endDate])
            ->where('status', Sale::STATUS_COMPLETED)
            ->count();

        $rate = $total > 0 ? ($completed / $total) * 100 : 0;

        return [
            'total_sales' => $total,
            'completed_sales' => $completed,
            'conversion_rate' => round($rate, 2),
        ];
    }

    /**
     * Get monthly sales comparison
     * 
     * @param int $months Number of months to compare
     * @return array Monthly comparison
     */
    public function getMonthlySalesComparison(int $months = 6): array
    {
        $result = [];

        for ($i = 0; $i < $months; $i++) {
            $date = now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            $sales = Sale::whereBetween('sale_date', [$startOfMonth, $endOfMonth])
                ->where('status', Sale::STATUS_COMPLETED)
                ->get();

            $result[] = [
                'month' => $date->format('Y-m'),
                'month_name' => $date->format('F Y'),
                'count' => $sales->count(),
                'total' => $sales->sum('total'),
                'avg' => $sales->avg('total') ?? 0,
            ];
        }

        return array_reverse($result);
    }
}
