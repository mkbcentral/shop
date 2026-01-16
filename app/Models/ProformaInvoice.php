<?php

namespace App\Models;

use App\Traits\HasStoreScope;
use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProformaInvoice extends Model
{
    use HasFactory, HasStoreScope, BelongsToOrganization;

    /**
     * Proforma status constants.
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_SENT = 'sent';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CONVERTED = 'converted';
    const STATUS_EXPIRED = 'expired';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'organization_id',
        'store_id',
        'user_id',
        'proforma_number',
        'client_name',
        'client_phone',
        'client_email',
        'client_address',
        'proforma_date',
        'valid_until',
        'subtotal',
        'tax_amount',
        'discount',
        'total',
        'status',
        'notes',
        'terms_conditions',
        'converted_to_invoice_id',
        'converted_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'proforma_date' => 'date',
        'valid_until' => 'date',
        'converted_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($proforma) {
            // Generate proforma number if not provided
            if (!$proforma->proforma_number) {
                $proforma->proforma_number = self::generateProformaNumber($proforma->store_id);
            }

            // Set default validity (30 days)
            if (!$proforma->valid_until) {
                $proforma->valid_until = now()->addDays(30);
            }
        });
    }

    /**
     * Generate a unique proforma number.
     */
    public static function generateProformaNumber(?int $storeId = null): string
    {
        $prefix = 'PRO';
        $year = date('Y');
        $month = date('m');

        // Get the last proforma number for the current month
        $lastProforma = self::where('proforma_number', 'like', "{$prefix}-{$year}{$month}-%")
            ->when($storeId, fn($q) => $q->where('store_id', $storeId))
            ->orderBy('proforma_number', 'desc')
            ->first();

        if ($lastProforma) {
            $lastNumber = (int) substr($lastProforma->proforma_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "{$prefix}-{$year}{$month}-{$newNumber}";
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT => 'Brouillon',
            self::STATUS_SENT => 'Envoyée',
            self::STATUS_ACCEPTED => 'Acceptée',
            self::STATUS_REJECTED => 'Refusée',
            self::STATUS_CONVERTED => 'Convertie',
            self::STATUS_EXPIRED => 'Expirée',
            default => $this->status,
        };
    }

    /**
     * Get status color for UI.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT => 'gray',
            self::STATUS_SENT => 'blue',
            self::STATUS_ACCEPTED => 'green',
            self::STATUS_REJECTED => 'red',
            self::STATUS_CONVERTED => 'indigo',
            self::STATUS_EXPIRED => 'yellow',
            default => 'gray',
        };
    }

    /**
     * Check if proforma is expired.
     */
    public function isExpired(): bool
    {
        return $this->valid_until && $this->valid_until->isPast() && !in_array($this->status, [
            self::STATUS_CONVERTED,
            self::STATUS_REJECTED,
        ]);
    }

    /**
     * Check if proforma can be edited.
     */
    public function canBeEdited(): bool
    {
        return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_SENT]);
    }

    /**
     * Check if proforma can be converted.
     */
    public function canBeConverted(): bool
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    /**
     * Scope for pending proformas.
     */
    public function scopePending($query)
    {
        return $query->whereIn('status', [self::STATUS_DRAFT, self::STATUS_SENT]);
    }

    /**
     * Scope for expired proformas.
     */
    public function scopeExpired($query)
    {
        return $query->where('valid_until', '<', now())
            ->whereNotIn('status', [self::STATUS_CONVERTED, self::STATUS_REJECTED, self::STATUS_EXPIRED]);
    }

    /**
     * Relationships
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ProformaInvoiceItem::class);
    }

    public function convertedInvoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'converted_to_invoice_id');
    }

    /**
     * Calculate totals from items.
     */
    public function calculateTotals(): void
    {
        $subtotal = $this->items->sum(fn($item) => $item->quantity * $item->unit_price);
        $discount = $this->items->sum('discount');
        $taxAmount = $this->items->sum(fn($item) => ($item->quantity * $item->unit_price - $item->discount) * ($item->tax_rate / 100));

        $this->subtotal = $subtotal;
        $this->discount = $discount;
        $this->tax_amount = $taxAmount;
        $this->total = $subtotal - $discount + $taxAmount;
        $this->save();
    }
}
