<?php

namespace App\Actions\Stock;

use App\Models\StockMovement;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class UpdateStockMovementAction
{
    /**
     * Update an existing stock movement.
     * This will reverse the old movement and apply the new one.
     */
    public function execute(int $movementId, array $data): StockMovement
    {
        return DB::transaction(function () use ($movementId, $data) {
            $movement = StockMovement::findOrFail($movementId);
            $variant = ProductVariant::findOrFail($movement->product_variant_id);

            // Calculate the difference to adjust stock
            $oldQuantity = $movement->quantity;
            $newQuantity = $data['quantity'] ?? $oldQuantity;
            $oldType = $movement->type;

            // Reverse the old movement effect on stock
            if ($oldType === StockMovement::TYPE_IN) {
                // Was an IN movement, so we need to remove the old quantity
                $variant->decrement('stock_quantity', $oldQuantity);
            } else {
                // Was an OUT movement, so we need to add back the old quantity
                $variant->increment('stock_quantity', $oldQuantity);
            }

            // Apply the new movement effect on stock
            if ($oldType === StockMovement::TYPE_IN) {
                // Check if new quantity is valid (can't make stock negative after other operations)
                $variant->increment('stock_quantity', $newQuantity);
            } else {
                // Check if we have enough stock for the new OUT quantity
                $currentStock = $variant->fresh()->stock_quantity;
                if ($currentStock < $newQuantity) {
                    // Rollback the reversal
                    if ($oldType === StockMovement::TYPE_IN) {
                        $variant->increment('stock_quantity', $oldQuantity);
                    } else {
                        $variant->decrement('stock_quantity', $oldQuantity);
                    }
                    throw new \Exception("Stock insuffisant. Disponible: {$currentStock}, Demandé: {$newQuantity}");
                }
                $variant->decrement('stock_quantity', $newQuantity);
            }

            // Update the movement record
            $movement->update([
                'quantity' => $newQuantity,
                'reference' => $data['reference'] ?? $movement->reference,
                'reason' => $data['reason'] ?? $movement->reason,
                'unit_price' => $data['unit_price'] ?? $movement->unit_price,
                'total_price' => isset($data['unit_price']) && isset($data['quantity']) 
                    ? $data['unit_price'] * $data['quantity'] 
                    : $movement->total_price,
                'date' => $data['date'] ?? $movement->date,
            ]);

            return $movement->fresh('productVariant.product', 'user');
        });
    }
}
