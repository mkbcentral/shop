<?php

namespace App\Dtos\Store;

class CreateTransferDto
{
    public function __construct(
        public readonly int $fromStoreId,
        public readonly int $toStoreId,
        public readonly array $items,
        public readonly ?string $expectedArrivalDate,
        public readonly ?string $notes,
        public readonly int $requestedBy,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            fromStoreId: $data['from_store_id'],
            toStoreId: $data['to_store_id'],
            items: $data['items'],
            expectedArrivalDate: $data['expected_arrival_date'] ?? null,
            notes: $data['notes'] ?? null,
            requestedBy: $data['requested_by'],
        );
    }

    public function toArray(): array
    {
        return [
            'from_store_id' => $this->fromStoreId,
            'to_store_id' => $this->toStoreId,
            'items' => $this->items,
            'expected_arrival_date' => $this->expectedArrivalDate,
            'notes' => $this->notes,
            'requested_by' => $this->requestedBy,
        ];
    }
}
