<?php

declare(strict_types=1);

namespace App\Exceptions\Pos;

use Exception;

class CartEmptyException extends Exception
{
    public function __construct()
    {
        parent::__construct('Le panier est vide. Veuillez ajouter des articles avant de procéder au paiement.');
    }
}
