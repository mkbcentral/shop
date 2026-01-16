<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\SaleCompleted;
use App\Services\SalesNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyManagersOnSale implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        private SalesNotificationService $notificationService
    ) {}

    /**
     * Handle the event.
     */
    public function handle(SaleCompleted $event): void
    {
        $this->notificationService->notifyNewSale($event->sale);
    }
}
