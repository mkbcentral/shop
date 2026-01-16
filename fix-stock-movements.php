<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\StockMovement;
use App\Models\StoreStock;
use App\Models\Store;

echo "=== Correction des mouvements sans store_id ===" . PHP_EOL;

// Trouver le premier magasin
$defaultStore = Store::first();
if (!$defaultStore) {
    echo "ERREUR: Aucun magasin trouvé!" . PHP_EOL;
    exit(1);
}

echo "Magasin par défaut: {$defaultStore->id} - {$defaultStore->name}" . PHP_EOL . PHP_EOL;

// Trouver les mouvements sans store_id
$movementsWithoutStore = StockMovement::whereNull('store_id')->get();
echo "Mouvements sans store_id: {$movementsWithoutStore->count()}" . PHP_EOL;

foreach ($movementsWithoutStore as $movement) {
    echo "  - Correction Movement ID: {$movement->id} -> store_id: {$defaultStore->id}" . PHP_EOL;
    $movement->store_id = $defaultStore->id;
    $movement->save();
    
    // Mettre à jour le StoreStock
    $storeStock = StoreStock::firstOrCreate(
        [
            'store_id' => $defaultStore->id,
            'product_variant_id' => $movement->product_variant_id,
        ],
        [
            'quantity' => 0,
            'low_stock_threshold' => 10,
            'min_stock_threshold' => 0,
        ]
    );
    
    if ($movement->type === 'in') {
        $storeStock->increment('quantity', $movement->quantity);
        echo "    + Ajouté {$movement->quantity} au StoreStock" . PHP_EOL;
    } elseif ($movement->type === 'out') {
        $storeStock->decrement('quantity', $movement->quantity);
        echo "    - Retiré {$movement->quantity} du StoreStock" . PHP_EOL;
    }
}

echo PHP_EOL . "=== Synchronisation des ProductVariants ===" . PHP_EOL;

// Synchroniser tous les variants
$variants = App\Models\ProductVariant::all();
foreach ($variants as $variant) {
    $totalStock = StoreStock::where('product_variant_id', $variant->id)->sum('quantity');
    $oldStock = $variant->stock_quantity;
    
    if ($totalStock != $oldStock) {
        $variant->stock_quantity = $totalStock;
        $variant->save();
        echo "Variant {$variant->id}: {$oldStock} -> {$totalStock}" . PHP_EOL;
    }
}

echo PHP_EOL . "=== Vérification finale ===" . PHP_EOL;

// Afficher l'état final
$storeStocks = StoreStock::with('variant.product')->get();
foreach ($storeStocks as $ss) {
    $productName = $ss->variant?->product?->name ?? 'N/A';
    echo "StoreStock - Store: {$ss->store_id} | Variant: {$ss->product_variant_id} | Qty: {$ss->quantity} | {$productName}" . PHP_EOL;
}

echo PHP_EOL;
$variants = App\Models\ProductVariant::with('product')->get();
foreach ($variants as $v) {
    $productName = $v->product?->name ?? 'N/A';
    $status = $v->stock_quantity <= $v->low_stock_threshold ? '⚠️ ALERTE' : '✓ OK';
    echo "Variant {$v->id}: stock_quantity = {$v->stock_quantity} | threshold = {$v->low_stock_threshold} | {$status} | {$productName}" . PHP_EOL;
}

echo PHP_EOL . "✅ Correction terminée!" . PHP_EOL;
