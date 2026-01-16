<?php

declare(strict_types=1);

namespace App\Events\Pos;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Événement déclenché lorsqu'un panier est vidé
 */
class CartCleared
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly int $userId,
        public readonly int $itemCount,
        public readonly float $totalValue
    ) {}

    /**
     * Obtient les données pour le broadcast
     */
    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->userId,
            'item_count' => $this->itemCount,
            'total_value' => $this->totalValue,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
