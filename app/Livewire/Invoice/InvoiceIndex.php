<?php

namespace App\Livewire\Invoice;

use App\Actions\Invoice\CreateInvoiceAction;
use App\Actions\Invoice\DeleteInvoiceAction;
use App\Actions\Invoice\MarkInvoiceAsPaidAction;
use App\Actions\Invoice\SendInvoiceAction;
use App\Actions\Invoice\CancelInvoiceAction;
use App\Repositories\InvoiceRepository;
use Livewire\Component;
use Livewire\WithPagination;

class InvoiceIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $perPage = 15;

    public $sortField = 'invoice_date';
    public $sortDirection = 'desc';

    public $invoiceToDelete = null;
    public $invoiceToProcess = null;
    public $actionType = '';

    // Create invoice modal properties
    public $saleId = null;
    public $invoiceDate;
    public $dueDate;
    public $invoiceStatus = 'draft';
    public $selectedSale = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'sortField' => ['except' => 'invoice_date'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
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

    public function openCreateModal()
    {
        $this->saleId = null;
        $this->invoiceDate = now()->format('Y-m-d');
        $this->dueDate = now()->addDays(30)->format('Y-m-d');
        $this->invoiceStatus = 'draft';
        $this->selectedSale = null;
        $this->resetValidation();
        $this->dispatch('open-create-modal');
    }

    public function closeCreateModal()
    {
        $this->saleId = null;
        $this->selectedSale = null;
        $this->resetValidation();
        $this->dispatch('close-create-modal');
    }

    public function updatedSaleId($value)
    {
        if ($value) {
            $repository = app(\App\Repositories\SaleRepository::class);
            $this->selectedSale = $repository->find($value);
        } else {
            $this->selectedSale = null;
        }
    }

    public function createInvoice()
    {
        $this->validate([
            'saleId' => 'required|exists:sales,id',
            'invoiceDate' => 'required|date',
            'dueDate' => 'nullable|date|after_or_equal:invoiceDate',
            'invoiceStatus' => 'required|in:draft,sent',
        ], [
            'saleId.required' => 'Veuillez sélectionner une vente.',
            'saleId.exists' => 'La vente sélectionnée n\'existe pas.',
            'invoiceDate.required' => 'La date de facturation est requise.',
            'invoiceDate.date' => 'Format de date invalide.',
            'dueDate.date' => 'Format de date invalide.',
            'dueDate.after_or_equal' => 'La date d\'échéance doit être postérieure ou égale à la date de facturation.',
            'invoiceStatus.required' => 'Le statut est requis.',
        ]);

        try {
            $action = app(CreateInvoiceAction::class);
            $invoice = $action->execute($this->saleId, [
                'invoice_date' => $this->invoiceDate,
                'due_date' => $this->dueDate,
                'status' => $this->invoiceStatus,
            ]);

            session()->flash('success', 'Facture créée avec succès.');
            $this->closeCreateModal();
            $this->dispatch('close-create-modal');
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }
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

    public function markAsPaid()
    {
        if (!$this->invoiceToProcess) {
            return;
        }

        try {
            $action = app(MarkInvoiceAsPaidAction::class);
            $repository = app(InvoiceRepository::class);
            
            $invoice = $repository->find($this->invoiceToProcess);

            if ($invoice) {
                $action->execute($invoice->id);
                session()->flash('success', 'Facture marquée comme payée avec succès.');
            } else {
                session()->flash('error', 'Facture introuvable.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }

        $this->invoiceToProcess = null;
    }

    public function sendInvoice()
    {
        if (!$this->invoiceToProcess) {
            return;
        }

        try {
            $action = app(SendInvoiceAction::class);
            $repository = app(InvoiceRepository::class);
            
            $invoice = $repository->find($this->invoiceToProcess);

            if ($invoice) {
                $action->execute($invoice->id);
                session()->flash('success', 'Facture envoyée avec succès.');
            } else {
                session()->flash('error', 'Facture introuvable.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }

        $this->invoiceToProcess = null;
    }

    public function cancelInvoice()
    {
        if (!$this->invoiceToProcess) {
            return;
        }

        try {
            $action = app(CancelInvoiceAction::class);
            $repository = app(InvoiceRepository::class);
            
            $invoice = $repository->find($this->invoiceToProcess);

            if ($invoice) {
                $action->execute($invoice->id);
                session()->flash('success', 'Facture annulée avec succès.');
            } else {
                session()->flash('error', 'Facture introuvable.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }

        $this->invoiceToProcess = null;
    }

    public function delete()
    {
        if (!$this->invoiceToDelete) {
            return;
        }

        try {
            $action = app(DeleteInvoiceAction::class);
            $repository = app(InvoiceRepository::class);
            
            $invoice = $repository->find($this->invoiceToDelete);

            if ($invoice) {
                $action->execute($invoice->id);
                session()->flash('success', 'Facture supprimée avec succès.');
            } else {
                session()->flash('error', 'Facture introuvable.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }

        $this->invoiceToDelete = null;
    }

    public function render(InvoiceRepository $repository, \App\Repositories\SaleRepository $saleRepository)
    {
        $query = $repository->query()
            ->with(['sale.client', 'sale.items']);

        // Apply search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('invoice_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('sale.client', function($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Apply status filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Apply date range filter
        if ($this->dateFrom) {
            $query->where('invoice_date', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->where('invoice_date', '<=', $this->dateTo);
        }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $invoices = $query->paginate($this->perPage);

        // Calculate statistics
        $statistics = $repository->statistics();

        // Get sales without invoices for the create modal
        $availableSales = $saleRepository->query()
            ->whereDoesntHave('invoice')
            ->where('status', 'completed')
            ->with('client')
            ->orderBy('sale_date', 'desc')
            ->get();

        return view('livewire.invoice.invoice-index', [
            'invoices' => $invoices,
            'statistics' => $statistics,
            'availableSales' => $availableSales,
        ]);
    }
}
