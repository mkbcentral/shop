<?php

namespace App\Dtos\Stock;

readonly class TransferStockDto
{
    public function __construct(
        public int $from_variant_id,
        public int $to_variant_id,
        public int $quantity,
        public int $user_id,
        public ?string $reference = null,
        public ?string $reason = null,
        public ?string $date = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            from_variant_id: (int) $data['from_variant_id'],
            to_variant_id: (int) $data['to_variant_id'],
            quantity: (int) $data['quantity'],
            user_id: (int) $data['user_id'],
            reference: $data['reference'] ?? null,
            reason: $data['reason'] ?? null,
            date: $data['date'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'from_variant_id' => $this->from_variant_id,
            'to_variant_id' => $this->to_variant_id,
            'quantity' => $this->quantity,
            'user_id' => $this->user_id,
            'reference' => $this->reference,
            'reason' => $this->reason,
            'date' => $this->date,
        ], fn($value) => $value !== null);
    }
}
