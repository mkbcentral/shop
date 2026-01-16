<?php

namespace App\Livewire\Invoice;

use App\Actions\Invoice\CreateInvoiceAction;
use App\Repositories\SaleRepository;
use Livewire\Component;

class InvoiceCreate extends Component
{
    public $saleId = null;
    public $invoiceDate;
    public $dueDate;
    public $status = 'draft';

    public $selectedSale = null;

    protected $rules = [
        'saleId' => 'required|exists:sales,id',
        'invoiceDate' => 'required|date',
        'dueDate' => 'nullable|date|after_or_equal:invoiceDate',
        'status' => 'required|in:draft,sent',
    ];

    protected $messages = [
        'saleId.required' => 'Veuillez sélectionner une vente.',
        'saleId.exists' => 'La vente sélectionnée n\'existe pas.',
        'invoiceDate.required' => 'La date de facturation est requise.',
        'invoiceDate.date' => 'Format de date invalide.',
        'dueDate.date' => 'Format de date invalide.',
        'dueDate.after_or_equal' => 'La date d\'échéance doit être postérieure ou égale à la date de facturation.',
        'status.required' => 'Le statut est requis.',
    ];

    public function mount()
    {
        $this->invoiceDate = now()->format('Y-m-d');
        $this->dueDate = now()->addDays(30)->format('Y-m-d');
    }

    public function updatedSaleId($value, SaleRepository $repository)
    {
        if ($value) {
            $this->selectedSale = $repository->find($value);
        } else {
            $this->selectedSale = null;
        }
    }

    public function save(CreateInvoiceAction $action)
    {
        $this->validate();

        try {
            $invoice = $action->execute($this->saleId, [
                'invoice_date' => $this->invoiceDate,
                'due_date' => $this->dueDate,
                'status' => $this->status,
            ]);

            session()->flash('success', 'Facture créée avec succès.');
            return redirect()->route('invoices.show', $invoice->id);
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function render(SaleRepository $repository)
    {
        // Get sales without invoices
        $sales = $repository->query()
            ->whereDoesntHave('invoice')
            ->where('status', 'completed')
            ->with('client')
            ->orderBy('sale_date', 'desc')
            ->get();

        return view('livewire.invoice.invoice-create', [
            'sales' => $sales,
        ]);
    }
}
