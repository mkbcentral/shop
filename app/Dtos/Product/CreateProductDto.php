<?php

namespace App\Dtos\Product;

readonly class CreateProductDto
{
    public function __construct(
        public int $category_id,
        public string $name,
        public string $reference,
        public float $price,
        public float $cost_price,
        public ?string $description = null,
        public ?string $barcode = null,
        public ?string $image = null,
        public string $status = 'active',
        public ?array $variants = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            category_id: (int) $data['category_id'],
            name: $data['name'],
            reference: $data['reference'],
            price: (float) $data['price'],
            cost_price: (float) $data['cost_price'],
            description: $data['description'] ?? null,
            barcode: $data['barcode'] ?? null,
            image: $data['image'] ?? null,
            status: $data['status'] ?? 'active',
            variants: $data['variants'] ?? null,
        );
    }

    public function toArray(): array
    {
        $array = [
            'category_id' => $this->category_id,
            'name' => $this->name,
            'reference' => $this->reference,
            'price' => $this->price,
            'cost_price' => $this->cost_price,
            'status' => $this->status,
        ];

        if ($this->description !== null) {
            $array['description'] = $this->description;
        }

        if ($this->barcode !== null) {
            $array['barcode'] = $this->barcode;
        }

        if ($this->image !== null) {
            $array['image'] = $this->image;
        }

        if ($this->variants !== null) {
            $array['variants'] = $this->variants;
        }

        return $array;
    }
}
