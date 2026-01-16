<?php

namespace App\Actions\Sale;

use App\Models\Sale;
use App\Services\SaleService;

class UpdateSaleAction
{
    public function __construct(
        private SaleService $saleService
    ) {}

    /**
     * Update a sale.
     */
    public function execute(int $saleId, array $data): Sale
    {
        return $this->saleService->updateSale($saleId, $data);
    }
}
