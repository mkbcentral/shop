<?php

namespace App\Models;

use App\Traits\HasStoreScope;
use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    use HasFactory, HasStoreScope, BelongsToOrganization;

    /**
     * Purchase status constants.
     */
    const STATUS_PENDING = 'pending';
    const STATUS_RECEIVED = 'received';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Payment status constants.
     */
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_PARTIAL = 'partial';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'organization_id',
        'store_id',
        'supplier_id',
        'purchase_number',
        'purchase_date',
        'total',
        'status',
        'payment_status',
        'paid_amount',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'purchase_date' => 'date',
        'total' => 'decimal:2',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Generate purchase number if not provided
        static::creating(function ($purchase) {
            if (!$purchase->purchase_number) {
                $purchase->purchase_number = self::generatePurchaseNumber();
            }
        });
    }

    /**
     * Generate a unique purchase number.
     */
    public static function generatePurchaseNumber(): string
    {
        $date = now()->format('Y-m');
        $count = self::whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month)
                    ->count() + 1;

        return 'ACH-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get the supplier that owns the purchase.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the store that owns the purchase.
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Get all items for this purchase.
     */
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    /**
     * Calculate and update purchase total from items.
     */
    public function calculateTotal(): void
    {
        $this->total = $this->items()->sum('subtotal');
        $this->save();
    }

    /**
     * Scope a query to only include received purchases.
     */
    public function scopeReceived($query)
    {
        return $query->where('status', self::STATUS_RECEIVED);
    }

    /**
     * Scope a query to only include pending purchases.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('purchase_date', [$startDate, $endDate]);
    }

    /**
     * Mark purchase as received.
     */
    public function markAsReceived(): void
    {
        $this->status = self::STATUS_RECEIVED;
        $this->save();
    }

    /**
     * Check if purchase is received.
     */
    public function isReceived(): bool
    {
        return $this->status === self::STATUS_RECEIVED;
    }
}
