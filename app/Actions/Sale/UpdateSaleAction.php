<?php

namespace App\Actions\Sale;

use App\Dtos\Sale\UpdateSaleDto;
use App\Models\Sale;
use App\Services\Sale\SaleUpdateService;

/**
 * Action to update a sale
 * 
 * Validates update data and delegates to SaleUpdateService
 */
class UpdateSaleAction
{
    public function __construct(
        private SaleUpdateService $updateService
    ) {}

    /**
     * Update a sale
     * 
     * @param int $saleId Sale to update
     * @param array $data Update data
     * @return Sale The updated sale
     */
    public function execute(int $saleId, array $data): Sale
    {
        // Validate using DTO if it exists
        if (class_exists(UpdateSaleDto::class)) {
            $dto = UpdateSaleDto::fromArray(array_merge($data, ['id' => $saleId]));
            $data = $dto->toArray();
        }

        // Delegate to specialized service
        return $this->updateService->updateSale($saleId, $data);
    }

    /**
     * Cancel a sale
     */
    public function cancel(int $saleId, ?string $reason = null): Sale
    {
        return $this->updateService->cancelSale($saleId, $reason);
    }

    /**
     * Update discount
     */
    public function updateDiscount(int $saleId, float $discount): Sale
    {
        return $this->updateService->updateDiscount($saleId, $discount);
    }

    /**
     * Update tax
     */
    public function updateTax(int $saleId, float $tax): Sale
    {
        return $this->updateService->updateTax($saleId, $tax);
    }
}

