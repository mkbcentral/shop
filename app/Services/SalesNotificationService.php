<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Sale;
use App\Models\Store;
use App\Models\User;
use App\Notifications\SalesReportNotification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SalesNotificationService
{
    /**
     * Milestones pour les notifications (en CDF)
     */
    private array $milestones = [
        100000,    // 100K
        500000,    // 500K
        1000000,   // 1M
        2000000,   // 2M
        5000000,   // 5M
        10000000,  // 10M
    ];

    /**
     * Notifier les managers/admins d'une nouvelle vente
     */
    public function notifyNewSale(Sale $sale): void
    {
        Log::info('SalesNotificationService::notifyNewSale appelé', [
            'sale_id' => $sale->id,
            'store_id' => $sale->store_id,
        ]);

        if (!$sale->store) {
            Log::warning('notifyNewSale: Pas de store associé à la vente', ['sale_id' => $sale->id]);
            return;
        }

        $store = $sale->store;
        $todaySales = $this->getTodaySalesForStore($store);

        Log::info('notifyNewSale: Envoi notification', [
            'store_name' => $store->name,
            'total' => $sale->total,
        ]);

        // Notifier pour la nouvelle vente
        $this->notifyStoreManagers($store, [
            'total_amount' => $sale->total,
            'total_sales' => 1,
            'sale_id' => $sale->id,
            'invoice_number' => $sale->sale_number,
        ], 'new_sale');

        // Vérifier les milestones
        $this->checkAndNotifyMilestones($store, $todaySales['total_amount']);
    }

    /**
     * Notifier le rapport horaire des ventes
     */
    public function notifyHourlySales(Store $store): void
    {
        $hourlyData = $this->getHourlySalesForStore($store);

        if ($hourlyData['total_sales'] > 0) {
            $this->notifyStoreManagers($store, $hourlyData, 'hourly');
        }
    }

    /**
     * Notifier le rapport journalier des ventes
     */
    public function notifyDailySales(Store $store): void
    {
        $dailyData = $this->getTodaySalesForStore($store);

        if ($dailyData['total_sales'] > 0) {
            $this->notifyStoreManagers($store, $dailyData, 'daily');
        }
    }

    /**
     * Notifier tous les magasins pour le rapport journalier
     */
    public function notifyAllStoresDailySales(): void
    {
        Store::where('is_active', true)->each(function (Store $store) {
            $this->notifyDailySales($store);
        });
    }

    /**
     * Notifier tous les magasins pour le rapport horaire
     */
    public function notifyAllStoresHourlySales(): void
    {
        Store::where('is_active', true)->each(function (Store $store) {
            $this->notifyHourlySales($store);
        });
    }

    /**
     * Vérifier et notifier les milestones atteints
     */
    private function checkAndNotifyMilestones(Store $store, float $totalAmount): void
    {
        $cacheKey = "store_{$store->id}_milestone_" . now()->format('Y-m-d');

        foreach ($this->milestones as $milestone) {
            $milestoneKey = "{$cacheKey}_{$milestone}";

            if ($totalAmount >= $milestone && !Cache::has($milestoneKey)) {
                Cache::put($milestoneKey, true, now()->endOfDay());

                $this->notifyStoreManagers($store, [
                    'total_amount' => $totalAmount,
                    'milestone' => $milestone,
                    'total_sales' => $this->getTodaySalesForStore($store)['total_sales'],
                ], 'milestone');

                Log::info("Milestone {$milestone} atteint pour {$store->name}");
            }
        }
    }

    /**
     * Notifier les managers et admins d'un magasin
     */
    private function notifyStoreManagers(Store $store, array $salesData, string $reportType): void
    {
        $usersToNotify = $this->getUsersToNotify($store);

        Log::info('notifyStoreManagers: Utilisateurs à notifier', [
            'store_id' => $store->id,
            'count' => $usersToNotify->count(),
            'user_ids' => $usersToNotify->pluck('id')->toArray(),
        ]);

        foreach ($usersToNotify as $user) {
            try {
                Log::info("Envoi notification à user {$user->id} ({$user->email})");
                $user->notify(new SalesReportNotification($store, $salesData, $reportType));
                Log::info("Notification envoyée avec succès à user {$user->id}");
            } catch (\Exception $e) {
                Log::error("Erreur notification ventes: {$e->getMessage()}", [
                    'user_id' => $user->id,
                    'store_id' => $store->id,
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }
    }

    /**
     * Obtenir les utilisateurs à notifier pour un magasin
     */
    private function getUsersToNotify(Store $store): \Illuminate\Support\Collection
    {
        // Admins et managers de l'organisation (owner, admin, manager)
        $orgUsers = User::whereHas('organizations', function ($query) use ($store) {
            $query->where('organizations.id', $store->organization_id)
                ->whereIn('organization_user.role', ['admin', 'owner', 'manager']);
        })->where('is_active', true)->get();

        // Managers du magasin (via store_user)
        $storeManagers = User::whereHas('stores', function ($query) use ($store) {
            $query->where('stores.id', $store->id)
                ->where('store_user.role', 'manager');
        })->where('is_active', true)->get();

        // Manager direct du magasin
        if ($store->manager_id) {
            $directManager = User::find($store->manager_id);
            if ($directManager && $directManager->is_active) {
                $storeManagers->push($directManager);
            }
        }

        return $orgUsers->merge($storeManagers)->unique('id');
    }

    /**
     * Obtenir les ventes du jour pour un magasin
     */
    private function getTodaySalesForStore(Store $store): array
    {
        $sales = Sale::where('store_id', $store->id)
            ->whereDate('created_at', today())
            ->where('status', 'completed')
            ->selectRaw('COUNT(*) as total_sales, COALESCE(SUM(total), 0) as total_amount')
            ->first();

        return [
            'total_sales' => $sales->total_sales ?? 0,
            'total_amount' => (float) ($sales->total_amount ?? 0),
            'date' => today()->format('Y-m-d'),
        ];
    }

    /**
     * Obtenir les ventes de la dernière heure pour un magasin
     */
    private function getHourlySalesForStore(Store $store): array
    {
        $sales = Sale::where('store_id', $store->id)
            ->where('created_at', '>=', now()->subHour())
            ->where('status', 'completed')
            ->selectRaw('COUNT(*) as total_sales, COALESCE(SUM(total), 0) as total_amount')
            ->first();

        return [
            'total_sales' => $sales->total_sales ?? 0,
            'total_amount' => (float) ($sales->total_amount ?? 0),
            'hour' => now()->format('H:i'),
        ];
    }

    /**
     * Obtenir le résumé des ventes par magasin pour une organisation
     */
    public function getOrganizationSalesSummary(int $organizationId): array
    {
        $stores = Store::where('organization_id', $organizationId)
            ->where('is_active', true)
            ->get();

        $summary = [];

        foreach ($stores as $store) {
            $todayData = $this->getTodaySalesForStore($store);
            $summary[] = [
                'store_id' => $store->id,
                'store_name' => $store->name,
                'today_sales' => $todayData['total_sales'],
                'today_amount' => $todayData['total_amount'],
            ];
        }

        return $summary;
    }
}
