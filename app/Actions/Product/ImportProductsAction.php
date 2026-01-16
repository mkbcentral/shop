<?php

namespace App\Actions\Product;

use App\Services\ProductService;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\DB;

class ImportProductsAction
{
    public function __construct(
        private ProductService $productService,
        private CategoryRepository $categoryRepository
    ) {}

    /**
     * Import multiple products with variants from array.
     */
    public function execute(array $products): array
    {
        return DB::transaction(function () use ($products) {
            $results = [
                'success' => [],
                'failed' => [],
            ];

            foreach ($products as $productData) {
                try {
                    // Validate category
                    if (isset($productData['category_id'])) {
                        $category = $this->categoryRepository->find($productData['category_id']);
                        if (!$category) {
                            throw new \Exception("Category not found");
                        }
                    }

                    // Create product
                    $product = $this->productService->createProduct($productData);

                    $results['success'][] = [
                        'product_id' => $product->id,
                        'reference' => $product->reference,
                        'name' => $product->name,
                        'variants_created' => $product->variants->count(),
                    ];

                } catch (\Exception $e) {
                    $results['failed'][] = [
                        'data' => $productData,
                        'error' => $e->getMessage(),
                    ];
                }
            }

            return $results;
        });
    }

    /**
     * Import products from CSV file.
     */
    public function fromCsv(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new \Exception("CSV file not found");
        }

        $products = [];
        $handle = fopen($filePath, 'r');
        $headers = fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {
            $productData = array_combine($headers, $row);

            // Parse variants if provided as JSON string
            if (isset($productData['variants'])) {
                $productData['variants'] = json_decode($productData['variants'], true);
            }

            $products[] = $productData;
        }

        fclose($handle);

        return $this->execute($products);
    }
}
