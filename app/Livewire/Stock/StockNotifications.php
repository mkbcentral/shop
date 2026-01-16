<?php

namespace App\Livewire\Stock;

use App\Models\ProductVariant;
use App\Models\Store;
use Illuminate\Support\Collection;
use Livewire\Component;

class StockNotifications extends Component
{
    public bool $showDropdown = false;
    public int $totalAlerts = 0;

    protected $listeners = [
        'stockUpdated' => '$refresh',
        'storeChanged' => '$refresh',
    ];

    public function toggleDropdown(): void
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function closeDropdown(): void
    {
        $this->showDropdown = false;
    }

    /**
     * Get stock alerts based on user role
     * - Managers/Admins: see all stores
     * - Regular users: see only their current store
     */
    public function getStockAlertsProperty(): Collection
    {
        $user = auth()->user();

        if (!$user) {
            return collect();
        }

        // Query ProductVariant directly for stock alerts
        $query = ProductVariant::with(['product'])
            ->where(function($q) {
                // Out of stock OR low stock
                $q->where('stock_quantity', '<=', 0)
                  ->orWhereColumn('stock_quantity', '<=', 'low_stock_threshold');
            })
            ->whereHas('product', function($q) {
                $q->where('status', 'active');
            });

        return $query->orderBy('stock_quantity', 'asc')
            ->limit(50)
            ->get();
    }

    /**
     * Get summary counts
     */
    public function getStoreSummaryProperty(): Collection
    {
        $user = auth()->user();

        if (!$user) {
            return collect();
        }

        // Get global counts from ProductVariant
        $outOfStock = ProductVariant::where('stock_quantity', '<=', 0)
            ->whereHas('product', fn($q) => $q->where('status', 'active'))
            ->count();

        $lowStock = ProductVariant::where('stock_quantity', '>', 0)
            ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->whereHas('product', fn($q) => $q->where('status', 'active'))
            ->count();

        $summary = collect();

        if ($outOfStock > 0 || $lowStock > 0) {
            // Get current store name if available
            $currentStore = current_store();
            $storeName = $currentStore?->name ?? 'Tous les magasins';

            $summary->push([
                'store' => (object)['name' => $storeName],
                'out_of_stock' => $outOfStock,
                'low_stock' => $lowStock,
                'total' => $outOfStock + $lowStock,
            ]);
        }

        return $summary;
    }

    /**
     * Get total alert count
     */
    public function getTotalAlertsCountProperty(): int
    {
        return $this->stockAlerts->count();
    }

    /**
     * Get out of stock count
     */
    public function getOutOfStockCountProperty(): int
    {
        return $this->stockAlerts->where('stock_quantity', '<=', 0)->count();
    }

    /**
     * Get low stock count
     */
    public function getLowStockCountProperty(): int
    {
        return $this->stockAlerts->where('stock_quantity', '>', 0)->count();
    }

    public function render()
    {
        return view('livewire.stock.stock-notifications');
    }
}
