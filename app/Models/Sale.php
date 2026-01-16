<?php

namespace App\Models;

use App\Traits\HasStoreScope;
use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, SoftDeletes, HasStoreScope, BelongsToOrganization;

    /**
     * Payment method constants.
     */
    const PAYMENT_CASH = 'cash';
    const PAYMENT_CARD = 'card';
    const PAYMENT_TRANSFER = 'transfer';
    const PAYMENT_CHEQUE = 'cheque';

    /**
     * Payment status constants.
     */
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_PARTIAL = 'partial';
    const PAYMENT_REFUNDED = 'refunded';

    /**
     * Sale status constants.
     */
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'organization_id',
        'store_id',
        'client_id',
        'sale_number',
        'sale_date',
        'subtotal',
        'discount',
        'tax',
        'total',
        'paid_amount',
        'payment_method',
        'payment_status',
        'status',
        'notes',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sale_date' => 'datetime',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Generate sale number if not provided
        static::creating(function ($sale) {
            if (!$sale->sale_number) {
                $sale->sale_number = self::generateSaleNumber($sale->store_id);
            }
        });
    }

    /**
     * Get the store that owns the sale.
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Generate a unique sale number.
     * Format: VT-S{store_id}-{année-mois}-{séquence}
     * Note: Pour éviter les doublons en concurrence, utilisez generateUniqueSaleNumber() dans SaleService
     */
    public static function generateSaleNumber(?int $storeId = null): string
    {
        $storeId = $storeId ?? current_store_id() ?? 0;
        $date = now()->format('Y-m');
        $prefix = 'VT-S' . $storeId . '-' . $date . '-';

        // Générer un identifiant unique de processus pour le fallback
        $processId = substr(md5(uniqid((string) mt_rand(), true)), 0, 6);
        $maxAttempts = 10;

        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            // Récupère le dernier numéro de vente du mois avec verrou
            $maxNumber = self::where('sale_number', 'like', $prefix . '%')
                            ->selectRaw('MAX(CAST(SUBSTRING(sale_number, -4) AS UNSIGNED)) as max_num')
                            ->lockForUpdate()
                            ->value('max_num');

            $nextNumber = ($maxNumber ?? 0) + 1 + $attempt;
            $saleNumber = $prefix . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);

            // Vérifier si ce numéro existe déjà
            if (!self::where('sale_number', $saleNumber)->exists()) {
                return $saleNumber;
            }
        }

        // En dernier recours, utiliser timestamp + identifiant unique
        return $prefix . now()->format('His') . '-' . $processId;
    }

    /**
     * Get the client that owns the sale.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the user (seller) who made the sale.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all items in this sale.
     */
    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Get the invoice for this sale.
     */
    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    /**
     * Get all payments for this sale.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Calculate and update sale totals based on items.
     */
    public function calculateTotals(): void
    {
        $this->subtotal = $this->items->sum('subtotal');
        $this->total = $this->subtotal - $this->discount + $this->tax;
        $this->save();
    }

    /**
     * Scope a query to only include completed sales.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope a query to only include pending sales.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope a query to only include paid sales.
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', self::PAYMENT_PAID);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('sale_date', [$startDate, $endDate]);
    }

    /**
     * Check if sale is paid.
     */
    public function isPaid(): bool
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }

    /**
     * Check if sale is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Get the remaining amount to be paid.
     */
    public function getRemainingAmountAttribute(): float
    {
        return max(0, $this->total - $this->paid_amount);
    }

    /**
     * Check if sale is partially paid.
     */
    public function isPartiallyPaid(): bool
    {
        return $this->paid_amount > 0 && $this->paid_amount < $this->total;
    }

    /**
     * Update payment status based on paid amount.
     */
    public function updatePaymentStatus(): void
    {
        if ($this->paid_amount <= 0) {
            $this->payment_status = self::PAYMENT_PENDING;
        } elseif ($this->paid_amount >= $this->total) {
            $this->payment_status = self::PAYMENT_PAID;
        } else {
            $this->payment_status = self::PAYMENT_PARTIAL;
        }
        $this->save();
    }
}
