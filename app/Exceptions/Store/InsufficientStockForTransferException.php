<?php

namespace App\Exceptions\Store;

use Exception;

class InsufficientStockForTransferException extends Exception
{
    public function __construct(string $productName, int $requested, int $available, string $storeName)
    {
        parent::__construct(
            "Stock insuffisant pour {$productName} dans le magasin {$storeName}. " .
            "Demandé: {$requested}, Disponible: {$available}"
        );
    }
}
