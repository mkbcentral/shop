<?php

declare(strict_types=1);

namespace App\Listeners\Pos;

use App\Events\Pos\SaleCompleted;
use App\Services\SalesNotificationService;
use Illuminate\Support\Facades\Log;

/**
 * Listener pour notifier les managers/admins lors d'une vente POS
 */
class NotifyManagersOnPosSale
{
    public function __construct(
        private SalesNotificationService $notificationService
    ) {}

    /**
     * Handle the event.
     */
    public function handle(SaleCompleted $event): void
    {
        Log::info('NotifyManagersOnPosSale: Listener déclenché', [
            'sale_id' => $event->sale->id,
            'store_id' => $event->sale->store_id,
        ]);

        $this->notificationService->notifyNewSale($event->sale);
    }
}
