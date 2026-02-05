<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Services\QRCodeGeneratorService;
use App\Traits\ResolvesStoreContext;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Service responsible for creating new products
 * Handles validation, slug generation, and product creation
 */
class ProductCreationService
{
    use ResolvesStoreContext;

    public function __construct(
        private ProductRepository $productRepository,
        private QRCodeGeneratorService $qrCodeGenerator,
        private ProductVariantService $variantService
    ) {}

    /**
     * Create a new product with its initial configuration
     * 
     * @param array $data Product data
     * @return Product Created product with variants
     * @throws \Exception If validation fails or product cannot be created
     */
    public function createProduct(array $data): Product
    {
        // Vérifier la limite de produits du plan
        $this->checkProductLimit();

        // Clean and normalize data
        $data = $this->normalizeProductData($data);

        // Validate uniqueness
        $this->validateUniqueness($data);

        // Ensure store_id is set
        if (!isset($data['store_id']) || empty($data['store_id'])) {
            $data['store_id'] = $this->resolveStoreId();
        }

        // Generate slug if not provided
        if (!isset($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['name']);
        }

        // Generate QR code if not provided
        if (!isset($data['qr_code']) || empty($data['qr_code'])) {
            $data['qr_code'] = $this->qrCodeGenerator->generateForProduct();
        }

        // Extract related data
        $variants = $data['variants'] ?? [];
        $attributes = $data['attributes'] ?? [];
        unset($data['variants'], $data['attributes']);

        DB::beginTransaction();
        try {
            // Create the product
            $product = $this->productRepository->create($data);

            // Handle variants creation
            $this->variantService->handleProductVariants(
                $product,
                $variants,
                $attributes,
                $data['product_type_id'] ?? null
            );

            DB::commit();
            return $product->load('variants', 'productType.attributes');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Clean and normalize product data
     */
    private function normalizeProductData(array $data): array
    {
        // Clean empty strings to null for nullable integer fields
        $nullableFields = ['product_type_id', 'category_id', 'cost_price'];
        foreach ($nullableFields as $field) {
            if (isset($data[$field]) && $data[$field] === '') {
                $data[$field] = null;
            }
        }

        return $data;
    }

    /**
     * Validate that reference and barcode are unique globally
     */
    private function validateUniqueness(array $data): void
    {
        // Check reference uniqueness
        if (isset($data['reference'])) {
            $referenceExists = Product::withoutGlobalScope('organization')
                ->where('reference', $data['reference'])
                ->exists();
            if ($referenceExists) {
                throw new \Exception(
                    "La référence {$data['reference']} existe déjà. Veuillez en choisir une autre."
                );
            }
        }

        // Check barcode uniqueness
        if (isset($data['barcode']) && !empty($data['barcode'])) {
            $barcodeExists = Product::withoutGlobalScope('organization')
                ->where('barcode', $data['barcode'])
                ->exists();
            if ($barcodeExists) {
                throw new \Exception(
                    "Le code-barres {$data['barcode']} existe déjà. Veuillez en générer un autre."
                );
            }
        }
    }

    /**
     * Generate a unique slug for a product globally
     */
    private function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $query = Product::withoutGlobalScope('organization')
                ->where('slug', $slug);

            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            if (!$query->exists()) {
                break;
            }

            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Check if the current plan allows creating more products
     */
    private function checkProductLimit(): void
    {
        // Get current organization
        $organization = app()->bound('current_organization') 
            ? app('current_organization') 
            : null;

        if (!$organization) {
            return; // No organization context, allow creation
        }

        // Check product limit based on subscription plan
        $planLimitService = app(\App\Services\PlanLimitService::class);
        
        if (!$planLimitService->canAddProduct($organization)) {
            $usage = $organization->getProductsUsage();
            throw new \Exception(
                $planLimitService->getLimitReachedMessage('products', $usage['current'], $usage['max'])
            );
        }
    }
}
