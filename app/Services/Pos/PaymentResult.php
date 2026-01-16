<?php

declare(strict_types=1);

namespace App\Services\Pos;

use App\Models\Sale;
use App\Models\Invoice;

/**
 * Résultat d'un traitement de paiement
 */
class PaymentResult
{
    public function __construct(
        public readonly bool $success,
        public readonly ?Sale $sale = null,
        public readonly ?Invoice $invoice = null,
        public readonly float $change = 0,
        public readonly ?string $error = null
    ) {}

    /**
     * Crée un résultat de succès
     */
    public static function success(Sale $sale, Invoice $invoice, float $change): self
    {
        return new self(
            success: true,
            sale: $sale,
            invoice: $invoice,
            change: $change
        );
    }

    /**
     * Crée un résultat d'échec
     */
    public static function failure(string $error): self
    {
        return new self(
            success: false,
            error: $error
        );
    }

    /**
     * Vérifie si le paiement a réussi
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * Vérifie si le paiement a échoué
     */
    public function isFailure(): bool
    {
        return !$this->success;
    }
}
