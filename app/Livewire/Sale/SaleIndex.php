<?php

namespace App\Livewire\Sale;

use App\Actions\Sale\DeleteSaleAction;
use App\Repositories\SaleRepository;
use App\Repositories\ClientRepository;
use App\Services\SaleService;
use Livewire\Component;
use Livewire\WithPagination;

class SaleIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $clientFilter = '';
    public $statusFilter = '';
    public $paymentStatusFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $perPage = 15;

    public $sortField = 'sale_date';
    public $sortDirection = 'desc';

    public $saleToDelete = null;
    public $saleToComplete = null;
    public $saleToRestore = null;
    public $saleToForceDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'clientFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'paymentStatusFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'sortField' => ['except' => 'sale_date'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount()
    {
        // Initialize date filters to current month
        if (empty($this->dateFrom)) {
            $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        }
        if (empty($this->dateTo)) {
            $this->dateTo = now()->format('Y-m-d');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingClientFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingPaymentStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
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

    public function completeSale(SaleService $service, SaleRepository $repository)
    {
        if (!$this->saleToComplete) {
            return;
        }

        try {
            $sale = $repository->find($this->saleToComplete);

            if ($sale) {
                $service->completeSale($sale->id);
                session()->flash('success', 'Vente complétée avec succès.');
            } else {
                session()->flash('error', 'Vente introuvable.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }

        $this->saleToComplete = null;
    }

    public function delete(DeleteSaleAction $action, SaleRepository $repository)
    {
        if (!$this->saleToDelete) {
            return;
        }

        try {
            $sale = $repository->find($this->saleToDelete);

            if ($sale) {
                $action->execute($sale->id, 'Supprimé depuis la liste');
                session()->flash('success', 'Vente annulée avec succès.');
            } else {
                session()->flash('error', 'Vente introuvable.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }

        $this->saleToDelete = null;
    }

    public function restoreSale(SaleRepository $repository)
    {
        if (!$this->saleToRestore) {
            return;
        }

        try {
            $sale = $repository->find($this->saleToRestore);

            if ($sale && $sale->status === 'cancelled') {
                $sale->update(['status' => 'pending']);
                session()->flash('success', 'Vente réactivée avec succès. Elle est maintenant en attente.');
            } else {
                session()->flash('error', 'Vente introuvable ou non annulée.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }

        $this->saleToRestore = null;
    }

    public function forceDelete(SaleRepository $repository)
    {
        if (!$this->saleToForceDelete) {
            return;
        }

        try {
            $sale = $repository->find($this->saleToForceDelete);

            if ($sale) {
                // Supprimer les items liés
                $sale->items()->delete();
                // Supprimer les paiements liés
                $sale->payments()->delete();
                // Supprimer la vente
                $sale->delete();
                session()->flash('success', 'Vente supprimée définitivement du système.');
            } else {
                session()->flash('error', 'Vente introuvable.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }

        $this->saleToForceDelete = null;
    }

    public function render(SaleRepository $repository, ClientRepository $clientRepository)
    {
        $query = $repository->query()
            ->with(['client', 'user', 'items']);

        // Apply search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('sale_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('client', function($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Apply client filter
        if ($this->clientFilter) {
            $query->where('client_id', $this->clientFilter);
        }

        // Apply status filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Apply payment status filter
        if ($this->paymentStatusFilter) {
            $query->where('payment_status', $this->paymentStatusFilter);
        }

        // Apply date range filter
        if ($this->dateFrom && $this->dateTo) {
            $query->whereDate('sale_date', '>=', $this->dateFrom)
                  ->whereDate('sale_date', '<=', $this->dateTo);
        } elseif ($this->dateFrom) {
            $query->whereDate('sale_date', '>=', $this->dateFrom);
        } elseif ($this->dateTo) {
            $query->whereDate('sale_date', '<=', $this->dateTo);
        }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $sales = $query->paginate($this->perPage);

        // Get clients for filter dropdown
        $clients = $clientRepository->all();

        // Calculate statistics
        $stats = $this->calculateStats($repository);

        return view('livewire.sale.sale-index', [
            'sales' => $sales,
            'clients' => $clients,
            'stats' => $stats,
        ]);
    }

    private function calculateStats(SaleRepository $repository)
    {
        $query = $repository->query();

        // Apply date range
        if ($this->dateFrom && $this->dateTo) {
            $query->whereDate('sale_date', '>=', $this->dateFrom)
                  ->whereDate('sale_date', '<=', $this->dateTo);
        } elseif ($this->dateFrom) {
            $query->whereDate('sale_date', '>=', $this->dateFrom);
        } elseif ($this->dateTo) {
            $query->whereDate('sale_date', '<=', $this->dateTo);
        }

        $completed = (clone $query)->where('status', 'completed')->get();
        $pending = (clone $query)->where('status', 'pending')->get();

        return [
            'total_sales' => $completed->count(),
            'total_amount' => $completed->sum('total'),
            'pending_sales' => $pending->count(),
            'pending_amount' => $pending->sum('total'),
        ];
    }
}
