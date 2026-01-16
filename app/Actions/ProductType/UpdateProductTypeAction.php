<?php

namespace App\Actions\ProductType;

use App\Services\ProductTypeService;
use App\Models\ProductType;

class UpdateProductTypeAction
{
    public function __construct(
        protected ProductTypeService $productTypeService
    ) {}

    /**
     * Execute the action to update a product type
     */
    public function execute(int $id, array $data): ProductType
    {
        // Validate input
        $this->validate($data);

        return $this->productTypeService->updateProductType($id, $data);
    }

    /**
     * Validate the input data
     */
    protected function validate(array $data): void
    {
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
    }
}
