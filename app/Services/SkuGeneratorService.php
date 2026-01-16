<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;

class SkuGeneratorService
{
    /**
     * Generate SKU for a variant.
     */
    public function generateForVariant(Product $product, array $variantData): string
    {
        $sku = strtoupper($product->reference);

        if (!empty($variantData['color'])) {
            $sku .= '-' . strtoupper(substr($variantData['color'], 0, 3));
        }

        if (!empty($variantData['size'])) {
            $sku .= '-' . strtoupper($variantData['size']);
        }

        // Ensure uniqueness by adding a counter if needed
        return $this->ensureUniqueness($sku);
    }

    /**
     * Generate default SKU for a product without variants.
     */
    public function generateDefault(Product $product): string
    {
        return $this->ensureUniqueness(strtoupper($product->reference) . '-DEF');
    }

    /**
     * Ensure SKU uniqueness by adding a counter if needed.
     */
    private function ensureUniqueness(string $baseSku): string
    {
        $sku = $baseSku;
        $counter = 1;

        while (ProductVariant::where('sku', $sku)->exists()) {
            $sku = $baseSku . '-' . $counter;
            $counter++;
        }

        return $sku;
    }

    /**
     * Validate SKU format.
     */
    public function validateFormat(string $sku): bool
    {
        // SKU format should start with reference format
        return (bool) preg_match('/^[A-Z0-9\-]+$/', $sku);
    }

    /**
     * Check if a SKU is unique.
     */
    public function isUnique(string $sku, ?int $excludeVariantId = null): bool
    {
        $query = ProductVariant::where('sku', $sku);
        
        if ($excludeVariantId) {
            $query->where('id', '!=', $excludeVariantId);
        }
        
        return !$query->exists();
    }
}
