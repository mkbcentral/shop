<?php

namespace App\Dtos\Invoice;

readonly class CreateInvoiceDto
{
    public function __construct(
        public int $sale_id,
        public ?string $invoice_date = null,
        public ?string $due_date = null,
        public string $status = 'draft',
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            sale_id: (int) $data['sale_id'],
            invoice_date: $data['invoice_date'] ?? null,
            due_date: $data['due_date'] ?? null,
            status: $data['status'] ?? 'draft',
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'sale_id' => $this->sale_id,
            'invoice_date' => $this->invoice_date,
            'due_date' => $this->due_date,
            'status' => $this->status,
        ], fn($value) => $value !== null);
    }
}
