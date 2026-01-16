<?php

namespace App\Livewire\Stock;

use App\Repositories\ProductVariantRepository;
use Livewire\Component;
use Livewire\WithPagination;

class StockAlerts extends Component
{
    use WithPagination;

    public $alertType = 'all'; // all, out_of_stock, low_stock
    public $perPage = 10;
    public $search = '';

    public function render(ProductVariantRepository $variantRepository)
    {
        $query = $variantRepository->query()
            ->with('product');

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
        } else {
            // All alerts (out of stock OR low stock)
            $query->where(function($q) {
                $q->where('stock_quantity', '<=', 0)
                  ->orWhereColumn('stock_quantity', '<=', 'low_stock_threshold');
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

        return view('livewire.stock.alerts', [
            'variants' => $variants,
        ]);
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
