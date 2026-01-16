<?php

namespace App\Dtos\Product;

readonly class CreateVariantDto
{
    public function __construct(
        public int $product_id,
        public ?string $size = null,
        public ?string $color = null,
        public ?string $sku = null,
        public ?string $barcode = null,
        public int $stock_quantity = 0,
        public float $additional_price = 0.0,
    ) {}

    public static function fromArray(array $data, int $productId): self
    {
        return new self(
            product_id: $productId,
            size: $data['size'] ?? null,
            color: $data['color'] ?? null,
            sku: $data['sku'] ?? null,
            barcode: $data['barcode'] ?? null,
            stock_quantity: (int) ($data['stock_quantity'] ?? 0),
            additional_price: (float) ($data['additional_price'] ?? 0.0),
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'product_id' => $this->product_id,
            'size' => $this->size,
            'color' => $this->color,
            'sku' => $this->sku,
            'barcode' => $this->barcode,
            'stock_quantity' => $this->stock_quantity,
            'additional_price' => $this->additional_price,
        ], fn($value) => $value !== null || $value === 0 || $value === 0.0);
    }
}
