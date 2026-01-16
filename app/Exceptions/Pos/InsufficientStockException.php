<?php

declare(strict_types=1);

namespace App\Exceptions\Pos;

use Exception;

class InsufficientStockException extends Exception
{
    public function __construct(
        private readonly string $productName,
        private readonly int $requestedQuantity,
        private readonly int $availableStock
    ) {
        parent::__construct(
            sprintf(
                'Stock insuffisant pour %s. Demandé: %d, Disponible: %d',
                $productName,
                $requestedQuantity,
                $availableStock
            )
        );
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function getRequestedQuantity(): int
    {
        return $this->requestedQuantity;
    }

    public function getAvailableStock(): int
    {
        return $this->availableStock;
    }
}
