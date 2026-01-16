<?php

declare(strict_types=1);

namespace App\Listeners\Pos;

use App\Events\Pos\SaleCompleted;
use App\Services\Pos\StatsService;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Listener qui met à jour les statistiques après une vente
 */
class UpdateDailyStatsListener implements ShouldQueue
{
    public function __construct(
        private readonly StatsService $statsService
    ) {}

    /**
     * Gère l'événement de vente complétée
     */
    public function handle(SaleCompleted $event): void
    {
        // Invalider le cache des statistiques pour forcer un rafraîchissement
        $this->statsService->invalidateStatsCache($event->sale->user_id);
    }
}
