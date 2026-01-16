<?php

namespace App\Actions\Supplier;

use App\Models\Supplier;
use App\Services\SupplierService;

class CreateSupplierAction
{
    public function __construct(
        private SupplierService $supplierService
    ) {}

    /**
     * Create a new supplier.
     */
    public function execute(array $data): Supplier
    {
        return $this->supplierService->createSupplier($data);
    }
}
