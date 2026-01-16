<?php

namespace App\Services;

use App\Models\StockMovement;
use App\Models\Store;
use App\Repositories\StockMovementRepository;
use App\Repositories\ProductVariantRepository;
use Illuminate\Support\Facades\DB;

class StockService
{
    public function __construct(
        private StockMovementRepository $movementRepository,
        private ProductVariantRepository $variantRepository
    ) {}

    /**
     * Get the store ID to use for stock movements.
     * Falls back to user's first store if current_store_id is null.
     */
    private function getStoreIdForMovement(?int $providedStoreId = null): int
    {
        // Use provided store_id first
        if ($providedStoreId) {
            return $providedStoreId;
        }

        // Use current_store_id if set
        $currentStoreId = current_store_id();
        if ($currentStoreId) {
            return $currentStoreId;
        }

        // Fall back to user's first store
        $user = auth()->user();
        if ($user && $user->stores->isNotEmpty()) {
            return $user->stores->first()->id;
        }

        // Last resort: use first store in system
        $firstStore = Store::first();
        if ($firstStore) {
            return $firstStore->id;
        }

        throw new \Exception("Aucun magasin disponible. Veuillez créer un magasin d'abord.");
    }

    /**
     * Add stock (IN movement).
     */
    public function addStock(array $data): StockMovement
    {
        return DB::transaction(function () use ($data) {
            $variant = $this->variantRepository->find($data['product_variant_id']);

            if (!$variant) {
                throw new \Exception("Product variant not found");
            }

            $movementData = [
                'store_id' => $this->getStoreIdForMovement($data['store_id'] ?? null),
                'product_variant_id' => $data['product_variant_id'],
                'type' => StockMovement::TYPE_IN,
                'movement_type' => $data['movement_type'] ?? StockMovement::MOVEMENT_PURCHASE,
                'quantity' => $data['quantity'],
                'reference' => $data['reference'] ?? null,
                'reason' => $data['reason'] ?? null,
                'unit_price' => $data['unit_price'] ?? null,
                'total_price' => $data['total_price'] ?? null,
                'date' => $data['date'] ?? now(),
                'user_id' => $data['user_id'],
            ];

            $movement = $this->movementRepository->create($movementData);

            // Update product's cost_price if requested and unit_price is provided
            if (!empty($data['update_product_cost']) && !empty($data['unit_price'])) {
                $product = $variant->product;
                if ($product) {
                    $product->update(['cost_price' => $data['unit_price']]);
                }
            }

            return $movement->fresh('productVariant.product', 'user');
        });
    }

    /**
     * Get variant stock information.
     */
    public function getVariantStock(int $variantId)
    {
        return $this->variantRepository->find($variantId);
    }

    /**
     * Remove stock (OUT movement).
     */
    public function removeStock(array $data): StockMovement
    {
        return DB::transaction(function () use ($data) {
            $variant = $this->variantRepository->find($data['product_variant_id']);

            if (!$variant) {
                throw new \Exception("Product variant not found");
            }

            // Check if sufficient stock is available
            if (!$variant->hasStock($data['quantity'])) {
                throw new \Exception("Insufficient stock. Available: {$variant->stock_quantity}, Requested: {$data['quantity']}");
            }

            $movementData = [
                'store_id' => $this->getStoreIdForMovement($data['store_id'] ?? null),
                'product_variant_id' => $data['product_variant_id'],
                'type' => StockMovement::TYPE_OUT,
                'movement_type' => $data['movement_type'] ?? StockMovement::MOVEMENT_ADJUSTMENT,
                'quantity' => $data['quantity'],
                'reference' => $data['reference'] ?? null,
                'reason' => $data['reason'] ?? null,
                'unit_price' => $data['unit_price'] ?? null,
                'total_price' => $data['total_price'] ?? null,
                'date' => $data['date'] ?? now(),
                'user_id' => $data['user_id'],
            ];

            $movement = $this->movementRepository->create($movementData);

            return $movement->fresh('productVariant.product', 'user');
        });
    }

