<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StoreTransfer extends Model
{
    use BelongsToOrganization;
    protected $fillable = [
        'organization_id',
        'transfer_number',
        'from_store_id',
        'to_store_id',
        'status',
        'transfer_date',
        'expected_arrival_date',
        'actual_arrival_date',
        'notes',
        'requested_by',
        'approved_by',
        'received_by',
        'cancelled_by',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'transfer_date' => 'datetime',
        'expected_arrival_date' => 'datetime',
        'actual_arrival_date' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Get the store sending the transfer
     */
    public function fromStore(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'from_store_id');
    }

    /**
     * Get the store receiving the transfer
     */
    public function toStore(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'to_store_id');
    }

    /**
     * Get the user who requested the transfer
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * Get the user who approved the transfer
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the user who received the transfer
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * Get the user who cancelled the transfer
     */
    public function canceller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    /**
     * Get the transfer items
     */
    public function items(): HasMany
    {
        return $this->hasMany(StoreTransferItem::class);
    }

    /**
     * Status checks
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isInTransit(): bool
    {
        return $this->status === 'in_transit';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if transfer can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'approved']);
    }

    /**
     * Check if transfer can be approved
     */
    public function canBeApproved(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if transfer can be received
     */
    public function canBeReceived(): bool
    {
        return $this->status === 'in_transit';
    }

    /**
     * Get total items count
     */
    public function getTotalItemsCount(): int
    {
        return $this->items()->sum('quantity_requested');
    }

    /**
     * Get total items received count
     */
    public function getTotalItemsReceivedCount(): int
    {
        return $this->items()->sum('quantity_received') ?? 0;
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeInTransit($query)
    {
        return $query->where('status', 'in_transit');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeFromStore($query, int $storeId)
    {
        return $query->where('from_store_id', $storeId);
    }

    public function scopeToStore($query, int $storeId)
    {
        return $query->where('to_store_id', $storeId);
    }
}
