<?php

namespace App\Repositories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

class InvoiceRepository
{
    /**
     * Get a new query builder for Invoice.
     */
    public function query(): Builder
    {
        $query = Invoice::query();

        // Si l'utilisateur n'a aucun accès aux stores, retourner une requête vide
        if (user_has_no_store_access()) {
            return $query->whereRaw('1 = 0');
        }

        // Filter by current store si l'utilisateur doit être filtré
        if (should_filter_by_store()) {
            $query->where('store_id', current_store_id());
        }

        // Sinon, l'admin voit tout (pas de filtre)

        return $query;
    }

    /**
     * Get all invoices.
     */
    public function all(): Collection
    {
        $query = Invoice::with('sale.client');

        // Si l'utilisateur n'a aucun accès aux stores, retourner une collection vide
        if (user_has_no_store_access()) {
            return collect();
        }

        // Filter by current store si l'utilisateur doit être filtré
        if (should_filter_by_store()) {
            $query->where('store_id', current_store_id());
        }

        return $query->orderBy('invoice_date', 'desc')->get();
    }

    /**
     * Find invoice by ID.
     */
    public function find(int $id): ?Invoice
    {
        return Invoice::with('sale.client', 'sale.items.productVariant.product')
            ->find($id);
    }

    /**
     * Find invoice by invoice number.
     */
    public function findByNumber(string $invoiceNumber): ?Invoice
    {
        return Invoice::where('invoice_number', $invoiceNumber)
            ->with('sale.client')
            ->first();
    }

    /**
     * Create a new invoice.
     */
    public function create(array $data): Invoice
    {
        return Invoice::create($data);
    }

    /**
     * Update an invoice.
     */
    public function update(Invoice $invoice, array $data): bool
    {
        return $invoice->update($data);
    }

    /**
     * Delete an invoice.
     */
    public function delete(Invoice $invoice): bool
    {
        return $invoice->delete();
    }

    /**
     * Get paid invoices.
     */
    public function paid(): Collection
    {
        $query = Invoice::paid()
            ->with('sale.client');

        // Si l'utilisateur n'a aucun accès aux stores, retourner une collection vide
        if (user_has_no_store_access()) {
            return collect();
        }

        // Filter by current store si l'utilisateur doit être filtré
        if (should_filter_by_store()) {
            $query->where('store_id', current_store_id());
        }

        return $query->orderBy('invoice_date', 'desc')->get();
    }

    /**
     * Get unpaid invoices.
     */
    public function unpaid(): Collection
    {
        $query = Invoice::unpaid()
            ->with('sale.client');

        // Si l'utilisateur n'a aucun accès aux stores, retourner une collection vide
        if (user_has_no_store_access()) {
            return collect();
        }

        // Filter by current store si l'utilisateur doit être filtré
        if (should_filter_by_store()) {
            $query->where('store_id', current_store_id());
        }

        return $query->orderBy('invoice_date', 'desc')->get();
    }

    /**
     * Get overdue invoices.
     */
    public function overdue(): Collection
    {
        $query = Invoice::overdue()
            ->with('sale.client');

        // Si l'utilisateur n'a aucun accès aux stores, retourner une collection vide
        if (user_has_no_store_access()) {
            return collect();
        }

        // Filter by current store si l'utilisateur doit être filtré
        if (should_filter_by_store()) {
            $query->where('store_id', current_store_id());
        }

        return $query->orderBy('due_date')->get();
    }

    /**
     * Get invoices by date range.
     */
    public function byDateRange(string $startDate, string $endDate): Collection
    {
        $query = Invoice::whereBetween('invoice_date', [$startDate, $endDate])
            ->with('sale.client');

        // Si l'utilisateur n'a aucun accès aux stores, retourner une collection vide
        if (user_has_no_store_access()) {
            return collect();
        }

        // Filter by current store si l'utilisateur doit être filtré
        if (should_filter_by_store()) {
            $query->where('store_id', current_store_id());
        }

        return $query->orderBy('invoice_date', 'desc')->get();
    }

    /**
     * Get invoice statistics.
     */
    public function statistics(): array
    {
        $query = Invoice::query();

        // Si l'utilisateur n'a aucun accès aux stores, retourner des stats vides
        if (user_has_no_store_access()) {
            return [
                'total_invoices' => 0,
                'paid_invoices' => 0,
                'unpaid_invoices' => 0,
                'total_paid_amount' => 0,
                'total_unpaid_amount' => 0,
            ];
        }

        // Filter by current store si l'utilisateur doit être filtré
        if (should_filter_by_store()) {
            $query->where('store_id', current_store_id());
        }

        $all = $query->get();
        $paid = $all->where('status', 'paid');
        $unpaid = $all->whereIn('status', ['draft', 'sent']);

        return [
            'total_invoices' => $all->count(),
            'paid_invoices' => $paid->count(),
            'unpaid_invoices' => $unpaid->count(),
            'total_paid_amount' => $paid->sum('total'),
            'total_unpaid_amount' => $unpaid->sum('total'),
        ];
    }
}
