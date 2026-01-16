<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Sale;
use App\Repositories\InvoiceRepository;
use App\Repositories\SaleRepository;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    public function __construct(
        private InvoiceRepository $invoiceRepository,
        private SaleRepository $saleRepository
    ) {}

    /**
     * Create an invoice from a sale.
     */
    public function createFromSale(int $saleId, array $data = []): Invoice
    {
        return DB::transaction(function () use ($saleId, $data) {
            $sale = $this->saleRepository->find($saleId);

            if (!$sale) {
                throw new \Exception("Sale not found");
            }

            if ($sale->invoice) {
                throw new \Exception("Invoice already exists for this sale");
            }

            $invoiceData = [
                'sale_id' => $saleId,
                'invoice_date' => $data['invoice_date'] ?? now(),
                'due_date' => $data['due_date'] ?? null,
                'subtotal' => $sale->subtotal,
                'tax' => $sale->tax,
                'total' => $sale->total,
                'status' => $data['status'] ?? Invoice::STATUS_DRAFT,
            ];

            $invoice = $this->invoiceRepository->create($invoiceData);

            return $invoice->fresh('sale.client', 'sale.items.productVariant.product');
        });
    }

    /**
     * Update an invoice.
     */
    public function updateInvoice(int $invoiceId, array $data): Invoice
    {
        $invoice = $this->invoiceRepository->find($invoiceId);

        if (!$invoice) {
            throw new \Exception("Invoice not found");
        }

        $this->invoiceRepository->update($invoice, $data);

        return $invoice->fresh();
    }

    /**
     * Mark invoice as paid.
     */
    public function markAsPaid(int $invoiceId): Invoice
    {
        return DB::transaction(function () use ($invoiceId) {
            $invoice = $this->invoiceRepository->find($invoiceId);

            if (!$invoice) {
                throw new \Exception("Invoice not found");
            }

            if ($invoice->status === Invoice::STATUS_PAID) {
                throw new \Exception("Invoice is already paid");
            }

            $invoice->markAsPaid();

            return $invoice->fresh();
        });
    }

    /**
     * Send invoice (change status to sent).
     */
    public function sendInvoice(int $invoiceId): Invoice
    {
        $invoice = $this->invoiceRepository->find($invoiceId);

        if (!$invoice) {
            throw new \Exception("Invoice not found");
        }

        $invoice->status = Invoice::STATUS_SENT;
        $invoice->save();

        // Here you could add email sending logic

        return $invoice->fresh();
    }

    /**
     * Cancel an invoice.
     */
    public function cancelInvoice(int $invoiceId): Invoice
    {
        $invoice = $this->invoiceRepository->find($invoiceId);

        if (!$invoice) {
            throw new \Exception("Invoice not found");
        }

        if ($invoice->status === Invoice::STATUS_PAID) {
            throw new \Exception("Cannot cancel a paid invoice");
        }

        $invoice->status = Invoice::STATUS_CANCELLED;
        $invoice->save();

        return $invoice->fresh();
    }

    /**
     * Get invoice statistics.
     */
    public function getStatistics(): array
    {
        return $this->invoiceRepository->statistics();
    }

    /**
     * Get overdue invoices.
     */
    public function getOverdueInvoices(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->invoiceRepository->overdue();
    }

    /**
     * Generate invoice PDF (placeholder).
     */
    public function generatePdf(int $invoiceId): string
    {
        $invoice = $this->invoiceRepository->find($invoiceId);

        if (!$invoice) {
            throw new \Exception("Invoice not found");
        }

        // TODO: Implement PDF generation logic
        // This could use packages like dompdf or barryvdh/laravel-dompdf

        return "PDF generation would happen here for invoice: {$invoice->invoice_number}";
    }
}
