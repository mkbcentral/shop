<?php

namespace App\Dtos\Sale;

readonly class CreateSaleDto
{
    public function __construct(
        public int $user_id,
        public string $payment_method,
        public array $items,
        public ?int $client_id = null,
        public ?string $sale_date = null,
        public float $discount = 0.0,
        public float $tax = 0.0,
        public string $payment_status = 'pending',
        public string $status = 'pending',
        public ?string $notes = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            user_id: (int) $data['user_id'],
            payment_method: $data['payment_method'],
            items: $data['items'],
            client_id: isset($data['client_id']) ? (int) $data['client_id'] : null,
            sale_date: $data['sale_date'] ?? null,
            discount: (float) ($data['discount'] ?? 0.0),
            tax: (float) ($data['tax'] ?? 0.0),
            payment_status: $data['payment_status'] ?? 'pending',
            status: $data['status'] ?? 'pending',
            notes: $data['notes'] ?? null,
        );
    }

    public static function fromRequest($request): self
    {
        $data = $request->validated();
        $data['user_id'] = $data['user_id'] ?? auth()->id();
        
        return self::fromArray($data);
    }

    public function toArray(): array
    {
        $array = [
            'user_id' => $this->user_id,
            'payment_method' => $this->payment_method,
            'items' => $this->items,
            'discount' => $this->discount,
            'tax' => $this->tax,
            'payment_status' => $this->payment_status,
            'status' => $this->status,
        ];

        if ($this->client_id !== null) {
            $array['client_id'] = $this->client_id;
        }

        if ($this->sale_date !== null) {
            $array['sale_date'] = $this->sale_date;
        }

        if ($this->notes !== null) {
            $array['notes'] = $this->notes;
        }

        return $array;
    }
}
