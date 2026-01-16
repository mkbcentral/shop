<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes, BelongsToOrganization;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'organization_id',
        'name',
        'phone',
        'email',
        'address',
    ];

    /**
     * Get all sales for this client.
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get the total amount spent by this client.
     */
    public function getTotalSpentAttribute(): float
    {
        return $this->sales()
            ->where('status', Sale::STATUS_COMPLETED)
            ->where('payment_status', Sale::PAYMENT_PAID)
            ->sum('total');
    }

    /**
     * Get the number of purchases made by this client.
     */
    public function getTotalPurchasesAttribute(): int
    {
        return $this->sales()
            ->where('status', Sale::STATUS_COMPLETED)
            ->count();
    }

    /**
     * Get the last purchase date.
     */
    public function getLastPurchaseDateAttribute()
    {
        return $this->sales()
            ->where('status', Sale::STATUS_COMPLETED)
            ->latest('sale_date')
            ->value('sale_date');
    }

    /**
     * Get pending amount (unpaid invoices).
     */
    public function getPendingAmountAttribute(): float
    {
        return $this->sales()
            ->where('payment_status', '!=', Sale::PAYMENT_PAID)
            ->where('status', Sale::STATUS_COMPLETED)
            ->sum('total');
    }

    /**
     * Get sales for a specific period.
     */
    public function salesBetweenDates(string $startDate, string $endDate)
    {
        return $this->sales()
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->where('status', Sale::STATUS_COMPLETED)
            ->get();
    }

    /**
     * Check if client has any sales.
     */
    public function hasSales(): bool
    {
        return $this->sales()->exists();
    }
}
