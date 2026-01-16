<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class VariantGeneratorService
{
    /**
     * Generate all possible variant combinations for a product
     * based on its product type variant attributes and create them
     * Returns true if variants were generated, false otherwise
     */
    public function generateVariants(Product $product, array $attributeValues = []): bool
    {
        if (!$product->productType) {
            return false;
        }

        if (!$product->productType->has_variants) {
            return false;
        }

        // Get variant attributes for this product type
        $variantAttributes = $product->productType->variantAttributes;

        if ($variantAttributes->isEmpty()) {
            return false;
        }

        // Build combinations from attribute values
        $variantOptions = [];
        foreach ($variantAttributes as $attribute) {
            $value = $attributeValues[$attribute->id] ?? null;

            if (empty($value)) {
                continue;
            }

            // If it's a select attribute, use the selected value(s)
            if ($attribute->type === 'select' && !empty($attribute->options)) {
                $variantOptions[$attribute->code] = [$value];
            } else {
                $variantOptions[$attribute->code] = [$value];
            }
        }

        // If no variant options found, return false
        if (empty($variantOptions)) {
            return false;
        }

        // Generate combinations
        $combinations = $this->generateCombinationsFromOptions($variantOptions);

        if (empty($combinations)) {
            return false;
        }

        // Create variants from combinations
        $this->createVariantsFromCombinations($product, $combinations, [
            'sku' => $product->reference . '-VAR',
            'additional_price' => 0,
            'stock_quantity' => 0,
        ]);

        // Save non-variant attributes to all variants
        $nonVariantAttributes = [];
        foreach ($attributeValues as $attrId => $value) {
            $attr = $variantAttributes->firstWhere('id', $attrId);
            if (!$attr || $attr->is_variant_attribute) {
                continue;
            }
            $nonVariantAttributes[$attrId] = $value;
        }

        if (!empty($nonVariantAttributes)) {
            foreach ($product->variants as $variant) {
                $this->saveNonVariantAttributes($variant, $nonVariantAttributes);
            }
        }

        return true;
    }

    /**
     * Save non-variant attributes to a variant
     */
    protected function saveNonVariantAttributes(ProductVariant $variant, array $attributes): void
    {
        foreach ($attributes as $attributeId => $value) {
            if (empty($value)) {
                continue;
            }

            ProductAttributeValue::updateOrCreate(
                [
                    'product_variant_id' => $variant->id,
                    'product_attribute_id' => $attributeId,
                ],
                [
                    'value' => is_array($value) ? json_encode($value) : $value,
                ]
            );
        }
    }

    /**
     * Generate all possible combinations from variant options
     */
    protected function generateCombinationsFromOptions(array $variantOptions): array
    {
        $combinations = [[]];

        foreach ($variantOptions as $code => $values) {
            $newCombinations = [];
            foreach ($combinations as $combination) {
                foreach ($values as $value) {
                    $newCombinations[] = array_merge(
                        $combination,
                        [$code => $value]
                    );
                }
            }
            $combinations = $newCombinations;
        }

        return $combinations;
    }

    /**
     * Generate all possible combinations from variant attributes
     */
    protected function generateCombinations(Collection $variantAttributes, array $attributeValues): array
    {
        $combinations = [[]];

        foreach ($variantAttributes as $attribute) {
            $values = $attributeValues[$attribute->code] ?? $attribute->options ?? [];

            if (empty($values)) {
                continue;
            }

            $newCombinations = [];
            foreach ($combinations as $combination) {
                foreach ($values as $value) {
                    $newCombinations[] = array_merge(
                        $combination,
                        [$attribute->code => $value]
                    );
                }
            }
            $combinations = $newCombinations;
        }

        return $combinations;
    }

    /**
     * Create product variants from combinations
     */
    public function createVariantsFromCombinations(
        Product $product,
        array $combinations,
        array $baseData = []
    ): Collection {
        DB::beginTransaction();

        try {
            $variants = collect();

            foreach ($combinations as $combination) {
                $variant = $this->createVariant($product, $combination, $baseData);
                $variants->push($variant);
            }

            DB::commit();

            return $variants;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Create a single variant with attribute values
     */
    protected function createVariant(Product $product, array $combination, array $baseData): ProductVariant
    {
        // Generate variant name from combination
        $variantName = $this->generateVariantName($combination);

        // Create variant
        $variantData = array_merge($baseData, [
            'product_id' => $product->id,
            'organization_id' => $product->organization_id,
            'variant_name' => $variantName,
        ]);

        // Handle legacy size/color fields for backward compatibility
        if (isset($combination['size'])) {
            $variantData['size'] = $combination['size'];
        }
        if (isset($combination['color'])) {
            $variantData['color'] = $combination['color'];
        }

        $variant = ProductVariant::create($variantData);

        // Create attribute values
        $this->createAttributeValues($variant, $combination);

        return $variant->fresh(['attributeValues']);
    }

    /**
     * Create attribute values for a variant
     */
    protected function createAttributeValues(ProductVariant $variant, array $combination): void
    {
        $product = $variant->product;
        $variantAttributes = $product->productType->variantAttributes;

        foreach ($combination as $attributeCode => $value) {
            $attribute = $variantAttributes->firstWhere('code', $attributeCode);

            if (!$attribute) {
                continue;
            }

            ProductAttributeValue::create([
                'product_attribute_id' => $attribute->id,
                'product_variant_id' => $variant->id,
                'value' => $value,
            ]);
        }
    }

    /**
     * Generate a human-readable name from a combination
     */
    protected function generateVariantName(array $combination): string
    {
        if (empty($combination)) {
            return 'Standard';
        }

        return implode(' - ', array_values($combination));
    }

    /**
     * Update variant attribute values
     */
    public function updateVariantAttributes(ProductVariant $variant, array $attributes): void
    {
        DB::beginTransaction();

        try {
            foreach ($attributes as $attributeCode => $value) {
                $product = $variant->product;
                $attribute = $product->productType->attributes()
                    ->where('code', $attributeCode)
                    ->first();

                if (!$attribute) {
                    continue;
                }

                ProductAttributeValue::updateOrCreate(
                    [
                        'product_attribute_id' => $attribute->id,
                        'product_variant_id' => $variant->id,
                    ],
                    [
                        'value' => $value,
                    ]
                );

                // Update legacy fields if needed
                if ($attributeCode === 'size') {
                    $variant->size = $value;
                }
                if ($attributeCode === 'color') {
                    $variant->color = $value;
                }
            }

            // Regenerate variant name
            $newName = $this->generateVariantName($attributes);
            $variant->variant_name = $newName;
            $variant->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get variant attributes as an array
     */
    public function getVariantAttributes(ProductVariant $variant): array
    {
        $attributes = [];

        foreach ($variant->attributeValues as $attributeValue) {
            $attribute = $attributeValue->productAttribute;
            $attributes[$attribute->code] = $attributeValue->value;
        }

        // Include legacy fields if no attribute values exist
        if (empty($attributes)) {
            if ($variant->size) {
                $attributes['size'] = $variant->size;
            }
            if ($variant->color) {
                $attributes['color'] = $variant->color;
            }
        }

        return $attributes;
    }
}
