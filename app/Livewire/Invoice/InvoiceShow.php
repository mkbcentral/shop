<?php

namespace App\Livewire\Invoice;

use App\Actions\Invoice\MarkInvoiceAsPaidAction;
use App\Actions\Invoice\SendInvoiceAction;
use App\Actions\Invoice\CancelInvoiceAction;
use App\Repositories\InvoiceRepository;
use Livewire\Component;

class InvoiceShow extends Component
{
    public $invoiceId;
    public $invoice;

    public function mount($id, InvoiceRepository $repository)
    {
        $this->invoiceId = $id;
        $this->invoice = $repository->find($id);

        if (!$this->invoice) {
            session()->flash('error', 'Facture introuvable.');
            return redirect()->route('invoices.index');
        }
    }

    public function markAsPaid(MarkInvoiceAsPaidAction $action, InvoiceRepository $repository)
    {
        try {
            $action->execute($this->invoiceId);
            $this->invoice = $repository->find($this->invoiceId);
            session()->flash('success', 'Facture marquée comme payée avec succès.');
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function sendInvoice(SendInvoiceAction $action, InvoiceRepository $repository)
    {
        try {
            $action->execute($this->invoiceId);
            $this->invoice = $repository->find($this->invoiceId);
            session()->flash('success', 'Facture envoyée avec succès.');
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function cancelInvoice(CancelInvoiceAction $action, InvoiceRepository $repository)
    {
        try {
            $action->execute($this->invoiceId);
            $this->invoice = $repository->find($this->invoiceId);
            session()->flash('success', 'Facture annulée avec succès.');
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.invoice.invoice-show');
    }
}
