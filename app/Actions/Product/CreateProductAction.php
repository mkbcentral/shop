<?php

namespace App\Actions\Product;

use App\Models\Product;
use App\Services\ProductService;
use App\Repositories\CategoryRepository;

class CreateProductAction
{
    public function __construct(
        private ProductService $productService,
        private CategoryRepository $categoryRepository
    ) {}

    /**
     * Create a new product with variants.
     */
    public function execute(array $data): Product
    {
        // Validate category exists
        if (isset($data['category_id'])) {
            $category = $this->categoryRepository->find($data['category_id']);
            if (!$category) {
                throw new \Exception("Category not found");
            }
        }

        // Validate required fields
        if (!isset($data['name']) || !isset($data['reference']) || !isset($data['price'])) {
            throw new \Exception("Missing required fields: name, reference, and price are required");
        }

        return $this->productService->createProduct($data);
    }
}
