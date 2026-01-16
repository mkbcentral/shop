<?php

namespace App\Actions\ProductType;

use App\Services\ProductTypeService;

class DeleteProductTypeAction
{
    public function __construct(
        protected ProductTypeService $productTypeService
    ) {}

    /**
     * Execute the action to delete a product type
     */
    public function execute(int $id): bool
    {
        return $this->productTypeService->deleteProductType($id);
    }
}
