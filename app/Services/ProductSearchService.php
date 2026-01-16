<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ProductSearchService
{
    /**
     * Search products by variant attributes
     *
     * @param array $filters ['attribute_code' => 'value', ...]
     * @param array $options Additional search options
     * @return Collection
     */
    public function searchByVariantAttributes(array $filters, array $options = []): Collection
    {
        $query = Product::query()
            ->with(['productType', 'category', 'variants.attributeValues.productAttribute']);

        // Filter by product type if specified
        if (!empty($options['product_type_id'])) {
            $query->where('product_type_id', $options['product_type_id']);
        }

        // Filter by category if specified
        if (!empty($options['category_id'])) {
            $query->where('category_id', $options['category_id']);
        }

        // Filter by store if specified
        if (!empty($options['store_id'])) {
            $query->where('store_id', $options['store_id']);
        }

        // Filter by variant attributes
        if (!empty($filters)) {
            $query->whereHas('variants', function(Builder $variantsQuery) use ($filters) {
                $variantsQuery->where(function(Builder $q) use ($filters) {
                    foreach ($filters as $attributeCode => $value) {
                        $q->whereHas('attributeValues', function(Builder $attrQuery) use ($attributeCode, $value) {
                            $attrQuery->whereHas('productAttribute', function(Builder $attrDefQuery) use ($attributeCode) {
                                $attrDefQuery->where('code', $attributeCode);
                            })->where('value', $value);
                        });
                    }
                });

                // Only variants with stock if specified
                if (!empty($options['in_stock_only'])) {
                    $variantsQuery->where('stock_quantity', '>', 0);
                }
            });
        }

        // Filter by brand
        if (!empty($options['brand'])) {
            $query->where('brand', 'like', '%' . $options['brand'] . '%');
        }

        // Filter by price range
        if (!empty($options['min_price'])) {
            $query->where('price', '>=', $options['min_price']);
        }
        if (!empty($options['max_price'])) {
            $query->where('price', '<=', $options['max_price']);
        }

        // Filter by status
        if (!empty($options['status'])) {
            $query->where('status', $options['status']);
        } else {
            // By default, only active products
            $query->where('status', 'active');
        }

        // Order by
        $orderBy = $options['order_by'] ?? 'name';
        $orderDirection = $options['order_direction'] ?? 'asc';
        $query->orderBy($orderBy, $orderDirection);

        // Limit results
        $limit = $options['limit'] ?? 100;
        $query->limit($limit);

        return $query->get();
    }

    /**
     * Search products with specific variant options available
     * Returns products that have at least one variant matching the criteria
     *
     * @param array $filters ['attribute_code' => ['value1', 'value2'], ...]
     * @param array $options
     * @return Collection
     */
    public function searchByAvailableVariantOptions(array $filters, array $options = []): Collection
    {
        $query = Product::query()
            ->with(['productType', 'category', 'variants.attributeValues.productAttribute']);

        // Filter by product type if specified
        if (!empty($options['product_type_id'])) {
            $query->where('product_type_id', $options['product_type_id']);
        }

        // Filter by variant attributes (OR logic for each attribute)
        if (!empty($filters)) {
            foreach ($filters as $attributeCode => $values) {
                if (!is_array($values)) {
                    $values = [$values];
                }

                $query->whereHas('variants', function(Builder $variantsQuery) use ($attributeCode, $values, $options) {
                    $variantsQuery->whereHas('attributeValues', function(Builder $attrQuery) use ($attributeCode, $values) {
                        $attrQuery->whereHas('productAttribute', function(Builder $attrDefQuery) use ($attributeCode) {
                            $attrDefQuery->where('code', $attributeCode);
                        })->whereIn('value', $values);
                    });

                    // Only variants with stock if specified
                    if (!empty($options['in_stock_only'])) {
                        $variantsQuery->where('stock_quantity', '>', 0);
                    }
                });
            }
        }

        // Apply additional filters
        $this->applyCommonFilters($query, $options);

        return $query->get();
    }

    /**
     * Get available filter options for a product type
     * Returns all unique values for each variant attribute
     *
     * @param int|null $productTypeId
     * @param array $options
     * @return array
     */
    public function getAvailableFilterOptions(?int $productTypeId = null, array $options = []): array
    {
        $query = Product::query()->with(['productType.variantAttributes', 'variants.attributeValues']);

        if ($productTypeId) {
            $query->where('product_type_id', $productTypeId);
        }

        // Apply common filters
        if (!empty($options['category_id'])) {
            $query->where('category_id', $options['category_id']);
        }

        if (!empty($options['in_stock_only'])) {
            $query->whereHas('variants', function($q) {
                $q->where('stock_quantity', '>', 0);
            });
        }

        $products = $query->get();

        $filterOptions = [];

        foreach ($products as $product) {
            if (!$product->productType) {
                continue;
            }

            foreach ($product->productType->variantAttributes as $attribute) {
                if (!isset($filterOptions[$attribute->code])) {
                    $filterOptions[$attribute->code] = [
                        'name' => $attribute->name,
                        'code' => $attribute->code,
                        'type' => $attribute->type,
                        'values' => [],
                    ];
                }

                foreach ($product->variants as $variant) {
                    foreach ($variant->attributeValues as $attrValue) {
                        if ($attrValue->product_attribute_id === $attribute->id) {
                            $value = $attrValue->value;

                            if (!in_array($value, $filterOptions[$attribute->code]['values'])) {
                                $filterOptions[$attribute->code]['values'][] = $value;
                            }
                        }
                    }
                }
            }
        }

        // Sort values for each attribute
        foreach ($filterOptions as $code => &$options) {
            sort($options['values']);
        }

        return $filterOptions;
    }

    /**
     * Quick search by text (name, reference, brand, barcode)
     *
     * @param string $searchTerm
     * @param array $options
     * @return Collection
     */
    public function quickSearch(string $searchTerm, array $options = []): Collection
    {
        $query = Product::query()
            ->with(['productType', 'category', 'variants']);

        $query->where(function(Builder $q) use ($searchTerm) {
            $q->where('name', 'like', '%' . $searchTerm . '%')
              ->orWhere('reference', 'like', '%' . $searchTerm . '%')
              ->orWhere('brand', 'like', '%' . $searchTerm . '%')
              ->orWhere('barcode', 'like', '%' . $searchTerm . '%')
              ->orWhereHas('variants', function(Builder $variantQuery) use ($searchTerm) {
                  $variantQuery->where('sku', 'like', '%' . $searchTerm . '%')
                               ->orWhere('barcode', 'like', '%' . $searchTerm . '%');
              });
        });

        $this->applyCommonFilters($query, $options);

        return $query->get();
    }

    /**
     * Find matching variants based on criteria
     *
     * @param int $productId
     * @param array $attributeFilters ['attribute_code' => 'value']
     * @return Collection
     */
    public function findMatchingVariants(int $productId, array $attributeFilters): Collection
    {
        $product = Product::with(['variants.attributeValues.productAttribute'])->find($productId);

        if (!$product) {
            return collect();
        }

        return $product->variants->filter(function($variant) use ($attributeFilters) {
            foreach ($attributeFilters as $code => $value) {
                $attrValue = $variant->attributeValues->first(function($av) use ($code) {
                    return $av->productAttribute->code === $code;
                });

                if (!$attrValue || $attrValue->value !== $value) {
                    return false;
                }
            }
            return true;
        });
    }

    /**
     * Get products by brand with variant stats
     *
     * @param string $brand
     * @param array $options
     * @return Collection
     */
    public function getProductsByBrand(string $brand, array $options = []): Collection
    {
        $query = Product::query()
            ->where('brand', 'like', '%' . $brand . '%')
            ->with(['productType', 'variants' => function($q) use ($options) {
                if (!empty($options['in_stock_only'])) {
                    $q->where('stock_quantity', '>', 0);
                }
            }]);

        $this->applyCommonFilters($query, $options);

        return $query->get()->map(function($product) {
            $product->total_variants = $product->variants->count();
            $product->in_stock_variants = $product->variants->where('stock_quantity', '>', 0)->count();
            $product->total_stock = $product->variants->sum('stock_quantity');
            return $product;
        });
    }

    /**
     * Apply common filters to a query
     *
     * @param Builder $query
     * @param array $options
     */
    protected function applyCommonFilters(Builder $query, array $options): void
    {
        if (!empty($options['category_id'])) {
            $query->where('category_id', $options['category_id']);
        }

        if (!empty($options['store_id'])) {
            $query->where('store_id', $options['store_id']);
        }

        if (!empty($options['min_price'])) {
            $query->where('price', '>=', $options['min_price']);
        }

        if (!empty($options['max_price'])) {
            $query->where('price', '<=', $options['max_price']);
        }

        if (!empty($options['status'])) {
            $query->where('status', $options['status']);
        } else {
            $query->where('status', 'active');
        }

        $orderBy = $options['order_by'] ?? 'name';
        $orderDirection = $options['order_direction'] ?? 'asc';
        $query->orderBy($orderBy, $orderDirection);

        if (!empty($options['limit'])) {
            $query->limit($options['limit']);
        }
    }

    /**
     * Get popular variants (most sold)
     *
     * @param array $options
     * @return Collection
     */
    public function getPopularVariants(array $options = []): Collection
    {
        $query = Product::query()
            ->with(['productType', 'variants.attributeValues.productAttribute', 'variants.saleItems'])
            ->whereHas('variants.saleItems');

        $this->applyCommonFilters($query, $options);

        $products = $query->get();

        $variantsWithSales = [];

        foreach ($products as $product) {
            foreach ($product->variants as $variant) {
                $totalSold = $variant->saleItems->sum('quantity');

                if ($totalSold > 0) {
                    $variantsWithSales[] = [
                        'product' => $product,
                        'variant' => $variant,
                        'total_sold' => $totalSold,
                        'variant_details' => $variant->getFormattedAttributes(),
                    ];
                }
            }
        }

        // Sort by total sold
        usort($variantsWithSales, function($a, $b) {
            return $b['total_sold'] <=> $a['total_sold'];
        });

        $limit = $options['limit'] ?? 20;
        return collect(array_slice($variantsWithSales, 0, $limit));
    }
}
