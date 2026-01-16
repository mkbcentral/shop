<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Sale;
use App\Services\Pos\StatsService;
use Illuminate\Support\Facades\Log;

/**
 * Observateur pour le modèle Sale
 * Effectue des actions automatiques lors des événements du modèle
 */
class SaleObserver
{
    public function __construct(
        private StatsService $statsService
    ) {}

    /**
     * Appelé après la création d'une vente
     *
     * @param Sale $sale
     * @return void
     */
    public function created(Sale $sale): void
    {
        // Invalider le cache des statistiques
        $this->statsService->invalidateStatsCache($sale->user_id);

        Log::info('Vente créée', [
            'sale_id' => $sale->id,
            'total' => $sale->total,
            'user_id' => $sale->user_id,
        ]);
    }

    /**
     * Appelé après la mise à jour d'une vente
     *
     * @param Sale $sale
     * @return void
     */
    public function updated(Sale $sale): void
    {
        // Invalider le cache si le statut a changé
        if ($sale->isDirty('status')) {
            $this->statsService->invalidateStatsCache($sale->user_id);

            Log::info('Statut de vente modifié', [
                'sale_id' => $sale->id,
                'old_status' => $sale->getOriginal('status'),
                'new_status' => $sale->status,
            ]);
        }

        // Invalider le cache si le montant a changé
        if ($sale->isDirty(['total', 'subtotal', 'discount', 'tax'])) {
            $this->statsService->invalidateStatsCache($sale->user_id);

            Log::info('Montant de vente modifié', [
                'sale_id' => $sale->id,
                'old_total' => $sale->getOriginal('total'),
                'new_total' => $sale->total,
            ]);
        }
    }

    /**
     * Appelé avant la suppression d'une vente
     *
     * @param Sale $sale
     * @return void
     */
    public function deleting(Sale $sale): void
    {
        Log::warning('Vente en cours de suppression', [
            'sale_id' => $sale->id,
            'total' => $sale->total,
            'date' => $sale->sale_date,
        ]);
    }

    /**
     * Appelé après la suppression d'une vente
     *
     * @param Sale $sale
     * @return void
     */
    public function deleted(Sale $sale): void
    {
        // Invalider le cache des statistiques
        $this->statsService->invalidateStatsCache($sale->user_id);

        Log::warning('Vente supprimée', [
            'sale_id' => $sale->id,
            'total' => $sale->total,
        ]);
    }

    /**
     * Appelé après la restauration d'une vente (soft delete)
     *
     * @param Sale $sale
     * @return void
     */
    public function restored(Sale $sale): void
    {
        // Invalider le cache des statistiques
        $this->statsService->invalidateStatsCache($sale->user_id);

        Log::info('Vente restaurée', [
            'sale_id' => $sale->id,
            'total' => $sale->total,
        ]);
    }
}
