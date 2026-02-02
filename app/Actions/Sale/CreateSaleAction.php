<?php

namespace App\Actions\Sale;

use App\Dtos\Sale\CreateSaleDto;
use App\Models\Sale;
use App\Services\Sale\SaleCreationService;

/**
 * Action to create a new sale
 * 
 * Validates sale data via DTO and delegates to SaleCreationService
 */
class CreateSaleAction
{
    public function __construct(
        private SaleCreationService $creationService
    ) {}

    /**
     * Create a new sale with validation
     * 
     * @param array $data Sale data
     * @return Sale The created sale
     */
    public function execute(array $data): Sale
    {
        // Create and validate DTO (validation happens in constructor)
        $dto = CreateSaleDto::fromArray($data);

        // Delegate to specialized service
        return $this->creationService->createSale($dto->toArray());
    }

    /**
     * Execute from validated request
     */
    public function executeFromRequest($request): Sale
    {
        $dto = CreateSaleDto::fromRequest($request);
        
        return $this->creationService->createSale($dto->toArray());
    }
}

