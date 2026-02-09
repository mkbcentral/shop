<?php

namespace App\Livewire\Stock;

use App\Repositories\ProductVariantRepository;
use Livewire\Component;
use Livewire\WithPagination;

class StockAlerts extends Component
{
    use WithPagination;

    public $alertType = 'all'; // all, out_of_stock, low_stock, expired, expiring_soon
    public $perPage = 10;
    public $search = '';

    public function render(ProductVariantRepository $variantRepository)
    {
        $query = $variantRepository->query()
            ->with('product');

        // Exclude service products from stock alerts
        $query->whereHas('product.productType', function($q) {
            $q->where('is_service', false);
        });

        // Filter by current store
        if (current_store_id()) {
            $query->whereHas('product', function($q) {
                $q->where('store_id', current_store_id());
            });
        }

        // Filter by alert type
        if ($this->alertType === 'out_of_stock') {
            $query->where('stock_quantity', '<=', 0);
        } elseif ($this->alertType === 'low_stock') {
            $query->where('stock_quantity', '>', 0)
                  ->whereColumn('stock_quantity', '<=', 'low_stock_threshold');
        } elseif ($this->alertType === 'expired') {
            $query->whereHas('product', function($q) {
                $q->whereNotNull('expiry_date')
                  ->whereDate('expiry_date', '<', now());
            });
        } elseif ($this->alertType === 'expiring_soon') {
            $query->whereHas('product', function($q) {
                $q->whereNotNull('expiry_date')
                  ->whereDate('expiry_date', '>=', now())
                  ->whereDate('expiry_date', '<=', now()->addDays(30));
            });
        } else {
            // All alerts (out of stock OR low stock OR expired OR expiring soon)
            $query->where(function($q) {
                $q->where('stock_quantity', '<=', 0)
                  ->orWhereColumn('stock_quantity', '<=', 'low_stock_threshold')
                  ->orWhereHas('product', function($pq) {
                      $pq->whereNotNull('expiry_date')
                         ->whereDate('expiry_date', '<=', now()->addDays(30));
                  });
            });
        }

        // Search filter
        if ($this->search) {
            $query->whereHas('product', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
            })->orWhere('sku', 'like', '%' . $this->search . '%');
        }

        $variants = $query->orderBy('stock_quantity', 'asc')
                          ->paginate($this->perPage);

        // Calculate stats
        $stats = $this->calculateStats();

        return view('livewire.stock.alerts', [
            'variants' => $variants,
            'stats' => $stats,
        ]);
    }

    private function calculateStats(): array
    {
        $baseQuery = \App\Models\ProductVariant::query();

        // Exclude service products from stock stats
        $baseQuery->whereHas('product.productType', function($q) {
            $q->where('is_service', false);
        });

        if (current_store_id()) {
            $baseQuery->whereHas('product', function($q) {
                $q->where('store_id', current_store_id());
            });
        }

        $outOfStock = (clone $baseQuery)->where('stock_quantity', '<=', 0)->count();
        $lowStock = (clone $baseQuery)->where('stock_quantity', '>', 0)->whereRaw('stock_quantity <= low_stock_threshold')->count();

        // For expiration alerts, show regardless of stock (product might still be on shelf)
        $expired = (clone $baseQuery)
            ->whereHas('product', function($q) {
                $q->whereNotNull('expiry_date')
                  ->whereDate('expiry_date', '<', now());
            })->count();

        $expiringSoon = (clone $baseQuery)
            ->whereHas('product', function($q) {
                $q->whereNotNull('expiry_date')
                  ->whereDate('expiry_date', '>=', now())
                  ->whereDate('expiry_date', '<=', now()->addDays(30));
            })->count();

        return [
            'out_of_stock' => $outOfStock,
            'low_stock' => $lowStock,
            'expired' => $expired,
            'expiring_soon' => $expiringSoon,
            'total' => $outOfStock + $lowStock + $expired + $expiringSoon,
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingAlertType()
    {
        $this->resetPage();
    }
}
