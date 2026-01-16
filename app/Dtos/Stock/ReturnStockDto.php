<?php

namespace App\Dtos\Stock;

readonly class ReturnStockDto
{
    public function __construct(
        public int $product_variant_id,
        public int $quantity,
        public int $user_id,
        public string $reason,
        public ?int $sale_id = null,
        public ?string $reference = null,
        public ?float $unit_price = null,
        public ?string $date = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            product_variant_id: (int) $data['product_variant_id'],
            quantity: (int) $data['quantity'],
            user_id: (int) $data['user_id'],
            reason: $data['reason'],
            sale_id: isset($data['sale_id']) ? (int) $data['sale_id'] : null,
            reference: $data['reference'] ?? null,
            unit_price: isset($data['unit_price']) ? (float) $data['unit_price'] : null,
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
            'sale_id' => $this->sale_id,
            'reference' => $this->reference,
            'unit_price' => $this->unit_price,
            'date' => $this->date,
        ], fn($value) => $value !== null);
    }
}