    /**
     * Adjust stock (correction).
     */
    public function adjustStock(int $variantId, int $newQuantity, int $userId, string $reason = null): StockMovement
    {
        return DB::transaction(function () use ($variantId, $newQuantity, $userId, $reason) {
            $variant = $this->variantRepository->find($variantId);

            if (!$variant) {
                throw new \Exception("Product variant not found");
            }

            $currentStock = $variant->stock_quantity;
            $difference = $newQuantity - $currentStock;

            if ($difference == 0) {
                throw new \Exception("No adjustment needed. Stock is already at {$currentStock}");
            }

            $type = $difference > 0 ? StockMovement::TYPE_IN : StockMovement::TYPE_OUT;
            $quantity = abs($difference);

            $movementData = [
                'store_id' => $this->getStoreIdForMovement(),
                'product_variant_id' => $variantId,
                'type' => $type,
                'movement_type' => StockMovement::MOVEMENT_ADJUSTMENT,
                'quantity' => $quantity,
                'reason' => $reason ?? "Stock adjustment from {$currentStock} to {$newQuantity}",
                'date' => now(),
                'user_id' => $userId,
            ];

            return $this->movementRepository->create($movementData);
        });
    }

    /**
     * Transfer stock between variants.
     */
    public function transferStock(int $fromVariantId, int $toVariantId, int $quantity, int $userId, string $reason = null): array
    {
        return DB::transaction(function () use ($fromVariantId, $toVariantId, $quantity, $userId, $reason) {
            $fromVariant = $this->variantRepository->find($fromVariantId);
            $toVariant = $this->variantRepository->find($toVariantId);

            if (!$fromVariant || !$toVariant) {
                throw new \Exception("Product variant not found");
            }

            if (!$fromVariant->hasStock($quantity)) {
                throw new \Exception("Insufficient stock in source variant. Available: {$fromVariant->stock_quantity}");
            }

            $reference = 'TRANSFER-' . now()->format('YmdHis');

            // Remove from source
            $outMovement = $this->removeStock([
                'product_variant_id' => $fromVariantId,
                'quantity' => $quantity,
                'movement_type' => StockMovement::MOVEMENT_TRANSFER,
                'reference' => $reference,
                'reason' => $reason ?? "Transfer to {$toVariant->full_name}",
                'user_id' => $userId,
            ]);

            // Add to destination
            $inMovement = $this->addStock([
                'product_variant_id' => $toVariantId,
                'quantity' => $quantity,
                'movement_type' => StockMovement::MOVEMENT_TRANSFER,
                'reference' => $reference,
                'reason' => $reason ?? "Transfer from {$fromVariant->full_name}",
                'user_id' => $userId,
            ]);

            return [
                'from_movement' => $outMovement,
                'to_movement' => $inMovement,
                'reference' => $reference,
            ];
        });
    }

    /**
     * Process stock return from client.
     */
    public function returnStock(int $variantId, int $quantity, int $userId, string $reason, int $saleId = null): StockMovement
    {
        return DB::transaction(function () use ($variantId, $quantity, $userId, $reason, $saleId) {
            $reference = $saleId ? "RETURN-SALE-{$saleId}" : 'RETURN-' . now()->format('YmdHis');

            return $this->addStock([
                'product_variant_id' => $variantId,
                'quantity' => $quantity,
                'movement_type' => StockMovement::MOVEMENT_RETURN,
                'reference' => $reference,
                'reason' => $reason,
                'user_id' => $userId,
            ]);
        });
    }

    /**
     * Get stock history for a variant.
     */
    public function getStockHistory(int $variantId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->movementRepository->byProductVariant($variantId);
    }

    /**
     * Get stock movements statistics.
     */
    public function getStatistics(string $startDate, string $endDate): array
    {
        return $this->movementRepository->statistics($startDate, $endDate);
    }

    /**
     * Get current stock levels.
     */
    public function getCurrentStockLevels(): array
    {
        $inStock = $this->variantRepository->inStock();
        $outOfStock = $this->variantRepository->outOfStock();
        $lowStock = $this->variantRepository->lowStock();

        return [
            'in_stock_count' => $inStock->count(),
            'out_of_stock_count' => $outOfStock->count(),
            'low_stock_count' => $lowStock->count(),
            'total_stock_value' => $inStock->sum(function ($variant) {
                return $variant->stock_quantity * $variant->final_price;
            }),
        ];
    }
}
