<?php

namespace App\Actions\Product;

use App\Services\ProductService;

class DeleteVariantAction
{
    public function __construct(
        private ProductService $productService
    ) {}

    /**
     * Delete a product variant.
     */
    public function execute(int $variantId): bool
    {
        return $this->productService->deleteVariant($variantId);
    }
}
