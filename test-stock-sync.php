<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\ProductVariant;
use App\Models\StoreStock;

echo '=== Comparaison stock variant vs store_stock ===' . PHP_EOL;

$variants = ProductVariant::with('product')->get();

foreach ($variants as $variant) {
    echo PHP_EOL . "Variant {$variant->id} - {$variant->product->name}" . PHP_EOL;
    echo "  stock_quantity (global): {$variant->stock_quantity}" . PHP_EOL;
    
    $storeStocks = StoreStock::with('store')->where('product_variant_id', $variant->id)->get();
    foreach ($storeStocks as $ss) {
        $storeName = $ss->store->name ?? 'N/A';
        echo "  store_stock (Store {$ss->store_id} - {$storeName}): {$ss->quantity}" . PHP_EOL;
    }
    
    // Calculer le total théorique
    $total = $storeStocks->sum('quantity');
    echo "  Total store_stock: {$total}" . PHP_EOL;
    
    if ($variant->stock_quantity != $total) {
        echo "  ⚠️ DESYNCHRONISATION: variant.stock_quantity ({$variant->stock_quantity}) != sum(store_stock) ({$total})" . PHP_EOL;
    }
}
