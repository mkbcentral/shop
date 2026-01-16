<?php

namespace App\Dtos\Stock;

readonly class AdjustStockDto
{
    public function __construct(
        public int $product_variant_id,
        public int $new_quantity,
        public int $user_id,
        public ?string $reason = null,
        public ?string $reference = null,
        public ?string $date = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            product_variant_id: (int) $data['product_variant_id'],
            new_quantity: (int) $data['new_quantity'],
            user_id: (int) $data['user_id'],
            reason: $data['reason'] ?? null,
            reference: $data['reference'] ?? null,
            date: $data['date'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'product_variant_id' => $this->product_variant_id,
            'new_quantity' => $this->new_quantity,
            'user_id' => $this->user_id,
            'reason' => $this->reason,
            'reference' => $this->reference,
            'date' => $this->date,
        ], fn($value) => $value !== null);
    }
}
