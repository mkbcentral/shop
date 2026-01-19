<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Services\Mobile\MobileReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller API Mobile - Rapports de Ventes
 *
 * Fournit les endpoints pour les statistiques et graphiques de ventes
 */
class MobileSalesReportController extends Controller
{
    public function __construct(
        private MobileReportService $reportService
    ) {}

    /**
     * Résumé des ventes
     *
     * GET /api/mobile/sales/summary
     */
    public function summary(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $data = $this->reportService->getSalesSummary($user);

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du résumé des ventes',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Rapport des ventes du jour
     *
     * GET /api/mobile/sales/daily
     */
    public function daily(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $data = $this->reportService->getSalesReport($user, 'daily');

            return response()->json([
                'success' => true,
                'data' => $data,
                'period' => 'daily',
                'date' => now()->format('Y-m-d'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du rapport journalier',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Rapport des ventes de la semaine
     *
     * GET /api/mobile/sales/weekly
     */
    public function weekly(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $data = $this->reportService->getSalesReport($user, 'weekly');

            return response()->json([
                'success' => true,
                'data' => $data,
                'period' => 'weekly',
                'week' => now()->weekOfYear,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du rapport hebdomadaire',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Rapport des ventes du mois
     *
     * GET /api/mobile/sales/monthly
     */
    public function monthly(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $data = $this->reportService->getSalesReport($user, 'monthly');

            return response()->json([
                'success' => true,
                'data' => $data,
                'period' => 'monthly',
                'month' => now()->format('Y-m'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du rapport mensuel',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Données pour graphique de ventes
     *
     * GET /api/mobile/sales/chart/{period}
     *
     * @param string $period week|month|quarter|year
     */
    public function chart(Request $request, string $period = 'week'): JsonResponse
    {
        try {
            $user = Auth::user();

            // Valider la période
            $validPeriods = ['week', 'month', 'quarter', 'year'];
            if (!in_array($period, $validPeriods)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Période invalide. Utilisez: ' . implode(', ', $validPeriods),
                ], 400);
            }

            $days = $request->input('days');
            $data = $this->reportService->getSalesChart($user, $period, $days ? (int) $days : null);

            return response()->json([
                'success' => true,
                'data' => [
                    'chart' => $data,
                    'period' => $period,
                    'chart_type' => 'line',
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des données du graphique',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Top produits vendus
     *
     * GET /api/mobile/sales/top-products
     */
    public function topProducts(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $limit = (int) $request->input('limit', 10);
            $days = (int) $request->input('days', 30);

            // Limiter les valeurs
            $limit = min(max($limit, 5), 50);
            $days = min(max($days, 7), 365);

            $data = $this->reportService->getTopProducts($user, $limit, $days);

            return response()->json([
                'success' => true,
                'data' => [
                    'products' => $data,
                    'limit' => $limit,
                    'days' => $days,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des top produits',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Ventes par magasin (admin/manager uniquement)
     *
     * GET /api/mobile/sales/by-store
     */
    public function byStore(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();

            if (!user_can_access_all_stores()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé - Réservé aux administrateurs et managers',
                ], 403);
            }

            $period = $request->input('period', 'month');
            $validPeriods = ['day', 'week', 'month', 'quarter', 'year'];

            if (!in_array($period, $validPeriods)) {
                $period = 'month';
            }

            $data = $this->reportService->getSalesByStore($user, $period);

            // Calculer les totaux
            $totalSales = collect($data)->sum('total_sales');
            $totalCount = collect($data)->sum('sales_count');

            return response()->json([
                'success' => true,
                'data' => [
                    'stores' => $data,
                    'totals' => [
                        'sales' => $totalSales,
                        'count' => $totalCount,
                    ],
                    'period' => $period,
                    'chart' => [
                        'type' => 'pie',
                        'labels' => collect($data)->pluck('name')->toArray(),
                        'values' => collect($data)->pluck('percentage')->toArray(),
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des ventes par magasin',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
