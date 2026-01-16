<?php

namespace App\Exceptions\Store;

use Exception;

class StoreNotFoundException extends Exception
{
    public function __construct(int $storeId)
    {
        parent::__construct("Le magasin avec l'ID {$storeId} est introuvable.");
    }
}
