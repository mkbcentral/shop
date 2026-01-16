<?php

namespace App\Dtos\Product;

readonly class UpdateVariantDto
{
    public function __construct(
        public ?string $size = null,
        public ?string $color = null,
        public ?string $sku = null,
        public ?string $barcode = null,
        public ?int $stock_quantity = null,
        public ?float $additional_price = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            size: $data['size'] ?? null,
            color: $data['color'] ?? null,
            sku: $data['sku'] ?? null,
            barcode: $data['barcode'] ?? null,
            stock_quantity: isset($data['stock_quantity']) ? (int) $data['stock_quantity'] : null,
            additional_price: isset($data['additional_price']) ? (float) $data['additional_price'] : null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'size' => $this->size,
            'color' => $this->color,
            'sku' => $this->sku,
            'barcode' => $this->barcode,
            'stock_quantity' => $this->stock_quantity,
            'additional_price' => $this->additional_price,
        ], fn($value) => $value !== null);
    }
}
