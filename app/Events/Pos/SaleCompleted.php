<?php

declare(strict_types=1);

namespace App\Events\Pos;

use App\Models\Sale;
use App\Models\Invoice;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Événement déclenché lorsqu'une vente est complétée
 */
class SaleCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly Sale $sale,
        public readonly Invoice $invoice,
        public readonly float $change
    ) {}

    /**
     * Obtient les données pour le broadcast (si nécessaire)
     */
    public function broadcastWith(): array
    {
        return [
            'sale_id' => $this->sale->id,
            'invoice_number' => $this->invoice->invoice_number,
            'total' => $this->sale->total,
            'change' => $this->change,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
