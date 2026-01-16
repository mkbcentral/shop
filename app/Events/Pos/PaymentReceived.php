<?php

declare(strict_types=1);

namespace App\Events\Pos;

use App\Models\Sale;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Événement déclenché lorsqu'un paiement est reçu
 */
class PaymentReceived
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly Sale $sale,
        public readonly string $paymentMethod,
        public readonly float $amount
    ) {}

    /**
     * Obtient les données pour le broadcast
     */
    public function broadcastWith(): array
    {
        return [
            'sale_id' => $this->sale->id,
            'payment_method' => $this->paymentMethod,
            'amount' => $this->amount,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
