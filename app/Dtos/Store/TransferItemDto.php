<?php

namespace App\Dtos\Store;

class TransferItemDto
{
    public function __construct(
        public readonly int $productVariantId,
        public readonly int $quantity,
        public readonly ?string $notes,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            productVariantId: $data['product_variant_id'],
            quantity: $data['quantity'],
            notes: $data['notes'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'product_variant_id' => $this->productVariantId,
            'quantity' => $this->quantity,
            'notes' => $this->notes,
        ];
    }
}
