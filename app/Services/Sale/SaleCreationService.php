<?php

namespace App\Services\Sale;

use App\Events\SaleCompleted;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Repositories\SaleRepository;
use App\Repositories\ProductVariantRepository;
use App\Traits\ResolvesStoreContext;
use Illuminate\Support\Facades\DB;

/**
 * Service responsible for creating new sales
 * Handles sale creation, item management, and number generation
 */
class SaleCreationService
{
    use ResolvesStoreContext;

    public function __construct(
        private SaleRepository $saleRepository,
        private ProductVariantRepository $variantRepository
    ) {}

    /**
     * Create a new sale with items
     * Includes retry mechanism for handling duplicate sale_number conflicts
     * 
     * @param array $data Sale data with items
     * @return Sale Created sale with relations
     * @throws \Exception If validation fails
     */
    public function createSale(array $data): Sale
    {
        $maxRetries = 3;
        $lastException = null;

        for ($retry = 0; $retry < $maxRetries; $retry++) {
            try {
                return $this->createSaleInternal($data);
            } catch (\Illuminate\Database\QueryException $e) {
                $lastException = $e;

                // Check if it's a duplicate entry error (MySQL error 1062)
                if ($e->errorInfo[1] === 1062 && str_contains($e->getMessage(), 'sale_number')) {
                    // Wait a bit before retrying with exponential backoff
                    usleep(50000 * ($retry + 1)); // 50ms, 100ms, 150ms
                    continue;
                }

                // If it's not a duplicate sale_number error, throw immediately
                throw $e;
            }
        }

        // If all retries failed, throw the last exception
        throw $lastException;
    }

    /**
     * Internal method to create sale (used by retry mechanism)
     */
    private function createSaleInternal(array $data): Sale
    {
        return DB::transaction(function () use ($data) {
            // Validate stock availability
            if (isset($data['items'])) {
                $this->validateStockAvailability($data['items']);
            }

            // Resolve store and organization
            $storeId = $this->resolveStoreId($data['store_id'] ?? null);
            $organizationId = $this->resolveOrganizationId(
                $data['organization_id'] ?? null,
                $storeId
            );

            // Generate unique sale number
            $saleNumber = $this->generateUniqueSaleNumber($storeId);

            // Prepare sale data
            $saleData = [
                'organization_id' => $organizationId,
                'store_id' => $storeId,
                'client_id' => $data['client_id'] ?? null,
                'user_id' => $data['user_id'],
                'sale_date' => $data['sale_date'] ?? now(),
                'subtotal' => 0,
                'discount' => $data['discount'] ?? 0,
                'tax' => $data['tax'] ?? 0,
                'total' => 0,
                'paid_amount' => $data['paid_amount'] ?? 0,
                'payment_method' => $data['payment_method'],
                'payment_status' => $data['payment_status'] ?? 'pending',
                'status' => $data['status'] ?? 'pending',
                'notes' => $data['notes'] ?? null,
                'sale_number' => $saleNumber,
            ];

            // Create sale
            $sale = $this->saleRepository->create($saleData);

            // Create sale items
            if (isset($data['items'])) {
                foreach ($data['items'] as $itemData) {
                    $this->addItem($sale, $itemData);
                }
            }

            // Calculate totals
            $sale->calculateTotals();

            // Update payment status based on paid_amount
            if (isset($data['paid_amount']) && $data['paid_amount'] > 0) {
                $sale->updatePaymentStatus();
            }

            return $sale->fresh('items.productVariant.product', 'client', 'user');
        });
    }

    /**
     * Add an item to a sale
     */
    public function addItem(Sale $sale, array $itemData): SaleItem
    {
        $variant = $this->variantRepository->find($itemData['product_variant_id']);

        if (!$variant) {
            throw new \Exception("Product variant not found");
        }

        // Check stock availability
        if (!$variant->hasStock($itemData['quantity'])) {
            throw new \Exception(
                "Insufficient stock for {$variant->full_name}. " .
                "Available: {$variant->stock_quantity}, Requested: {$itemData['quantity']}"
            );
        }

        $item = SaleItem::create([
            'sale_id' => $sale->id,
            'product_variant_id' => $itemData['product_variant_id'],
            'quantity' => $itemData['quantity'],
            'unit_price' => $itemData['unit_price'] ?? $variant->final_price,
            'discount' => $itemData['discount'] ?? 0,
        ]);

        return $item;
    }

