<?php

namespace App\Dtos\Sale;

readonly class UpdateSaleDto
{
    public function __construct(
        public ?int $client_id = null,
        public ?string $payment_method = null,
        public ?string $payment_status = null,
        public ?string $status = null,
        public ?float $discount = null,
        public ?float $tax = null,
        public ?string $notes = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            client_id: isset($data['client_id']) ? (int) $data['client_id'] : null,
            payment_method: $data['payment_method'] ?? null,
            payment_status: $data['payment_status'] ?? null,
            status: $data['status'] ?? null,
            discount: isset($data['discount']) ? (float) $data['discount'] : null,
            tax: isset($data['tax']) ? (float) $data['tax'] : null,
            notes: $data['notes'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'client_id' => $this->client_id,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'status' => $this->status,
            'discount' => $this->discount,
            'tax' => $this->tax,
            'notes' => $this->notes,
        ], fn($value) => $value !== null);
    }
}
