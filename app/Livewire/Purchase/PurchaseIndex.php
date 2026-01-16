<?php

namespace App\Livewire\Purchase;

use App\Actions\Purchase\DeletePurchaseAction;
use App\Repositories\PurchaseRepository;
use App\Repositories\SupplierRepository;
use App\Services\PurchaseService;
use Livewire\Component;
use Livewire\WithPagination;

class PurchaseIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $supplierFilter = '';
    public $statusFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $perPage = 15;

    public $sortField = 'purchase_date';
    public $sortDirection = 'desc';

    public $purchaseToDelete = null;
    public $purchaseToReceive = null;
    public $purchaseToCancel = null;
    public $purchaseToRestore = null;
    public $selectedPurchase = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'supplierFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'sortField' => ['except' => 'purchase_date'],
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

    public function updatingSupplierFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
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

    public function showDetails($purchaseId, PurchaseRepository $repository)
    {
        $this->selectedPurchase = $repository->find($purchaseId);

        if ($this->selectedPurchase) {
            $this->selectedPurchase->load(['supplier', 'items.productVariant.product']);
        }
    }

    public function receivePurchase(PurchaseService $service, PurchaseRepository $repository)
    {
        if (!$this->purchaseToReceive) {
            return;
        }

        try {
            $purchase = $repository->find($this->purchaseToReceive);

            if ($purchase) {
                $service->markAsReceived($purchase->id);
                $this->dispatch('show-toast', message: 'Achat réceptionné avec succès.', type: 'success');
            } else {
                $this->dispatch('show-toast', message: 'Achat introuvable.', type: 'error');
            }
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Erreur : ' . $e->getMessage(), type: 'error');
        }

        $this->purchaseToReceive = null;
    }

    public function cancelPurchase(PurchaseRepository $repository)
    {
        if (!$this->purchaseToCancel) {
            return;
        }

        try {
            $purchase = $repository->find($this->purchaseToCancel);

            if ($purchase && $purchase->status !== 'cancelled') {
                $purchase->update(['status' => 'cancelled']);
                session()->flash('success', 'Achat annulé avec succès.');
            } else {
                session()->flash('error', 'Achat introuvable ou déjà annulé.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }

        $this->purchaseToCancel = null;
    }

    public function restorePurchase(PurchaseRepository $repository)
    {
        if (!$this->purchaseToRestore) {
            return;
        }

        try {
            $purchase = $repository->find($this->purchaseToRestore);

            if ($purchase && $purchase->status === 'cancelled') {
                $purchase->update(['status' => 'pending']);
                session()->flash('success', 'Achat réactivé avec succès. Il est maintenant en attente.');
            } else {
                session()->flash('error', 'Achat introuvable ou non annulé.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }

        $this->purchaseToRestore = null;
    }

    public function delete(DeletePurchaseAction $action, PurchaseRepository $repository)
    {
        if (!$this->purchaseToDelete) {
            return;
        }

        try {
            $purchase = $repository->find($this->purchaseToDelete);

            if ($purchase) {
                $action->execute($purchase->id);
                session()->flash('success', 'Achat supprimé avec succès.');
            } else {
                session()->flash('error', 'Achat introuvable.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }

        $this->purchaseToDelete = null;
    }

    public function render(PurchaseRepository $repository, SupplierRepository $supplierRepository)
    {
        $query = $repository->query()
            ->with(['supplier', 'items']);

        // Apply search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('purchase_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('supplier', function($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Apply supplier filter
        if ($this->supplierFilter) {
            $query->where('supplier_id', $this->supplierFilter);
        }

        // Apply status filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Apply date range filter
        if ($this->dateFrom && $this->dateTo) {
            $query->whereBetween('purchase_date', [$this->dateFrom, $this->dateTo]);
        } elseif ($this->dateFrom) {
            $query->where('purchase_date', '>=', $this->dateFrom);
        } elseif ($this->dateTo) {
            $query->where('purchase_date', '<=', $this->dateTo);
        }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $purchases = $query->paginate($this->perPage);

        // Get suppliers for filter dropdown
        $suppliers = $supplierRepository->all();

        // Calculate statistics
        $stats = $this->calculateStats($repository);

        return view('livewire.purchase.purchase-index', [
            'purchases' => $purchases,
            'suppliers' => $suppliers,
            'stats' => $stats,
        ]);
    }

    private function calculateStats(PurchaseRepository $repository)
    {
        $query = $repository->query();

        // Apply date range
        if ($this->dateFrom && $this->dateTo) {
            $query->whereBetween('purchase_date', [$this->dateFrom, $this->dateTo]);
        } elseif ($this->dateFrom) {
            $query->where('purchase_date', '>=', $this->dateFrom);
        } elseif ($this->dateTo) {
            $query->where('purchase_date', '<=', $this->dateTo);
        }

        $received = (clone $query)->where('status', 'received')->get();
        $pending = (clone $query)->where('status', 'pending')->get();

        return [
            'total_purchases' => $received->count(),
            'total_amount' => $received->sum('total'),
            'pending_purchases' => $pending->count(),
            'pending_amount' => $pending->sum('total'),
        ];
    }
}
