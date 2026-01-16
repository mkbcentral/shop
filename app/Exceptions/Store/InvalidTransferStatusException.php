<?php

namespace App\Exceptions\Store;

use Exception;

class InvalidTransferStatusException extends Exception
{
    public function __construct(string $action, string $currentStatus)
    {
        parent::__construct(
            "Impossible de {$action} le transfert. Statut actuel: {$currentStatus}"
        );
    }
}
