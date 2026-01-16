<?php

namespace App\Dtos\Invoice;

readonly class UpdateInvoiceDto
{
    public function __construct(
        public ?string $invoice_date = null,
        public ?string $due_date = null,
        public ?string $status = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            invoice_date: $data['invoice_date'] ?? null,
            due_date: $data['due_date'] ?? null,
            status: $data['status'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'invoice_date' => $this->invoice_date,
            'due_date' => $this->due_date,
            'status' => $this->status,
        ], fn($value) => $value !== null);
    }
}
