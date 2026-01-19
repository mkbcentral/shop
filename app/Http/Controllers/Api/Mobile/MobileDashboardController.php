<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Services\Mobile\MobileReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller API Mobile - Dashboard
 *
 * Fournit les endpoints pour le tableau de bord mobile
 */
class MobileDashboardController extends Controller
{
    public function __construct(
        private MobileReportService $reportService
    ) {}

    /**
     * Dashboard principal
     *
     * GET /api/mobile/dashboard
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $data = $this->reportService->getDashboardData($user);

            return response()->json([
                'success' => true,
                'data' => $data,
                'timestamp' => now()->toIso8601String(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du dashboard',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Contexte utilisateur (organisation, stores, rôle)
     *
     * GET /api/mobile/context
     */
    public function userContext(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $context = $this->reportService->getUserContext($user);

            return response()->json([
                'success' => true,
                'data' => $context,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du contexte',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Stores accessibles par l'utilisateur
     *
     * GET /api/mobile/stores
     */
    public function stores(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $stores = $this->reportService->getAccessibleStores($user);

            return response()->json([
                'success' => true,
                'data' => [
                    'stores' => $stores,
                    'current_store_id' => current_store_id(),
                    'can_switch' => user_can_access_all_stores(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des stores',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Changer de store actif
     *
     * POST /api/mobile/switch-store/{storeId}
     */
    public function switchStore(Request $request, int $storeId): JsonResponse
    {
        try {
            $user = Auth::user();

            // Vérifier que l'utilisateur a accès à ce store
            $accessibleStores = collect($this->reportService->getAccessibleStores($user));
            $hasAccess = $accessibleStores->contains('id', $storeId);

            if (!$hasAccess) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'avez pas accès à ce magasin',
                ], 403);
            }

            // Mettre à jour le store courant de l'utilisateur
            $user->update(['current_store_id' => $storeId]);

            // Invalider le cache
            $this->reportService->invalidateCache($user);

            return response()->json([
                'success' => true,
                'message' => 'Magasin changé avec succès',
                'data' => [
                    'current_store_id' => $storeId,
                    'context' => $this->reportService->getUserContext($user->fresh()),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du changement de magasin',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Performance des stores (admin/manager uniquement)
     *
     * GET /api/mobile/stores-performance
     */
    public function storesPerformance(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();

            if (!user_can_access_all_stores()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé',
                ], 403);
            }

            $performance = $this->reportService->getStoresPerformance($user);

            return response()->json([
                'success' => true,
                'data' => [
                    'stores' => $performance,
                    'period' => 'today',
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des performances',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Rafraîchir le cache
     *
     * POST /api/mobile/refresh
     */
    public function refresh(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $this->reportService->invalidateCache($user);

            return response()->json([
                'success' => true,
                'message' => 'Cache invalidé avec succès',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du rafraîchissement',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
