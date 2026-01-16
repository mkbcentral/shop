<?php

namespace App\Listeners;

use App\Events\OutOfStockAlert;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LogOutOfStockAlert implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(OutOfStockAlert $event): void
    {
        Log::critical('Out of Stock Alert', [
            'variant_id' => $event->variant->id,
            'product' => $event->variant->product->name,
            'variant' => $event->variant->full_name,
            'current_stock' => $event->variant->stock_quantity,
        ]);

        // TODO: Send urgent email notification
        // TODO: Send SMS to managers
        // TODO: Create high-priority in-app notification
        // TODO: Disable product on website if needed
    }
}
