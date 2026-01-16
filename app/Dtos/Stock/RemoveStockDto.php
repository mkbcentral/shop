<?php

namespace App\Dtos\Stock;

readonly class RemoveStockDto
{
    public function __construct(
        public int $product_variant_id,
        public int $quantity,
        public int $user_id,
        public string $reason,
        public string $movement_type = 'adjustment',
        public ?string $reference = null,
        public ?float $unit_price = null,
        public ?float $total_price = null,
        public ?string $date = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            product_variant_id: (int) $data['product_variant_id'],
            quantity: (int) $data['quantity'],
            user_id: (int) $data['user_id'],
            reason: $data['reason'],
            movement_type: $data['movement_type'] ?? 'adjustment',
            reference: $data['reference'] ?? null,
            unit_price: isset($data['unit_price']) ? (float) $data['unit_price'] : null,
            total_price: isset($data['total_price']) ? (float) $data['total_price'] : null,
            date: $data['date'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'product_variant_id' => $this->product_variant_id,
            'quantity' => $this->quantity,
            'user_id' => $this->user_id,
            'reason' => $this->reason,
            'movement_type' => $this->movement_type,
            'reference' => $this->reference,
            'unit_price' => $this->unit_price,
            'total_price' => $this->total_price,
            'date' => $this->date,
        ], fn($value) => $value !== null);
    }
}
