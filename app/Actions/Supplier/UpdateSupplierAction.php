<?php

namespace App\Actions\Supplier;

use App\Models\Supplier;
use App\Services\SupplierService;

class UpdateSupplierAction
{
    public function __construct(
        private SupplierService $supplierService
    ) {}

    /**
     * Update a supplier.
     */
    public function execute(int $supplierId, array $data): Supplier
    {
        return $this->supplierService->updateSupplier($supplierId, $data);
    }
}
