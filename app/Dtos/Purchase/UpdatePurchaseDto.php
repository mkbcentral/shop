<?php

namespace App\Dtos\Purchase;

readonly class UpdatePurchaseDto
{
    public function __construct(
        public ?int $supplier_id = null,
        public ?string $purchase_date = null,
        public ?float $total = null,
        public ?string $status = null,
        public ?string $notes = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            supplier_id: isset($data['supplier_id']) ? (int) $data['supplier_id'] : null,
            purchase_date: $data['purchase_date'] ?? null,
            total: isset($data['total']) ? (float) $data['total'] : null,
            status: $data['status'] ?? null,
            notes: $data['notes'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'supplier_id' => $this->supplier_id,
            'purchase_date' => $this->purchase_date,
            'total' => $this->total,
            'status' => $this->status,
            'notes' => $this->notes,
        ], fn($value) => $value !== null);
    }
}
