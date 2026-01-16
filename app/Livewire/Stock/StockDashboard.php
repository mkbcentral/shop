<?php

namespace App\Livewire\Stock;

use App\Repositories\ProductVariantRepository;
use App\Repositories\StockMovementRepository;
use Livewire\Component;

class StockDashboard extends Component
{
    public $dateFrom;
    public $dateTo;

    public function mount()
    {
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function render(ProductVariantRepository $variantRepository, StockMovementRepository $movementRepository)
    {
        // Get statistics
        $stats = $movementRepository->statistics($this->dateFrom, $this->dateTo);

        // Get low stock products
        $lowStockQuery = $variantRepository->query()
            ->with('product')
            ->where('stock_quantity', '>', 0)
            ->whereRaw('stock_quantity <= low_stock_threshold');

        // Filter by current store
        if (current_store_id()) {
            $lowStockQuery->whereHas('product', function($q) {
                $q->where('store_id', current_store_id());
            });
        }

        $lowStockProducts = $lowStockQuery->orderBy('stock_quantity', 'asc')
            ->limit(5)
            ->get();

        // Get out of stock products
        $outOfStockQuery = $variantRepository->query()
            ->with('product')
            ->where('stock_quantity', '<=', 0);

        // Filter by current store
        if (current_store_id()) {
            $outOfStockQuery->whereHas('product', function($q) {
                $q->where('store_id', current_store_id());
            });
        }

        $outOfStockProducts = $outOfStockQuery->limit(5)->get();

        // Get recent movements
        $recentMovementsQuery = $movementRepository->query()
            ->with(['productVariant.product', 'user'])
            ->whereBetween('date', [$this->dateFrom, $this->dateTo]);

        // Filter by current store
        if (current_store_id()) {
            $recentMovementsQuery->where('store_id', current_store_id());
        }

        $recentMovements = $recentMovementsQuery->orderBy('date', 'desc')
            ->limit(10)
            ->get();

        return view('livewire.stock.dashboard', [
            'stats' => $stats,
            'lowStockProducts' => $lowStockProducts,
            'outOfStockProducts' => $outOfStockProducts,
            'recentMovements' => $recentMovements,
        ]);
    }
}
