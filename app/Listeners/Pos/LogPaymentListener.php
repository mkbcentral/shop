<?php

declare(strict_types=1);

namespace App\Listeners\Pos;

use App\Events\Pos\PaymentReceived;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Listener qui enregistre les paiements reçus
 */
class LogPaymentListener implements ShouldQueue
{
    /**
     * Gère l'événement de paiement reçu
     */
    public function handle(PaymentReceived $event): void
    {
        Log::channel('payments')->info('Paiement reçu', [
            'sale_id' => $event->sale->id,
            'payment_method' => $event->paymentMethod,
            'amount' => $event->amount,
            'user_id' => $event->sale->user_id,
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
