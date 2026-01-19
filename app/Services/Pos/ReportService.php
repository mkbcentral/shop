<?php

declare(strict_types=1);

namespace App\Services\Pos;

use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

/**
 * Service de génération de rapports POS
 * Gère les statistiques et exports pour le point de vente
 */
class ReportService
{
    /**
     * Récupère les statistiques quotidiennes
     *
     * @param Carbon|null $date Date (défaut: aujourd'hui)
     * @param int|null $userId Filtrer par utilisateur
     * @return array{total_sales: float, transaction_count: int, average_ticket: float, payment_methods: array, hourly_distribution: array}
     */
    public function getDailyStats(?Carbon $date = null, ?int $userId = null): array
    {
        $date = $date ?? now();
        $storeId = current_store_id();
        $cacheKey = "pos_daily_stats_{$date->format('Y-m-d')}_{$userId}_{$storeId}";

        return Cache::remember($cacheKey, 3600, function () use ($date, $userId, $storeId) {
            $query = Sale::with('items')
                ->whereDate('sale_date', $date)
                ->where('status', 'completed');

            if ($userId) {
                $query->where('user_id', $userId);
            }

            // Filter by current store if user is not admin
            if (!user_can_access_all_stores() && $storeId) {
                $query->where('store_id', $storeId);
            }

            $sales = $query->get();

            $totalSales = $sales->sum('total');
            $transactionCount = $sales->count();

            return [
                'total_sales' => $totalSales,
                'transaction_count' => $transactionCount,
                'average_ticket' => $transactionCount > 0 ? $totalSales / $transactionCount : 0,
                'payment_methods' => $this->getPaymentMethodBreakdown($sales),
                'hourly_distribution' => $this->getHourlyDistribution($sales),
                'top_products' => $this->getTopProducts($date, $userId, $storeId),
            ];
        });
    }

    /**
     * Récupère les statistiques hebdomadaires
     *
     * @param Carbon|null $startDate Date de début (défaut: début de semaine)
     * @param int|null $userId Filtrer par utilisateur
     * @return array{total_sales: float, transaction_count: int, daily_breakdown: array, trend: string}
     */
    public function getWeeklyStats(?Carbon $startDate = null, ?int $userId = null): array
    {
        $startDate = $startDate ?? now()->startOfWeek();
        $endDate = $startDate->copy()->endOfWeek();
        $storeId = current_store_id();

        $query = Sale::with('items')
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->where('status', 'completed');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        // Filter by current store if user is not admin
        if (!user_can_access_all_stores() && $storeId) {
            $query->where('store_id', $storeId);
        }

        $sales = $query->get();

        $dailyBreakdown = [];
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $daySales = $sales->filter(fn($sale) => $sale->sale_date->isSameDay($date));
            $dailyBreakdown[$date->format('Y-m-d')] = [
                'date' => $date->format('d/m/Y'),
                'total' => $daySales->sum('total'),
                'count' => $daySales->count(),
            ];
        }

