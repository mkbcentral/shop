<?php

namespace App\Services;

use App\Models\Supplier;
use App\Repositories\SupplierRepository;

class SupplierService
{
    public function __construct(
        private SupplierRepository $supplierRepository
    ) {}

    /**
     * Create a new supplier.
     */
    public function createSupplier(array $data): Supplier
    {
        // Validate required fields
        if (!isset($data['name'])) {
            throw new \Exception("Supplier name is required");
        }

        return $this->supplierRepository->create($data);
    }

    /**
     * Update a supplier.
     */
    public function updateSupplier(int $supplierId, array $data): Supplier
    {
        $supplier = $this->supplierRepository->find($supplierId);

        if (!$supplier) {
            throw new \Exception("Supplier not found");
        }

        $this->supplierRepository->update($supplier, $data);

        return $supplier->fresh();
    }

    /**
     * Delete a supplier.
     */
    public function deleteSupplier(int $supplierId): bool
    {
        $supplier = $this->supplierRepository->find($supplierId);

        if (!$supplier) {
            throw new \Exception("Supplier not found");
        }

        // Check if supplier has purchases
        if ($supplier->hasPurchases()) {
            throw new \Exception("Cannot delete supplier with existing purchase history. Consider archiving instead.");
        }

        return $this->supplierRepository->delete($supplier);
    }

    /**
     * Search suppliers.
     */
    public function searchSuppliers(string $query): \Illuminate\Database\Eloquent\Collection
    {
        return $this->supplierRepository->search($query);
    }
}
