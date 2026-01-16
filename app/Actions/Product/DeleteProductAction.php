<?php

namespace App\Actions\Product;

use App\Services\ProductService;

class DeleteProductAction
{
    public function __construct(
        private ProductService $productService
    ) {}

    /**
     * Delete a product.
     */
    public function execute(int $productId): bool
    {
        return $this->productService->deleteProduct($productId);
    }
}
