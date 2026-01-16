<?php

namespace App\Dtos\Sale;

readonly class ProcessSaleDto
{
    public function __construct(
        public int $user_id,
        public string $payment_method,
        public array $items,
        public ?int $client_id = null,
        public ?string $sale_date = null,
        public float $discount = 0.0,
        public float $tax = 0.0,
        public bool $complete = true,
        public bool $generate_invoice = false,
        public ?string $invoice_date = null,
        public ?string $due_date = null,
        public string $invoice_status = 'sent',
        public ?string $notes = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            user_id: (int) $data['user_id'],
            payment_method: $data['payment_method'],
            items: $data['items'],
            client_id: isset($data['client_id']) ? (int) $data['client_id'] : null,
            sale_date: $data['sale_date'] ?? null,
            discount: (float) ($data['discount'] ?? 0.0),
            tax: (float) ($data['tax'] ?? 0.0),
            complete: (bool) ($data['complete'] ?? true),
            generate_invoice: (bool) ($data['generate_invoice'] ?? false),
            invoice_date: $data['invoice_date'] ?? null,
            due_date: $data['due_date'] ?? null,
            invoice_status: $data['invoice_status'] ?? 'sent',
            notes: $data['notes'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'user_id' => $this->user_id,
            'payment_method' => $this->payment_method,
            'items' => $this->items,
            'client_id' => $this->client_id,
            'sale_date' => $this->sale_date,
            'discount' => $this->discount,
            'tax' => $this->tax,
            'complete' => $this->complete,
            'generate_invoice' => $this->generate_invoice,
            'invoice_date' => $this->invoice_date,
            'due_date' => $this->due_date,
            'invoice_status' => $this->invoice_status,
            'notes' => $this->notes,
        ], fn($value) => $value !== null);
    }
}
