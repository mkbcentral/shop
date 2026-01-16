<?php

namespace App\Livewire\Product;

use App\Services\ProductSearchService;
use App\Models\ProductType;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class ProductSearch extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $selectedProductType = null;
    public $selectedCategory = null;
    public $selectedBrand = '';
    public $minPrice = '';
    public $maxPrice = '';
    public $inStockOnly = false;

    // Variant attribute filters
    public $variantFilters = [];
    public $availableFilterOptions = [];

    // Display options
    public $showFilters = false;
    public $orderBy = 'name';
    public $orderDirection = 'asc';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'selectedProductType' => ['except' => null],
        'selectedCategory' => ['except' => null],
        'inStockOnly' => ['except' => false],
    ];

    public function mount()
    {
        $this->loadAvailableFilters();
    }

    public function updatedSelectedProductType()
    {
        $this->variantFilters = [];
        $this->loadAvailableFilters();
        $this->resetPage();
    }

    public function updatedSelectedCategory()
    {
        $this->loadAvailableFilters();
        $this->resetPage();
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedInStockOnly()
    {
        $this->loadAvailableFilters();
        $this->resetPage();
    }

    public function updatedVariantFilters()
    {
        $this->resetPage();
    }

    public function loadAvailableFilters()
    {
        $searchService = app(ProductSearchService::class);

        $options = [
            'in_stock_only' => $this->inStockOnly,
        ];

        if ($this->selectedCategory) {
            $options['category_id'] = $this->selectedCategory;
        }

        $this->availableFilterOptions = $searchService->getAvailableFilterOptions(
            $this->selectedProductType,
            $options
        );
    }

    public function clearFilters()
    {
        $this->reset([
            'searchTerm',
            'selectedProductType',
            'selectedCategory',
            'selectedBrand',
            'minPrice',
            'maxPrice',
            'inStockOnly',
            'variantFilters',
        ]);
        $this->loadAvailableFilters();
        $this->resetPage();
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function getProductsProperty()
    {
        $searchService = app(ProductSearchService::class);

        // Quick search if there's a search term
        if (!empty($this->searchTerm)) {
            return $searchService->quickSearch($this->searchTerm, [
                'product_type_id' => $this->selectedProductType,
                'category_id' => $this->selectedCategory,
                'in_stock_only' => $this->inStockOnly,
                'min_price' => $this->minPrice,
                'max_price' => $this->maxPrice,
                'brand' => $this->selectedBrand,
                'order_by' => $this->orderBy,
                'order_direction' => $this->orderDirection,
            ]);
        }

        // Search by variant attributes if filters are applied
        if (!empty($this->variantFilters)) {
            $cleanFilters = array_filter($this->variantFilters);

            if (!empty($cleanFilters)) {
                return $searchService->searchByVariantAttributes($cleanFilters, [
                    'product_type_id' => $this->selectedProductType,
                    'category_id' => $this->selectedCategory,
                    'in_stock_only' => $this->inStockOnly,
                    'min_price' => $this->minPrice,
                    'max_price' => $this->maxPrice,
                    'brand' => $this->selectedBrand,
                    'order_by' => $this->orderBy,
                    'order_direction' => $this->orderDirection,
                ]);
            }
        }

        // Default: return all products with filters
        return \App\Models\Product::query()
            ->with(['productType', 'category', 'variants'])
            ->when($this->selectedProductType, function($q) {
                $q->where('product_type_id', $this->selectedProductType);
            })
            ->when($this->selectedCategory, function($q) {
                $q->where('category_id', $this->selectedCategory);
            })
            ->when($this->selectedBrand, function($q) {
                $q->where('brand', 'like', '%' . $this->selectedBrand . '%');
            })
            ->when($this->minPrice, function($q) {
                $q->where('price', '>=', $this->minPrice);
            })
            ->when($this->maxPrice, function($q) {
                $q->where('price', '<=', $this->maxPrice);
            })
            ->when($this->inStockOnly, function($q) {
                $q->whereHas('variants', function($vq) {
                    $vq->where('stock_quantity', '>', 0);
                });
            })
            ->where('status', 'active')
            ->orderBy($this->orderBy, $this->orderDirection)
            ->paginate(20);
    }

    public function render()
    {
        return view('livewire.product.product-search', [
            'products' => $this->products,
            'productTypes' => ProductType::active()->get(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }
}
