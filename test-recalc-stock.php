<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\StoreStock;
use App\Models\StockMovement;

echo "=== Recalcul des stocks store_stock à partir des mouvements ===" . PHP_EOL;

// Pour le store 2 (Ben busness)
$storeId = 2;
$variantIds = [1, 2];

foreach ($variantIds as $variantId) {
    // Calculer le stock attendu basé sur les mouvements
    $inMovements = StockMovement::where('store_id', $storeId)
        ->where('product_variant_id', $variantId)
        ->where('type', 'in')
        ->sum('quantity');
    
    $outMovements = StockMovement::where('store_id', $storeId)
        ->where('product_variant_id', $variantId)
        ->where('type', 'out')
        ->sum('quantity');
    
    $expectedStock = $inMovements - $outMovements;
    
    $currentStock = StoreStock::where('store_id', $storeId)
        ->where('product_variant_id', $variantId)
        ->first();
    
    $actualStock = $currentStock ? $currentStock->quantity : 0;
    
    echo PHP_EOL . "Variant $variantId (Store $storeId):" . PHP_EOL;
    echo "  IN movements total: $inMovements" . PHP_EOL;
    echo "  OUT movements total: $outMovements" . PHP_EOL;
    echo "  Expected stock (IN - OUT): $expectedStock" . PHP_EOL;
    echo "  Actual stock in store_stock: $actualStock" . PHP_EOL;
    
    if ($expectedStock != $actualStock) {
        echo "  ⚠️ DISCREPANCY: Expected $expectedStock but got $actualStock" . PHP_EOL;
        echo "  Difference: " . ($actualStock - $expectedStock) . PHP_EOL;
    } else {
        echo "  ✅ Stock is correct" . PHP_EOL;
    }
}

// Optionnel : corriger automatiquement
echo PHP_EOL . "=== Voulez-vous corriger les stocks? (en cours de vérification) ===" . PHP_EOL;
