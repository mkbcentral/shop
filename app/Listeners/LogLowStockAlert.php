<?php

namespace App\Listeners;

use App\Events\LowStockAlert;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LogLowStockAlert implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(LowStockAlert $event): void
    {
        Log::warning('Low Stock Alert', [
            'variant_id' => $event->variant->id,
            'product' => $event->variant->product->name,
            'variant' => $event->variant->full_name,
            'current_stock' => $event->variant->stock_quantity,
            'threshold' => $event->variant->low_stock_threshold,
            'alert_type' => $event->alertType,
        ]);

        // TODO: Send email notification to admins
        // TODO: Send SMS notification
        // TODO: Create in-app notification
    }
}
