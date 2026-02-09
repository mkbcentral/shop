<?php

declare(strict_types=1);

namespace App\Listeners\Pos;

use App\Events\Pos\SaleCompleted;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Listener qui vérifie et notifie pour les stocks faibles
 */
class NotifyLowStockListener implements ShouldQueue
{
    /**
     * Seuil de stock faible
     */
    private const LOW_STOCK_THRESHOLD = 10;

    /**
     * Gère l'événement de vente complétée
     */
    public function handle(SaleCompleted $event): void
    {
        // Vérifier le stock de chaque article vendu
        foreach ($event->sale->items as $item) {
            $variant = ProductVariant::with('product.productType')->find($item->product_variant_id);

            if (!$variant) {
                continue;
            }

            // Skip service products - they don't have stock management
            if ($variant->product->productType?->is_service) {
                continue;
            }

            // Si le stock est faible, créer une notification
            if ($variant->stock_quantity <= self::LOW_STOCK_THRESHOLD) {
                $this->notifyLowStock($variant);
            }

            // Si rupture de stock
            if ($variant->stock_quantity <= 0) {
                $this->notifyOutOfStock($variant);
            }
        }
    }

    /**
     * Notifie pour un stock faible
     */
    private function notifyLowStock(ProductVariant $variant): void
    {
        Log::channel('inventory')->warning('Stock faible détecté', [
            'product_id' => $variant->product_id,
            'product_name' => $variant->product->name,
            'variant_id' => $variant->id,
            'current_stock' => $variant->stock_quantity,
            'threshold' => self::LOW_STOCK_THRESHOLD,
            'timestamp' => now()->toIso8601String(),
        ]);

        // TODO: Envoyer une notification aux gestionnaires
        // Notification::send($managers, new LowStockNotification($variant));
    }

    /**
     * Notifie pour une rupture de stock
     */
    private function notifyOutOfStock(ProductVariant $variant): void
    {
        Log::channel('inventory')->error('Rupture de stock détectée', [
            'product_id' => $variant->product_id,
            'product_name' => $variant->product->name,
            'variant_id' => $variant->id,
            'timestamp' => now()->toIso8601String(),
        ]);

        // TODO: Notification urgente
        // Notification::send($managers, new OutOfStockNotification($variant));
    }
}
