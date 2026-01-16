<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\StoreStock;
use App\Models\ProductVariant;

// Corriger les stocks pour store 2
$storeId = 2;

echo "=== Correction des stocks ===" . PHP_EOL;

// Variant 1 - devrait être 10 pas 20
$stock1 = StoreStock::where('store_id', $storeId)
    ->where('product_variant_id', 1)
    ->first();
if ($stock1 && $stock1->quantity == 20) {
    $stock1->update(['quantity' => 10]);
    echo "Variant 1 corrigé: 20 -> 10" . PHP_EOL;
}

// Variant 2 - devrait être 10 pas 20  
$stock2 = StoreStock::where('store_id', $storeId)
    ->where('product_variant_id', 2)
    ->first();
if ($stock2 && $stock2->quantity == 20) {
    $stock2->update(['quantity' => 10]);
    echo "Variant 2 corrigé: 20 -> 10" . PHP_EOL;
}

// Resynchroniser les variants
echo PHP_EOL . "=== Resynchronisation des stock_quantity ===" . PHP_EOL;

$variant1 = ProductVariant::find(1);
$totalStock1 = StoreStock::where('product_variant_id', 1)->sum('quantity');
$variant1->update(['stock_quantity' => $totalStock1]);
echo "Variant 1 stock_quantity: {$variant1->stock_quantity} -> $totalStock1" . PHP_EOL;

$variant2 = ProductVariant::find(2);
$totalStock2 = StoreStock::where('product_variant_id', 2)->sum('quantity');
$variant2->update(['stock_quantity' => $totalStock2]);
echo "Variant 2 stock_quantity: {$variant2->stock_quantity} -> $totalStock2" . PHP_EOL;

echo PHP_EOL . "=== Vérification finale ===" . PHP_EOL;
$stocks = StoreStock::with(['store', 'variant.product'])->get();
foreach ($stocks as $s) {
    $storeName = $s->store->name ?? 'N/A';
    $productName = $s->variant->product->name ?? 'N/A';
    echo "Store {$s->store_id} ({$storeName}) | {$productName} | Qty: {$s->quantity}" . PHP_EOL;
}

echo PHP_EOL . "Stocks corrigés!" . PHP_EOL;
