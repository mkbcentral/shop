<?php

namespace App\Dtos\Purchase;

readonly class CreatePurchaseDto
{
    public function __construct(
        public int $supplier_id,
        public string $purchase_date,
        public float $total,
        public string $status = 'pending',
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            supplier_id: (int) $data['supplier_id'],
            purchase_date: $data['purchase_date'],
            total: (float) $data['total'],
            status: $data['status'] ?? 'pending',
        );
    }

    public function toArray(): array
    {
        return [
            'supplier_id' => $this->supplier_id,
            'purchase_date' => $this->purchase_date,
            'total' => $this->total,
            'status' => $this->status,
        ];
    }
}
