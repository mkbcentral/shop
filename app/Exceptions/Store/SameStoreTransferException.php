<?php

namespace App\Exceptions\Store;

use Exception;

class SameStoreTransferException extends Exception
{
    public function __construct()
    {
        parent::__construct("Impossible de transférer des produits vers le même magasin.");
    }
}
