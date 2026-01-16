<?php

namespace App\Dtos\Sale;

readonly class RefundSaleDto
{
    public function __construct(
        public string $reason,
        public bool $restore_stock = true,
        public ?string $refund_method = null,
        public ?float $refund_amount = null,
        public ?string $notes = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            reason: $data['reason'],
            restore_stock: (bool) ($data['restore_stock'] ?? true),
            refund_method: $data['refund_method'] ?? null,
            refund_amount: isset($data['refund_amount']) ? (float) $data['refund_amount'] : null,
            notes: $data['notes'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'reason' => $this->reason,
            'restore_stock' => $this->restore_stock,
            'refund_method' => $this->refund_method,
            'refund_amount' => $this->refund_amount,
            'notes' => $this->notes,
        ], fn($value) => $value !== null);
    }
}
