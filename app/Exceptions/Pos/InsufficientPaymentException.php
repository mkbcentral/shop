<?php

declare(strict_types=1);

namespace App\Exceptions\Pos;

use Exception;

class InsufficientPaymentException extends Exception
{
    public function __construct(
        private readonly float $total,
        private readonly float $paidAmount
    ) {
        $missing = $total - $paidAmount;
        parent::__construct(
            sprintf(
                'Montant payé insuffisant. Manque: %s CDF',
                number_format($missing, 2)
            )
        );
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function getPaidAmount(): float
    {
        return $this->paidAmount;
    }

    public function getMissingAmount(): float
    {
        return $this->total - $this->paidAmount;
    }
}
