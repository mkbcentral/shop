<?php

namespace App\Livewire;

use App\Services\DashboardService;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $dashboardService = app(DashboardService::class);

        // Get all statistics
        $kpis = $dashboardService->getKPIs();
        $salesStats = $dashboardService->getSalesStats();
        $stockStats = $dashboardService->getStockStats();
        $recentData = $dashboardService->getRecentData();

        return view('livewire.dashboard', [
            // KPIs
            'total_products' => $kpis['total_products'],
            'total_clients' => $kpis['total_clients'],
            'total_suppliers' => $kpis['total_suppliers'],

            // Sales
            'today_sales' => $salesStats['today_sales'],
            'month_sales' => $salesStats['month_sales'],
            'total_sales' => $salesStats['total_sales'],
            'sales_growth' => $salesStats['sales_growth'],
            'sales_chart_data' => $salesStats['chart_data'],

            // Stock
            'low_stock_alerts' => $stockStats['low_stock_alerts'],
            'out_of_stock_alerts' => $stockStats['out_of_stock_alerts'],
            'total_stock_value' => $stockStats['total_stock_value'],

            // Recent Data
            'recent_sales' => $recentData['recent_sales'],
            'recent_movements' => $recentData['recent_movements'],
            'top_products' => $recentData['top_products'],
        ]);
    }
}
