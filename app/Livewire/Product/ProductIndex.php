<?php

namespace App\Livewire\Product;

use App\Services\ProductService;
use App\Services\ProductExcelExporter;
use App\Services\ProductKPIService;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use App\Dtos\Product\ProductFilterDto;
use Livewire\Component;
use Livewire\WithPagination;

class ProductIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    public $statusFilter = '';
    public $stockLevelFilter = '';
    public $perPage = 15;

    public $sortField = 'name';
    public $sortDirection = 'asc';

    public $productToDelete = null;

    // Bulk actions
    public $selected = [];
    public $selectAll = false;
    public $bulkAction = '';

    // View modes
    public $viewMode = 'table'; // 'table' or 'grid'
    public $densityMode = 'comfortable'; // 'compact', 'comfortable', 'spacious'

    protected $listeners = ['productSaved' => '$refresh'];

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'stockLevelFilter' => ['except' => ''],
        'viewMode' => ['except' => 'table'],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingStockLevelFilter()
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected = $this->getProductIds();
        } else {
            $this->selected = [];
        }
    }

    private function getProductIds()
    {
        $filters = $this->getFiltersDto();

        return app(ProductRepository::class)->paginateWithFilters(
            perPage: $filters->perPage,
            search: $filters->search,
            categoryId: $filters->categoryId,
            status: $filters->status,
            stockLevel: $filters->stockLevel,
            sortField: $filters->sortField,
            sortDirection: $filters->sortDirection
        )->pluck('id')->toArray();
    }

    private function getFiltersDto(): ProductFilterDto
    {
        return ProductFilterDto::fromLivewire([
            'search' => $this->search,
            'categoryFilter' => $this->categoryFilter,
            'statusFilter' => $this->statusFilter,
            'stockLevelFilter' => $this->stockLevelFilter,
            'viewMode' => $this->viewMode,
            'sortField' => $this->sortField,
            'sortDirection' => $this->sortDirection,
            'perPage' => $this->perPage,
        ]);
    }

    public function toggleSelect($productId)
    {
        if (in_array($productId, $this->selected)) {
            $this->selected = array_diff($this->selected, [$productId]);
        } else {
            $this->selected[] = $productId;
        }

        $this->selectAll = count($this->selected) === count($this->getProductIds());
    }

    public function executeBulkAction(ProductService $service)
    {
        if (empty($this->selected) || empty($this->bulkAction)) {
            $this->dispatch('show-toast', message: 'Veuillez sélectionner des produits et une action.', type: 'warning');
            return;
        }

        try {
            switch ($this->bulkAction) {
                case 'delete':
                    $this->bulkDelete($service);
                    break;
                case 'activate':
                    $this->bulkUpdateStatus('active');
                    break;
                case 'deactivate':
                    $this->bulkUpdateStatus('inactive');
                    break;
            }

            $this->selected = [];
            $this->selectAll = false;
            $this->bulkAction = '';

        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Erreur : ' . $e->getMessage(), type: 'error');
        }
    }

    private function bulkDelete(ProductService $service)
    {
        $count = 0;
        $errors = [];

        foreach ($this->selected as $productId) {
            try {
                $service->deleteProduct($productId);
                $count++;
            } catch (\Exception $e) {
                $errors[] = $e->getMessage();
            }
        }

        if ($count > 0) {
            $this->dispatch('show-toast', message: "{$count} produit(s) supprimé(s) avec succès.", type: 'success');
        }

        if (!empty($errors)) {
            $this->dispatch('show-toast', message: 'Certains produits n\'ont pas pu être supprimés : ' . implode(', ', array_slice($errors, 0, 3)), type: 'warning');
        }
    }

    private function bulkUpdateStatus(string $status)
    {
        $repository = app(ProductRepository::class);
        $count = 0;

        foreach ($this->selected as $productId) {
            $product = $repository->find($productId);
            if ($product) {
                $repository->update($product, ['status' => $status]);
                $count++;
            }
        }

        $statusLabel = $status === 'active' ? 'activé(s)' : 'désactivé(s)';
        $this->dispatch('show-toast', message: "{$count} produit(s) {$statusLabel} avec succès.", type: 'success');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'categoryFilter', 'statusFilter', 'stockLevelFilter']);
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function delete(ProductService $service)
    {
        if (!$this->productToDelete) {
            return;
        }

        try {
            $service->deleteProduct($this->productToDelete);
            $this->dispatch('show-toast', message: 'Produit supprimé avec succès.', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Erreur : ' . $e->getMessage(), type: 'error');
        }

        $this->productToDelete = null;
    }

    public function exportExcel(ProductRepository $repository, ProductExcelExporter $exporter)
    {
        try {
            $filters = $this->getFiltersDto();

            // Get all products with current filters (no pagination)
            $products = $repository->getAllWithFilters(
                search: $filters->search,
                categoryId: $filters->categoryId,
                status: $filters->status,
                stockLevel: $filters->stockLevel,
                sortField: $filters->sortField,
                sortDirection: $filters->sortDirection
            );

            return $exporter->export($products);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Erreur lors de l\'export: ' . $e->getMessage(), type: 'error');
            return null;
        }
    }

    public function render(ProductRepository $repository, CategoryRepository $categoryRepository, ProductKPIService $kpiService)
    {
        $filters = $this->getFiltersDto();

        $products = $repository->paginateWithFilters(
            perPage: $filters->perPage,
            search: $filters->search,
            categoryId: $filters->categoryId,
            status: $filters->status,
            stockLevel: $filters->stockLevel,
            sortField: $filters->sortField,
            sortDirection: $filters->sortDirection
        );

        return view('livewire.product.product-index', [
            'products' => $products,
            'categories' => $categoryRepository->all(),
            'kpis' => $kpiService->calculateAllKPIs(),
        ]);
    }
}