        return [
            'total_sales' => $sales->sum('total'),
            'transaction_count' => $sales->count(),
            'daily_breakdown' => $dailyBreakdown,
            'trend' => $this->calculateTrend($dailyBreakdown),
            'average_daily' => $sales->sum('total') / 7,
        ];
    }

    /**
     * Récupère les statistiques mensuelles
     *
     * @param int $year Année
     * @param int $month Mois (1-12)
     * @param int|null $userId Filtrer par utilisateur
     * @return array{total_sales: float, transaction_count: int, weekly_breakdown: array, best_day: array}
     */
    public function getMonthlyStats(int $year, int $month, ?int $userId = null): array
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        $storeId = current_store_id();

        $cacheKey = "pos_monthly_stats_{$year}_{$month}_{$userId}_{$storeId}";

        return Cache::remember($cacheKey, 7200, function () use ($startDate, $endDate, $userId, $storeId) {
            $query = Sale::with('items')
                ->whereBetween('sale_date', [$startDate, $endDate])
                ->where('status', 'completed');

            if ($userId) {
                $query->where('user_id', $userId);
            }

            // Filter by current store if user is not admin
            if (!user_can_access_all_stores() && $storeId) {
                $query->where('store_id', $storeId);
            }

            $sales = $query->get();

            $weeklyBreakdown = $this->getWeeklyBreakdown($sales, $startDate, $endDate);
            $bestDay = $this->getBestDay($sales);

            return [
                'total_sales' => $sales->sum('total'),
                'transaction_count' => $sales->count(),
                'average_ticket' => $sales->count() > 0 ? $sales->sum('total') / $sales->count() : 0,
                'weekly_breakdown' => $weeklyBreakdown,
                'best_day' => $bestDay,
                'payment_methods' => $this->getPaymentMethodBreakdown($sales),
            ];
        });
    }

    /**
     * Récupère le rapport de performance par utilisateur
     *
     * @param Carbon $startDate Date de début
     * @param Carbon $endDate Date de fin
     * @return array
     */
    public function getUserPerformanceReport(Carbon $startDate, Carbon $endDate): array
    {
        $query = DB::table('sales')
            ->join('users', 'sales.user_id', '=', 'users.id')
            ->whereBetween('sales.sale_date', [$startDate, $endDate])
            ->where('sales.status', 'completed');

        // Filter by current store if user is not admin
        if (!user_can_access_all_stores() && current_store_id()) {
            $query->where('sales.store_id', current_store_id());
        }

        return $query->select(
                'users.id',
                'users.name',
                DB::raw('COUNT(*) as transaction_count'),
                DB::raw('SUM(sales.total) as total_sales'),
                DB::raw('AVG(sales.total) as average_ticket'),
                DB::raw('MAX(sales.total) as highest_sale')
            )
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_sales')
            ->get()
            ->toArray();
    }

    /**
     * Récupère les produits les plus vendus
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param int $limit
     * @return array
     */
    public function getTopSellingProducts(Carbon $startDate, Carbon $endDate, int $limit = 10): array
    {
        $query = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('product_variants', 'sale_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->whereBetween('sales.sale_date', [$startDate, $endDate])
            ->where('sales.status', 'completed');

        // Filter by current store if user is not admin
        if (!user_can_access_all_stores() && current_store_id()) {
            $query->where('sales.store_id', current_store_id());
        }

        return $query->select(
                'products.name',
                'product_variants.sku',
                DB::raw('SUM(sale_items.quantity) as total_quantity'),
                DB::raw('SUM(sale_items.quantity * sale_items.unit_price) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'product_variants.sku')
            ->orderByDesc('total_quantity')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Invalide le cache des statistiques
     *
     * @param Carbon|null $date
     * @return void
     */
    public function invalidateCache(?Carbon $date = null): void
    {
        $date = $date ?? now();

        // Invalider stats quotidiennes
        Cache::forget("pos_daily_stats_{$date->format('Y-m-d')}_");

        // Invalider stats mensuelles
        Cache::forget("pos_monthly_stats_{$date->year}_{$date->month}_");
    }

    /**
     * Décomposition par méthode de paiement
     *
     * @param \Illuminate\Support\Collection $sales
     * @return array
     */
    private function getPaymentMethodBreakdown($sales): array
    {
        return $sales->groupBy('payment_method')
            ->map(fn($group) => [
                'count' => $group->count(),
                'total' => $group->sum('total'),
            ])
            ->toArray();
    }

    /**
     * Distribution des ventes par heure
     *
     * @param \Illuminate\Support\Collection $sales
     * @return array
     */
    private function getHourlyDistribution($sales): array
    {
        $distribution = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $hourSales = $sales->filter(fn($sale) => $sale->sale_date->hour === $hour);
            $distribution[$hour] = [
                'hour' => sprintf('%02d:00', $hour),
                'count' => $hourSales->count(),
                'total' => $hourSales->sum('total'),
            ];
        }
        return $distribution;
    }

    /**
     * Récupère les produits les plus vendus pour une période
     *
     * @param Carbon $date
     * @param int|null $userId
     * @param int|null $storeId
     * @return array
     */
    private function getTopProducts(Carbon $date, ?int $userId, ?int $storeId = null): array
    {
        $query = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('product_variants', 'sale_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->whereDate('sales.sale_date', $date)
            ->where('sales.status', 'completed');

        if ($userId) {
            $query->where('sales.user_id', $userId);
        }

        // Filter by store
        if (!user_can_access_all_stores() && $storeId) {
            $query->where('sales.store_id', $storeId);
        }

        return $query
            ->select(
                'products.name',
                DB::raw('SUM(sale_items.quantity) as quantity_sold'),
                DB::raw('SUM(sale_items.quantity * sale_items.unit_price) as revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('quantity_sold')
            ->limit(5)
            ->get()
            ->toArray();
    }

    /**
     * Décomposition hebdomadaire
     *
     * @param \Illuminate\Support\Collection $sales
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    private function getWeeklyBreakdown($sales, Carbon $startDate, Carbon $endDate): array
    {
        $weeks = [];
        $currentWeek = 1;

        for ($date = $startDate->copy(); $date <= $endDate; $date->addWeek()) {
            $weekEnd = $date->copy()->endOfWeek();
            if ($weekEnd > $endDate) {
                $weekEnd = $endDate;
            }

            $weekSales = $sales->filter(function ($sale) use ($date, $weekEnd) {
                return $sale->sale_date->between($date, $weekEnd);
            });

            $weeks["week_$currentWeek"] = [
                'start' => $date->format('d/m'),
                'end' => $weekEnd->format('d/m'),
                'total' => $weekSales->sum('total'),
                'count' => $weekSales->count(),
            ];

            $currentWeek++;
        }

        return $weeks;
    }

    /**
     * Trouve le meilleur jour du mois
     *
     * @param \Illuminate\Support\Collection $sales
     * @return array
     */
    private function getBestDay($sales): array
    {
        $dailyTotals = $sales->groupBy(fn($sale) => $sale->sale_date->format('Y-m-d'))
            ->map(fn($group) => [
                'date' => $group->first()->sale_date->format('d/m/Y'),
                'total' => $group->sum('total'),
                'count' => $group->count(),
            ])
            ->sortByDesc('total');

        return $dailyTotals->first() ?? [
            'date' => null,
            'total' => 0,
            'count' => 0,
        ];
    }

    /**
     * Calcule la tendance (hausse/baisse)
     *
     * @param array $dailyBreakdown
     * @return string
     */
    private function calculateTrend(array $dailyBreakdown): string
    {
        $values = array_column($dailyBreakdown, 'total');

        if (count($values) < 2) {
            return 'stable';
        }

        $firstHalf = array_sum(array_slice($values, 0, (int)(count($values) / 2)));
        $secondHalf = array_sum(array_slice($values, (int)(count($values) / 2)));

        if ($secondHalf > $firstHalf * 1.1) {
            return 'hausse';
        } elseif ($secondHalf < $firstHalf * 0.9) {
            return 'baisse';
        }

        return 'stable';
    }
}
