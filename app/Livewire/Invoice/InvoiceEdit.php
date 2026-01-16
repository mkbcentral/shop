<?php

namespace App\Livewire\Invoice;

use App\Actions\Invoice\UpdateInvoiceAction;
use App\Repositories\InvoiceRepository;
use Livewire\Component;

class InvoiceEdit extends Component
{
    public $invoiceId;
    public $invoice;
    public $invoiceDate;
    public $dueDate;
    public $status;

    protected $rules = [
        'invoiceDate' => 'required|date',
        'dueDate' => 'nullable|date|after_or_equal:invoiceDate',
        'status' => 'required|in:draft,sent,paid,cancelled',
    ];

    protected $messages = [
        'invoiceDate.required' => 'La date de facturation est requise.',
        'invoiceDate.date' => 'Format de date invalide.',
        'dueDate.date' => 'Format de date invalide.',
        'dueDate.after_or_equal' => 'La date d\'échéance doit être postérieure ou égale à la date de facturation.',
        'status.required' => 'Le statut est requis.',
    ];

    public function mount($id, InvoiceRepository $repository)
    {
        $this->invoiceId = $id;
        $this->invoice = $repository->find($id);

        if (!$this->invoice) {
            session()->flash('error', 'Facture introuvable.');
            return redirect()->route('invoices.index');
        }

        $this->invoiceDate = $this->invoice->invoice_date->format('Y-m-d');
        $this->dueDate = $this->invoice->due_date ? $this->invoice->due_date->format('Y-m-d') : null;
        $this->status = $this->invoice->status;
    }

    public function save(UpdateInvoiceAction $action)
    {
        $this->validate();

        try {
            $action->execute($this->invoiceId, [
                'invoice_date' => $this->invoiceDate,
                'due_date' => $this->dueDate,
                'status' => $this->status,
            ]);

            session()->flash('success', 'Facture mise à jour avec succès.');
            return redirect()->route('invoices.show', $this->invoiceId);
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.invoice.invoice-edit');
    }
}
