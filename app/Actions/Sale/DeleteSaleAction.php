<?php

namespace App\Actions\Sale;

use App\Services\SaleService;

class DeleteSaleAction
{
    public function __construct(
        private SaleService $saleService
    ) {}

    /**
     * Delete a sale (cancel if not completed, or use refund for completed sales).
     */
    public function execute(int $saleId, string $reason = null): bool
    {
        $this->saleService->cancelSale($saleId, $reason ?? "Sale deleted");
        return true;
    }
}
