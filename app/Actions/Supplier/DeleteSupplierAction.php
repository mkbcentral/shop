<?php

namespace App\Actions\Supplier;

use App\Services\SupplierService;

class DeleteSupplierAction
{
    public function __construct(
        private SupplierService $supplierService
    ) {}

    /**
     * Delete a supplier (soft delete).
     */
    public function execute(int $supplierId): bool
    {
        return $this->supplierService->deleteSupplier($supplierId);
    }
}
