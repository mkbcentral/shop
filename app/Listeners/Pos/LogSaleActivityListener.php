<?php

declare(strict_types=1);

namespace App\Listeners\Pos;

use App\Events\Pos\SaleCompleted;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Listener qui enregistre l'activité des ventes
 */
class LogSaleActivityListener implements ShouldQueue
{
    /**
     * Gère l'événement de vente complétée
     */
    public function handle(SaleCompleted $event): void
    {
        Log::channel('pos')->info('Vente complétée', [
            'sale_id' => $event->sale->id,
            'invoice_number' => $event->invoice->invoice_number,
            'user_id' => $event->sale->user_id,
            'client_id' => $event->sale->client_id,
            'total' => $event->sale->total,
            'payment_method' => $event->sale->payment_method,
            'items_count' => $event->sale->items->count(),
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
