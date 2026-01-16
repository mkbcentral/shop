<?php

namespace App\Services;

use App\Repositories\DashboardRepository;
use Carbon\Carbon;

class DashboardService
{
    public function __construct(
        protected DashboardRepository $repository
    ) {}

    /**
     * Get all dashboard statistics
     */
    public function getDashboardStats(): array
    {
        return [
            'kpis' => $this->getKPIs(),
            'sales' => $this->getSalesStats(),
            'stock' => $this->getStockStats(),
            'recent_data' => $this->getRecentData(),
        ];
    }

    /**
     * Get Key Performance Indicators
     */
    public function getKPIs(): array
    {
        return [
            'total_products' => $this->repository->getTotalProducts(),
            'total_clients' => $this->repository->getTotalClients(),
            'total_suppliers' => $this->repository->getTotalSuppliers(),
        ];
    }

    /**
     * Get sales statistics
     */
    public function getSalesStats(): array
    {
        $todaySales = $this->repository->getTodaySales();
        $monthSales = $this->repository->getMonthSales(now());
        $lastMonthSales = $this->repository->getMonthSales(now()->subMonth());

        $salesGrowth = $lastMonthSales > 0
            ? (($monthSales - $lastMonthSales) / $lastMonthSales) * 100
            : 0;

        return [
            'today_sales' => $todaySales,
            'month_sales' => $monthSales,
            'total_sales' => $this->repository->getTotalSalesCount(),
            'sales_growth' => $salesGrowth,
            'chart_data' => $this->getSalesChartData(7),
        ];
    }

    /**
     * Get stock statistics
     */
    public function getStockStats(): array
    {
        return [
            'low_stock_alerts' => $this->repository->getLowStockCount(),
            'out_of_stock_alerts' => $this->repository->getOutOfStockCount(),
            'total_stock_value' => $this->repository->getTotalStockValue(),
        ];
    }

    /**
     * Get recent data (sales, movements, top products)
     */
    public function getRecentData(): array
    {
        return [
            'recent_sales' => $this->repository->getRecentSales(10),
            'recent_movements' => $this->repository->getRecentStockMovements(5),
            'top_products' => $this->repository->getTopSellingProducts(5, 30),
        ];
    }

    /**
     * Get sales chart data for the last N days
     */
    public function getSalesChartData(int $days = 7): \Illuminate\Support\Collection
    {
        $chartData = collect();

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $total = $this->repository->getSalesByDate($date);

            $chartData->push((object)[
                'day' => $date,
                'total' => $total
            ]);
        }

        return $chartData;
    }

    /**
     * Get sales comparison between two periods
     */
    public function getSalesComparison(Carbon $startDate1, Carbon $endDate1, Carbon $startDate2, Carbon $endDate2): array
    {
        $period1Sales = $this->repository->getSalesBetweenDates($startDate1, $endDate1);
        $period2Sales = $this->repository->getSalesBetweenDates($startDate2, $endDate2);

        $growth = $period2Sales > 0
            ? (($period1Sales - $period2Sales) / $period2Sales) * 100
            : 0;

        return [
            'period1_sales' => $period1Sales,
            'period2_sales' => $period2Sales,
            'growth_percentage' => $growth,
        ];
    }
}
