<?php

namespace App\Actions\Product;

use App\Models\ProductVariant;
use App\Services\ProductService;

class CreateVariantAction
{
    public function __construct(
        private ProductService $productService
    ) {}

    /**
     * Create a new product variant.
     */
    public function execute(int $productId, array $data): ProductVariant
    {
        // Validate required fields
        if (!isset($data['sku']) && (!isset($data['size']) && !isset($data['color']))) {
            throw new \Exception("SKU or size/color combination is required");
        }

        return $this->productService->createVariant($productId, $data);
    }
}
