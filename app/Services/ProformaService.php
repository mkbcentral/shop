<?php

namespace App\Services;

use App\Models\ProformaInvoice;
use App\Models\ProformaInvoiceItem;
use App\Models\Invoice;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProformaService
{
    /**
     * Create a new proforma invoice.
     */
    public function create(array $data, array $items): ProformaInvoice
    {
        return DB::transaction(function () use ($data, $items) {
            $proforma = ProformaInvoice::create([
                'organization_id' => $data['organization_id'] ?? Auth::user()->currentOrganization?->id,
                'store_id' => $data['store_id'] ?? Auth::user()->current_store_id,
                'user_id' => $data['user_id'] ?? Auth::id(),
                'client_name' => $data['client_name'],
                'client_phone' => $data['client_phone'] ?? null,
                'client_email' => $data['client_email'] ?? null,
                'client_address' => $data['client_address'] ?? null,
                'proforma_date' => $data['proforma_date'] ?? now(),
                'valid_until' => $data['valid_until'] ?? now()->addDays(30),
                'notes' => $data['notes'] ?? null,
                'terms_conditions' => $data['terms_conditions'] ?? null,
                'status' => ProformaInvoice::STATUS_DRAFT,
            ]);

            // Add items
            foreach ($items as $item) {
                ProformaInvoiceItem::create([
                    'proforma_invoice_id' => $proforma->id,
                    'product_variant_id' => $item['product_variant_id'] ?? null,
                    'description' => $item['description'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount' => $item['discount'] ?? 0,
                    'tax_rate' => $item['tax_rate'] ?? 0,
                    'total' => ($item['quantity'] * $item['unit_price']) - ($item['discount'] ?? 0),
                ]);
            }

            // Refresh to recalculate totals
            $proforma->refresh();

            return $proforma;
        });
    }

    /**
     * Update a proforma invoice.
     */
    public function update(ProformaInvoice $proforma, array $data, array $items): ProformaInvoice
    {
        if (!$proforma->canBeEdited()) {
            throw new \Exception('Cette proforma ne peut plus être modifiée.');
        }

        return DB::transaction(function () use ($proforma, $data, $items) {
            $proforma->update([
                'client_name' => $data['client_name'],
                'client_phone' => $data['client_phone'] ?? null,
                'client_email' => $data['client_email'] ?? null,
                'client_address' => $data['client_address'] ?? null,
                'proforma_date' => $data['proforma_date'],
                'valid_until' => $data['valid_until'],
                'notes' => $data['notes'] ?? null,
                'terms_conditions' => $data['terms_conditions'] ?? null,
            ]);

            // Delete existing items and recreate
            $proforma->items()->delete();

            foreach ($items as $item) {
                ProformaInvoiceItem::create([
                    'proforma_invoice_id' => $proforma->id,
                    'product_variant_id' => $item['product_variant_id'] ?? null,
                    'description' => $item['description'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount' => $item['discount'] ?? 0,
                    'tax_rate' => $item['tax_rate'] ?? 0,
                    'total' => ($item['quantity'] * $item['unit_price']) - ($item['discount'] ?? 0),
                ]);
            }

            $proforma->refresh();

            return $proforma;
        });
    }

    /**
     * Mark proforma as sent.
     */
    public function markAsSent(ProformaInvoice $proforma): ProformaInvoice
    {
        $proforma->update(['status' => ProformaInvoice::STATUS_SENT]);
        return $proforma;
    }

    /**
     * Mark proforma as accepted.
     */
    public function accept(ProformaInvoice $proforma): ProformaInvoice
    {
        $proforma->update(['status' => ProformaInvoice::STATUS_ACCEPTED]);
        return $proforma;
    }

    /**
     * Mark proforma as rejected.
     */
    public function reject(ProformaInvoice $proforma): ProformaInvoice
    {
        $proforma->update(['status' => ProformaInvoice::STATUS_REJECTED]);
        return $proforma;
    }

    /**
     * Convert proforma to invoice (creates a sale and invoice).
     */
    public function convertToInvoice(ProformaInvoice $proforma): Invoice
    {
        if (!$proforma->canBeConverted()) {
            throw new \Exception('Cette proforma ne peut pas être convertie. Elle doit être acceptée.');
        }

        // Ensure items are loaded
        $proforma->load('items');

        return DB::transaction(function () use ($proforma) {
            // Create sale from proforma
            $sale = Sale::create([
                'organization_id' => $proforma->organization_id,
                'store_id' => $proforma->store_id,
                'user_id' => Auth::id(),
                'client_id' => null, // No linked client for proforma
                'sale_date' => now(),
                'subtotal' => $proforma->subtotal ?? 0,
                'discount' => $proforma->discount ?? 0,
                'tax' => $proforma->tax_amount ?? 0,
                'total' => $proforma->total ?? 0,
                'payment_method' => 'cash',
                'payment_status' => 'pending',
                'status' => 'completed',
                'notes' => "Converti depuis proforma {$proforma->proforma_number}",
            ]);

            // Create sale items
            foreach ($proforma->items as $proformaItem) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_variant_id' => $proformaItem->product_variant_id,
                    'quantity' => $proformaItem->quantity,
                    'unit_price' => $proformaItem->unit_price ?? 0,
                    'discount' => $proformaItem->discount ?? 0,
                    'subtotal' => $proformaItem->total ?? ($proformaItem->quantity * ($proformaItem->unit_price ?? 0)),
                ]);
            }

            // Create invoice
            $invoice = Invoice::create([
                'organization_id' => $proforma->organization_id,
                'store_id' => $proforma->store_id,
                'sale_id' => $sale->id,
                'invoice_date' => now(),
                'due_date' => now()->addDays(30),
                'subtotal' => $proforma->subtotal ?? 0,
                'tax' => $proforma->tax_amount ?? 0,
                'total' => $proforma->total ?? 0,
                'status' => Invoice::STATUS_DRAFT,
            ]);

            // Update proforma status
            $proforma->update([
                'status' => ProformaInvoice::STATUS_CONVERTED,
                'converted_to_invoice_id' => $invoice->id,
                'converted_at' => now(),
            ]);

            return $invoice;
        });
    }

    /**
     * Duplicate a proforma.
     */
    public function duplicate(ProformaInvoice $proforma): ProformaInvoice
    {
        return DB::transaction(function () use ($proforma) {
            $newProforma = ProformaInvoice::create([
                'organization_id' => $proforma->organization_id,
                'store_id' => $proforma->store_id,
                'user_id' => Auth::id(),
                'client_name' => $proforma->client_name,
                'client_phone' => $proforma->client_phone,
                'client_email' => $proforma->client_email,
                'client_address' => $proforma->client_address,
                'proforma_date' => now(),
                'valid_until' => now()->addDays(30),
                'notes' => $proforma->notes,
                'terms_conditions' => $proforma->terms_conditions,
                'status' => ProformaInvoice::STATUS_DRAFT,
            ]);

            // Duplicate items
            foreach ($proforma->items as $item) {
                ProformaInvoiceItem::create([
                    'proforma_invoice_id' => $newProforma->id,
                    'product_variant_id' => $item->product_variant_id,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'discount' => $item->discount,
                    'tax_rate' => $item->tax_rate,
                    'total' => $item->total,
                ]);
            }

            $newProforma->refresh();

            return $newProforma;
        });
    }

    /**
     * Mark expired proformas.
     */
    public function markExpiredProformas(): int
    {
        return ProformaInvoice::expired()
            ->update(['status' => ProformaInvoice::STATUS_EXPIRED]);
    }

    /**
     * Get statistics for dashboard.
     */
    public function getStatistics(?int $storeId = null): array
    {
        $query = ProformaInvoice::query()
            ->when($storeId, fn($q) => $q->where('store_id', $storeId));

        return [
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->pending()->count(),
            'accepted' => (clone $query)->where('status', ProformaInvoice::STATUS_ACCEPTED)->count(),
            'converted' => (clone $query)->where('status', ProformaInvoice::STATUS_CONVERTED)->count(),
            'total_amount' => (clone $query)->pending()->sum('total'),
        ];
    }
}
