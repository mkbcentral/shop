<?php

namespace App\Livewire\PriceHistory;

use App\Models\PriceHistory;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Livewire\Component;
use Livewire\WithPagination;

class PriceHistoryIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $productFilter = '';
    public $priceTypeFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $changeDirection = ''; // 'increase', 'decrease', ''
    public $perPage = 15;

    protected $queryString = [
        'search' => ['except' => ''],
        'productFilter' => ['except' => ''],
        'priceTypeFilter' => ['except' => ''],
        'changeDirection' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
    ];

    public function mount()
    {
        $this->dateFrom = now()->subMonths(3)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingProductFilter()
    {
        $this->resetPage();
    }

    public function updatingPriceTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingChangeDirection()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'productFilter', 'priceTypeFilter', 'changeDirection']);
        $this->dateFrom = now()->subMonths(3)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->resetPage();
    }

    public function render(ProductRepository $productRepository)
    {
        $query = PriceHistory::query()
            ->with(['product', 'productVariant', 'user'])
            ->orderBy('changed_at', 'desc');

        // Filter by search (product name or reference)
        if ($this->search) {
            $query->whereHas('product', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('reference', 'like', '%' . $this->search . '%');
            });
        }

        // Filter by specific product
        if ($this->productFilter) {
            $query->where('product_id', $this->productFilter);
        }

        // Filter by price type
        if ($this->priceTypeFilter) {
            $query->where('price_type', $this->priceTypeFilter);
        }

        // Filter by change direction
        if ($this->changeDirection === 'increase') {
            $query->where('price_difference', '>', 0);
        } elseif ($this->changeDirection === 'decrease') {
            $query->where('price_difference', '<', 0);
        }

        // Filter by date range
        if ($this->dateFrom) {
            $query->whereDate('changed_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('changed_at', '<=', $this->dateTo);
        }

        $priceHistories = $query->paginate($this->perPage);

        // Get products for filter dropdown
        $products = Product::select('id', 'name', 'reference')
            ->orderBy('name')
            ->get();

        // Calculate statistics
        $stats = $this->getStatistics();

        return view('livewire.price-history.index', [
            'priceHistories' => $priceHistories,
            'products' => $products,
            'stats' => $stats,
        ]);
    }

    private function getStatistics(): array
    {
        $baseQuery = PriceHistory::query();

        // Apply date filters
        if ($this->dateFrom) {
            $baseQuery->whereDate('changed_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $baseQuery->whereDate('changed_at', '<=', $this->dateTo);
        }

        $totalChanges = (clone $baseQuery)->count();
        $increases = (clone $baseQuery)->where('price_difference', '>', 0)->count();
        $decreases = (clone $baseQuery)->where('price_difference', '<', 0)->count();
        $avgChange = (clone $baseQuery)->avg('percentage_change') ?? 0;

        // Products with most changes
        $mostChangedProducts = (clone $baseQuery)
            ->selectRaw('product_id, COUNT(*) as changes_count')
            ->groupBy('product_id')
            ->orderByDesc('changes_count')
            ->limit(5)
            ->with('product:id,name')
            ->get();

        return [
            'total_changes' => $totalChanges,
            'increases' => $increases,
            'decreases' => $decreases,
            'avg_change' => round($avgChange, 2),
            'most_changed' => $mostChangedProducts,
        ];
    }
}
