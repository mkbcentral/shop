<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreTransferItem extends Model
{
    protected $fillable = [
        'store_transfer_id',
        'product_variant_id',
        'quantity_requested',
        'quantity_sent',
        'quantity_received',
        'notes',
    ];

    protected $casts = [
        'quantity_requested' => 'integer',
        'quantity_sent' => 'integer',
        'quantity_received' => 'integer',
    ];

    /**
     * Get the transfer this item belongs to
     */
    public function transfer(): BelongsTo
    {
        return $this->belongsTo(StoreTransfer::class, 'store_transfer_id');
    }

    /**
     * Get the product variant
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    /**
     * Check if quantity sent matches requested
     */
    public function isFullySent(): bool
    {
        return $this->quantity_sent !== null && $this->quantity_sent === $this->quantity_requested;
    }

    /**
     * Check if quantity received matches sent
     */
    public function isFullyReceived(): bool
    {
        return $this->quantity_received !== null &&
               $this->quantity_sent !== null &&
               $this->quantity_received === $this->quantity_sent;
    }

    /**
     * Get the difference between sent and received
     */
    public function getMissingQuantity(): int
    {
        if ($this->quantity_sent === null || $this->quantity_received === null) {
            return 0;
        }

        return $this->quantity_sent - $this->quantity_received;
    }

    /**
     * Get the product name
     */
    public function getProductName(): string
    {
        return $this->variant->product->name ?? 'Unknown';
    }

    /**
     * Get the variant details
     */
    public function getVariantDetails(): string
    {
        $details = [];

        if ($this->variant->size) {
            $details[] = $this->variant->size;
        }

        if ($this->variant->color) {
            $details[] = $this->variant->color;
        }

        return implode(' - ', $details);
    }
}
