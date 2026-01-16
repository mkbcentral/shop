<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
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
     * Get all purchases from this supplier.
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Get the total amount spent with this supplier.
     */
    public function getTotalSpentAttribute(): float
    {
        return $this->purchases()
            ->where('status', Purchase::STATUS_RECEIVED)
            ->sum('total');
    }

    /**
     * Get the number of purchases from this supplier.
     */
    public function getTotalPurchasesAttribute(): int
    {
        return $this->purchases()->count();
    }

    /**
     * Get the last purchase date.
     */
    public function getLastPurchaseDateAttribute()
    {
        return $this->purchases()
            ->latest('purchase_date')
            ->value('purchase_date');
    }

    /**
     * Get pending purchases.
     */
    public function getPendingPurchasesAttribute(): int
    {
        return $this->purchases()
            ->where('status', Purchase::STATUS_PENDING)
            ->count();
    }

    /**
     * Get purchases for a specific period.
     */
    public function purchasesBetweenDates(string $startDate, string $endDate)
    {
        return $this->purchases()
            ->whereBetween('purchase_date', [$startDate, $endDate])
            ->get();
    }

    /**
     * Check if supplier has any purchases.
     */
    public function hasPurchases(): bool
    {
        return $this->purchases()->exists();
    }
}
