<?php

declare(strict_types=1);

namespace App\Services\Pos;

/**
 * DTO pour les données de paiement
 */
class PaymentData
{
    public function __construct(
        public readonly int $userId,
        public readonly ?int $clientId,
        public readonly ?int $storeId,
        public readonly string $paymentMethod,
        public readonly array $items,
        public readonly float $discount,
        public readonly float $tax,
        public readonly float $paidAmount,
        public readonly float $total,
        public readonly string $notes,
        public readonly array $stockValidation
    ) {}

    /**
     * Crée une instance depuis un composant Livewire
     */
    public static function fromComponent(object $component, array $items, array $stockValidation): self
    {
        /** @var int $userId */
        $userId = auth()->id() ?? throw new \RuntimeException('Utilisateur non authentifié');

        return new self(
            userId: $userId,
            clientId: $component->clientId,
            storeId: current_store_id(),
            paymentMethod: $component->paymentMethod,
            items: $items,
            discount: (float) $component->discount,
            tax: (float) $component->tax,
            paidAmount: (float) $component->paidAmount,
            total: (float) $component->total,
            notes: $component->notes ?? '',
            stockValidation: $stockValidation
        );
    }
}
