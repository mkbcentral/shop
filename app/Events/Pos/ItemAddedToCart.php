<?php

declare(strict_types=1);

namespace App\Events\Pos;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Événement déclenché lorsqu'un article est ajouté au panier
 */
class ItemAddedToCart
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly int $userId,
        public readonly int $variantId,
        public readonly string $productName,
        public readonly int $quantity,
        public readonly float $price
    ) {}

    /**
     * Obtient les données pour le broadcast
     */
    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->userId,
            'variant_id' => $this->variantId,
            'product_name' => $this->productName,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
