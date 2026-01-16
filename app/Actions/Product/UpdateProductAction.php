<?php

namespace App\Actions\Product;

use App\Models\Product;
use App\Services\ProductService;

class UpdateProductAction
{
    public function __construct(
        private ProductService $productService
    ) {}

    /**
     * Update a product.
     */
    public function execute(int $productId, array $data): Product
    {
        return $this->productService->updateProduct($productId, $data);
    }
}
