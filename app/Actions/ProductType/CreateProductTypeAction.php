<?php

namespace App\Actions\ProductType;

use App\Services\ProductTypeService;
use App\Models\ProductType;

class CreateProductTypeAction
{
    public function __construct(
        protected ProductTypeService $productTypeService
    ) {}

    /**
     * Execute the action to create a product type
     */
    public function execute(array $data): ProductType
    {
        // Validate required fields
        $this->validate($data);

        return $this->productTypeService->createProductType($data);
    }

    /**
     * Validate the input data
     */
    protected function validate(array $data): void
    {
        if (empty($data['name'])) {
            throw new \InvalidArgumentException("Product type name is required");
        }

        // Validate boolean fields
        $booleanFields = [
            'has_variants',
            'has_expiry_date',
            'has_weight',
            'has_dimensions',
            'has_serial_number',
            'is_active'
        ];

        foreach ($booleanFields as $field) {
            if (isset($data[$field]) && !is_bool($data[$field])) {
                $data[$field] = filter_var($data[$field], FILTER_VALIDATE_BOOLEAN);
            }
        }

        // Validate attributes if provided
        if (isset($data['attributes']) && !is_array($data['attributes'])) {
            throw new \InvalidArgumentException("Attributes must be an array");
        }
    }
}
