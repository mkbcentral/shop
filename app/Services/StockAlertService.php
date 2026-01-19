<?php

namespace App\Services;

use App\Models\ProductVariant;
use App\Events\LowStockAlert;
use App\Events\OutOfStockAlert;
use App\Repositories\ProductVariantRepository;
use Illuminate\Support\Collection;

class StockAlertService
{
    public function __construct(
        private ProductVariantRepository $variantRepository
    ) {}

    /**
     * Check all variants for stock alerts.
     */
    public function checkStockLevels(): array
    {
        $lowStockVariants = $this->getLowStockVariants();
        $outOfStockVariants = $this->getOutOfStockVariants();

        // Dispatch events for each low stock variant
        foreach ($lowStockVariants as $variant) {
            event(new LowStockAlert($variant, 'low_stock'));
        }

        // Dispatch events for each out of stock variant
        foreach ($outOfStockVariants as $variant) {
            event(new OutOfStockAlert($variant));
        }

        return [
            'low_stock_count' => $lowStockVariants->count(),
            'out_of_stock_count' => $outOfStockVariants->count(),
            'low_stock_variants' => $lowStockVariants,
            'out_of_stock_variants' => $outOfStockVariants,
        ];
    }

    /**
     * Get all low stock variants.
     */
    public function getLowStockVariants(): Collection
    {
        $query = ProductVariant::whereRaw('stock_quantity <= low_stock_threshold')
            ->where('stock_quantity', '>', 0)
            ->with('product');
        
        // Filter by current store if user is not admin
        if (!user_can_access_all_stores() && effective_store_id()) {
            $query->whereHas('product', function ($q) {
                $q->where('store_id', effective_store_id());
            });
        }
        
        return $query->get()
            ->filter(fn($variant) => $variant->isLowStock());
    }

    /**
     * Get all out of stock variants.
     */
    public function getOutOfStockVariants(): Collection
    {
        $query = ProductVariant::whereRaw('stock_quantity <= min_stock_threshold')
            ->with('product');
        
        // Filter by current store if user is not admin
        if (!user_can_access_all_stores() && effective_store_id()) {
            $query->whereHas('product', function($q) {
                $q->where('store_id', effective_store_id());
            });
        }
        
        return $query->get()
            ->filter(fn($variant) => $variant->isOutOfStock());
    }

    /**
     * Get stock alerts summary.
     */
    public function getAlertsSummary(): array
    {
        $lowStock = $this->getLowStockVariants();
        $outOfStock = $this->getOutOfStockVariants();

        return [
            'total_alerts' => $lowStock->count() + $outOfStock->count(),
            'low_stock' => [
                'count' => $lowStock->count(),
                'variants' => $lowStock->map(fn($v) => [
                    'id' => $v->id,
                    'product' => $v->product->name,
                    'variant' => $v->full_name,
                    'current_stock' => $v->stock_quantity,
                    'threshold' => $v->low_stock_threshold,
                    'status' => 'low_stock',
                ]),
            ],
            'out_of_stock' => [
                'count' => $outOfStock->count(),
                'variants' => $outOfStock->map(fn($v) => [
                    'id' => $v->id,
                    'product' => $v->product->name,
                    'variant' => $v->full_name,
                    'current_stock' => $v->stock_quantity,
                    'status' => 'out_of_stock',
                ]),
            ],
        ];
    }

    /**
     * Check specific variant for alerts.
     */
    public function checkVariant(int $variantId): ?string
    {
        $variant = $this->variantRepository->find($variantId);

        if (!$variant) {
            return null;
        }

        if ($variant->isOutOfStock()) {
            event(new OutOfStockAlert($variant));
            return 'out_of_stock';
        }

        if ($variant->isLowStock()) {
            event(new LowStockAlert($variant, 'low_stock'));
            return 'low_stock';
        }

        return 'in_stock';
    }

    /**
     * Update stock thresholds for a variant.
     */
    public function updateThresholds(int $variantId, int $lowThreshold, int $minThreshold = 0): ProductVariant
    {
        $variant = $this->variantRepository->find($variantId);

        if (!$variant) {
            throw new \Exception("Product variant not found");
        }

        $variant->low_stock_threshold = $lowThreshold;
        $variant->min_stock_threshold = $minThreshold;
        $variant->save();

        // Check if this update triggers an alert
        $this->checkVariant($variantId);

        return $variant->fresh();
    }

    /**
     * Bulk update thresholds for multiple variants.
     */
    public function bulkUpdateThresholds(array $updates): array
    {
        $results = [];

        foreach ($updates as $update) {
            try {
                $variant = $this->updateThresholds(
                    $update['variant_id'],
                    $update['low_threshold'],
                    $update['min_threshold'] ?? 0
                );
                $results[] = [
                    'variant_id' => $variant->id,
                    'status' => 'success',
                    'stock_status' => $variant->stock_status,
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'variant_id' => $update['variant_id'],
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }
}
