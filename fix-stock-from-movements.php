<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\StockMovement;
use App\Models\StoreStock;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

echo "=== FIXING STORE STOCK BASED ON MOVEMENTS ===\n\n";

// Get all unique store + variant combinations from movements
$stores = StoreStock::select('store_id')->distinct()->pluck('store_id');
$variants = StoreStock::select('product_variant_id')->distinct()->pluck('product_variant_id');

foreach ($stores as $storeId) {
    echo "Store {$storeId}:\n";
    
    foreach ($variants as $variantId) {
        // Calculate correct stock from movements
        $inQty = StockMovement::where('store_id', $storeId)
            ->where('product_variant_id', $variantId)
            ->where('type', 'in')
            ->sum('quantity');
            
        $outQty = StockMovement::where('store_id', $storeId)
            ->where('product_variant_id', $variantId)
            ->where('type', 'out')
            ->sum('quantity');
            
        $correctStock = $inQty - $outQty;
        
        // Get current stock
        $storeStock = StoreStock::where('store_id', $storeId)
            ->where('product_variant_id', $variantId)
            ->first();
            
        if ($storeStock) {
            $currentStock = $storeStock->quantity;
            
            if ($currentStock != $correctStock) {
                echo "  Variant {$variantId}: Current={$currentStock}, Correct={$correctStock} (IN:{$inQty}, OUT:{$outQty}) -> FIXING\n";
                
                // Update without triggering any events
                DB::table('store_stock')
                    ->where('store_id', $storeId)
                    ->where('product_variant_id', $variantId)
                    ->update(['quantity' => $correctStock]);
            } else {
                echo "  Variant {$variantId}: OK ({$currentStock})\n";
            }
        }
    }
    echo "\n";
}

// Also sync the global variant stock
echo "=== SYNCING GLOBAL VARIANT STOCK ===\n";
$allVariants = ProductVariant::all();
foreach ($allVariants as $variant) {
    $totalStock = StoreStock::where('product_variant_id', $variant->id)->sum('quantity');
    if ($variant->stock_quantity != $totalStock) {
        echo "Variant {$variant->id}: Global={$variant->stock_quantity}, Total from stores={$totalStock} -> FIXING\n";
        $variant->update(['stock_quantity' => $totalStock]);
    }
}

echo "\n=== VERIFICATION ===\n";
$stocks = StoreStock::orderBy('store_id')->orderBy('product_variant_id')->get();
foreach($stocks as $s) {
    echo "Store:{$s->store_id} | Variant:{$s->product_variant_id} | Qty:{$s->quantity}\n";
}
