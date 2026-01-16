<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Sale;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SaleCompleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Sale $sale
    ) {}

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('sales'),
        ];

        // Canal spécifique au magasin
        if ($this->sale->store_id) {
            $channels[] = new PrivateChannel('store.' . $this->sale->store_id);
        }

        // Canal spécifique à l'organisation
        if ($this->sale->organization_id) {
            $channels[] = new PrivateChannel('organization.' . $this->sale->organization_id);
        }

        return $channels;
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'sale_id' => $this->sale->id,
            'store_id' => $this->sale->store_id,
            'store_name' => $this->sale->store?->name,
            'total' => $this->sale->total,
            'invoice_number' => $this->sale->sale_number,
            'payment_method' => $this->sale->payment_method,
            'created_at' => $this->sale->created_at->toISOString(),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'sale.completed';
    }
}
