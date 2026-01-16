<?php

namespace App\Actions\Product;

use App\Models\ProductVariant;
use App\Services\ProductService;

class UpdateVariantAction
{
    public function __construct(
        private ProductService $productService
    ) {}

    /**
     * Update a product variant.
     */
    public function execute(int $variantId, array $data): ProductVariant
    {
        return $this->productService->updateVariant($variantId, $data);
    }
}
