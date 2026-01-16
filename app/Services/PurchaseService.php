<?php

namespace App\Services;

use App\Models\Purchase;
use App\Repositories\PurchaseRepository;
use App\Repositories\PurchaseItemRepository;
use App\Repositories\SupplierRepository;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    public function __construct(
        private PurchaseRepository $purchaseRepository,
        private PurchaseItemRepository $purchaseItemRepository,
        private SupplierRepository $supplierRepository
    ) {}

    /**
     * Create a new purchase.
     */
    public function createPurchase(array $data): Purchase
    {
        // Validate supplier exists
        $supplier = $this->supplierRepository->find($data['supplier_id']);
        if (!$supplier) {
            throw new \Exception("Supplier not found");
        }

        // Validate required fields
        if (!isset($data['purchase_date']) || !isset($data['total'])) {
            throw new \Exception("Purchase date and total are required");
        }

        $purchaseData = [
            'supplier_id' => $data['supplier_id'],
            'purchase_date' => $data['purchase_date'],
            'total' => $data['total'],
            'status' => $data['status'] ?? 'pending',
            'notes' => $data['notes'] ?? null,
        ];

        return $this->purchaseRepository->create($purchaseData);
    }

    /**
     * Update a purchase.
     */
    public function updatePurchase(int $purchaseId, array $data): Purchase
    {
        return DB::transaction(function () use ($purchaseId, $data) {
            $purchase = $this->purchaseRepository->find($purchaseId);

            if (!$purchase) {
                throw new \Exception("Purchase not found");
            }

            if ($purchase->status === 'received') {
                throw new \Exception("Cannot update a received purchase");
            }

            // Update basic fields
            $updateData = [];
            if (isset($data['supplier_id'])) $updateData['supplier_id'] = $data['supplier_id'];
            if (isset($data['purchase_date'])) $updateData['purchase_date'] = $data['purchase_date'];
            if (isset($data['status'])) $updateData['status'] = $data['status'];
            if (isset($data['payment_status'])) $updateData['payment_status'] = $data['payment_status'];
            if (isset($data['paid_amount'])) $updateData['paid_amount'] = $data['paid_amount'];
            if (isset($data['notes'])) $updateData['notes'] = $data['notes'];

            // Update items if provided
            if (isset($data['items'])) {
                // Delete old items
                foreach ($purchase->items as $item) {
                    $this->purchaseItemRepository->delete($item->id);
                }

                // Create new items
                foreach ($data['items'] as $item) {
                    $itemData = [
                        'purchase_id' => $purchase->id,
                        'product_variant_id' => $item['product_variant_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'subtotal' => $item['quantity'] * $item['unit_price'],
                    ];
                    $this->purchaseItemRepository->create($itemData);
                }

                // Recalculate total
                $purchase->refresh();
                $updateData['total'] = $purchase->items()->sum('subtotal');
            } elseif (isset($data['total'])) {
                $updateData['total'] = $data['total'];
            }

            $this->purchaseRepository->update($purchase, $updateData);

            return $purchase->fresh('items.productVariant.product', 'supplier');
        });
    }

    /**
     * Delete a purchase.
     */
    public function deletePurchase(int $purchaseId): bool
    {
        $purchase = $this->purchaseRepository->find($purchaseId);

        if (!$purchase) {
            throw new \Exception("Purchase not found");
        }

        if ($purchase->status === 'received') {
            throw new \Exception("Cannot delete a received purchase. Please cancel it first.");
        }

        return $this->purchaseRepository->delete($purchase);
    }

    /**
     * Mark purchase as received and update stock.
     */
    public function markAsReceived(int $purchaseId): Purchase
    {
        return DB::transaction(function () use ($purchaseId) {
            $purchase = $this->purchaseRepository->find($purchaseId);

            if (!$purchase) {
                throw new \Exception("Purchase not found");
            }

            if ($purchase->status === 'received') {
                throw new \Exception("Purchase is already received");
            }

            // Update stock for each item
            $purchase->load('items.productVariant');
            foreach ($purchase->items as $item) {
                $variant = $item->productVariant;
                if ($variant) {
                    $variant->increaseStock($item->quantity);
                }
            }

            // Mark as received
            $purchase->markAsReceived();

            return $purchase->fresh('items.productVariant.product', 'supplier');
        });
    }

    /**
     * Get purchases by supplier.
     */
    public function getPurchasesBySupplier(int $supplierId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->purchaseRepository->bySupplier($supplierId);
    }

    /**
     * Create a purchase with items.
     */
    public function createPurchaseWithItems(array $data): Purchase
    {
        return DB::transaction(function () use ($data) {
            // Validate supplier exists
            $supplier = $this->supplierRepository->find($data['supplier_id']);
            if (!$supplier) {
                throw new \Exception("Supplier not found");
            }

            // Validate items
            if (!isset($data['items']) || empty($data['items'])) {
                throw new \Exception("Purchase must have at least one item");
            }

            // Calculate total
            $total = 0;
            foreach ($data['items'] as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];
                $total += $subtotal;
            }

            // Create purchase
            $purchaseData = [
                'supplier_id' => $data['supplier_id'],
                'purchase_date' => $data['purchase_date'] ?? now(),
                'total' => $total,
                'status' => $data['status'] ?? 'pending',
                'payment_status' => $data['payment_status'] ?? 'pending',
                'paid_amount' => $data['paid_amount'] ?? 0,
            ];

            $purchase = $this->purchaseRepository->create($purchaseData);

            // Create items
            foreach ($data['items'] as $item) {
                $itemData = [
                    'purchase_id' => $purchase->id,
                    'product_variant_id' => $item['product_variant_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['quantity'] * $item['unit_price'],
                ];
                $this->purchaseItemRepository->create($itemData);
            }

            return $purchase->fresh('items.productVariant.product', 'supplier');
        });
    }

    /**
     * Add item to purchase.
     */
    public function addItemToPurchase(int $purchaseId, array $itemData): Purchase
    {
        return DB::transaction(function () use ($purchaseId, $itemData) {
            $purchase = $this->purchaseRepository->find($purchaseId);

            if (!$purchase) {
                throw new \Exception("Purchase not found");
            }

            if ($purchase->status === 'received') {
                throw new \Exception("Cannot add items to a received purchase");
            }

            // Create item
            $itemData['purchase_id'] = $purchaseId;
            $itemData['subtotal'] = $itemData['quantity'] * $itemData['unit_price'];

            $this->purchaseItemRepository->create($itemData);

            // Recalculate total
            $newTotal = $purchase->items()->sum('subtotal');
            $this->purchaseRepository->update($purchase, ['total' => $newTotal]);

            return $purchase->fresh('items.productVariant.product');
        });
    }

    /**
     * Remove item from purchase.
     */
    public function removeItemFromPurchase(int $purchaseId, int $itemId): Purchase
    {
        return DB::transaction(function () use ($purchaseId, $itemId) {
            $purchase = $this->purchaseRepository->find($purchaseId);

            if (!$purchase) {
                throw new \Exception("Purchase not found");
            }

            if ($purchase->status === 'received') {
                throw new \Exception("Cannot remove items from a received purchase");
            }

            // Verify item belongs to purchase
            $item = $this->purchaseItemRepository->find($itemId);
            if (!$item || $item->purchase_id !== $purchaseId) {
                throw new \Exception("Item not found or does not belong to this purchase");
            }

            // Delete item
            $this->purchaseItemRepository->delete($itemId);

            // Recalculate total
            $newTotal = $purchase->items()->sum('subtotal');
            $this->purchaseRepository->update($purchase, ['total' => $newTotal]);

            return $purchase->fresh('items.productVariant.product');
        });
    }

    /**
     * Update purchase item.
     */
    public function updatePurchaseItem(int $purchaseId, int $itemId, array $itemData): Purchase
    {
        return DB::transaction(function () use ($purchaseId, $itemId, $itemData) {
            $purchase = $this->purchaseRepository->find($purchaseId);

            if (!$purchase) {
                throw new \Exception("Purchase not found");
            }

            if ($purchase->status === 'received') {
                throw new \Exception("Cannot update items of a received purchase");
            }

            // Verify item belongs to purchase
            $item = $this->purchaseItemRepository->find($itemId);
            if (!$item || $item->purchase_id !== $purchaseId) {
                throw new \Exception("Item not found or does not belong to this purchase");
            }

            // Recalculate subtotal if quantity or price changed
            if (isset($itemData['quantity']) || isset($itemData['unit_price'])) {
                $quantity = $itemData['quantity'] ?? $item->quantity;
                $unitPrice = $itemData['unit_price'] ?? $item->unit_price;
                $itemData['subtotal'] = $quantity * $unitPrice;
            }

            // Update item
            $this->purchaseItemRepository->update($itemId, $itemData);

            // Recalculate total
            $newTotal = $purchase->items()->sum('subtotal');
            $this->purchaseRepository->update($purchase, ['total' => $newTotal]);

            return $purchase->fresh('items.productVariant.product');
        });
    }
}
