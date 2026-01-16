<?php

namespace App\Dtos\Product;

readonly class UpdateProductDto
{
    public function __construct(
        public ?int $category_id = null,
        public ?string $name = null,
        public ?string $reference = null,
        public ?string $barcode = null,
        public ?float $price = null,
        public ?float $cost_price = null,
        public ?string $description = null,
        public ?string $image = null,
        public ?string $status = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            category_id: isset($data['category_id']) ? (int) $data['category_id'] : null,
            name: $data['name'] ?? null,
            reference: $data['reference'] ?? null,
            barcode: $data['barcode'] ?? null,
            price: isset($data['price']) ? (float) $data['price'] : null,
            cost_price: isset($data['cost_price']) ? (float) $data['cost_price'] : null,
            description: $data['description'] ?? null,
            image: $data['image'] ?? null,
            status: $data['status'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'category_id' => $this->category_id,
            'name' => $this->name,
            'reference' => $this->reference,
            'barcode' => $this->barcode,
            'price' => $this->price,
            'cost_price' => $this->cost_price,
            'description' => $this->description,
            'image' => $this->image,
            'status' => $this->status,
        ], fn($value) => $value !== null);
    }
}