    /**
     * Add item to existing sale
     */
    public function addItemToSale(int $saleId, array $itemData): Sale
    {
        return DB::transaction(function () use ($saleId, $itemData) {
            $sale = $this->saleRepository->find($saleId);

            if (!$sale) {
                throw new \Exception("Sale not found");
            }

            if ($sale->status !== Sale::STATUS_PENDING) {
                throw new \Exception("Cannot add items to a completed or cancelled sale");
            }

            $this->addItem($sale, $itemData);
            $sale->calculateTotals();

            return $sale->fresh('items.productVariant.product');
        });
    }

    /**
     * Complete a sale (mark as completed and paid)
     */
    public function completeSale(int $saleId): Sale
    {
        return DB::transaction(function () use ($saleId) {
            $sale = $this->saleRepository->find($saleId);

            if (!$sale) {
                throw new \Exception("Sale not found");
            }

            if ($sale->status === Sale::STATUS_COMPLETED) {
                throw new \Exception("Sale is already completed");
            }

            // Update sale status
            $sale->status = Sale::STATUS_COMPLETED;
            
            // Only mark as paid if it's not already marked as partial or paid
            if ($sale->payment_status === Sale::PAYMENT_PENDING) {
                $sale->paid_amount = $sale->total;
                $sale->payment_status = Sale::PAYMENT_PAID;
            }
            $sale->save();

            // Load items with sale relationship
            $sale->load('items');

            // Create stock movements for each item
            foreach ($sale->items as $item) {
                $item->setRelation('sale', $sale);
                $item->createStockMovement();
            }

            // Dispatch sale completed event
            event(new SaleCompleted($sale));

            return $sale->fresh('items.productVariant.product');
        });
    }

    /**
     * Validate stock availability for all items
     */
    private function validateStockAvailability(array $items): void
    {
        foreach ($items as $itemData) {
            $variant = $this->variantRepository->find($itemData['product_variant_id']);

            if (!$variant) {
                throw new \Exception("Product variant ID {$itemData['product_variant_id']} not found");
            }

            if (!$variant->hasStock($itemData['quantity'])) {
                throw new \Exception(
                    "Insufficient stock for {$variant->full_name}. " .
                    "Available: {$variant->stock_quantity}, Requested: {$itemData['quantity']}"
                );
            }
        }
    }

    /**
     * Generate a unique sale number per store
     * Format: VT-S{store_id}-{année-mois}-{séquence}
     * Example: VT-S1-2026-01-0001
     * 
     * Uses exclusive locks and unique identifiers as fallback to avoid duplicates
     */
    private function generateUniqueSaleNumber(?int $storeId): string
    {
        $storeId = $storeId ?? 0;
        $date = now()->format('Y-m');
        $prefix = 'VT-S' . $storeId . '-' . $date . '-';
        $maxAttempts = 15;

        // Generate unique process identifier to avoid collisions
        $processId = substr(md5(uniqid((string) mt_rand(), true)), 0, 6);

        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            // Get MAX sequence number for this store and month with lock
            $maxNumber = Sale::where('sale_number', 'like', $prefix . '%')
                            ->selectRaw('MAX(CAST(SUBSTRING(sale_number, -4) AS UNSIGNED)) as max_num')
                            ->lockForUpdate()
                            ->value('max_num');

            $nextNumber = ($maxNumber ?? 0) + 1 + $attempt;
            $saleNumber = $prefix . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);

            // Double check with lock to avoid race conditions
            $exists = Sale::where('sale_number', $saleNumber)
                         ->lockForUpdate()
                         ->exists();

            if (!$exists) {
                return $saleNumber;
            }

            // Small pause to reduce contention
            usleep(10000 * ($attempt + 1)); // 10ms, 20ms, 30ms...
        }

        // Last resort: use timestamp + unique process identifier
        return $prefix . now()->format('His') . '-' . $processId;
    }
}
