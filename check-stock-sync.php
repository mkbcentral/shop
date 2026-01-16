<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Derniers mouvements de stock ===" . PHP_EOL;
$movements = App\Models\StockMovement::with('productVariant.product')->latest()->take(5)->get();
foreach($movements as $m) {
    $productName = $m->productVariant?->product?->name ?? 'N/A';
    echo "Movement ID: {$m->id} | Type: {$m->type} | Qty: {$m->quantity} | Store: {$m->store_id} | Variant: {$m->product_variant_id} | Produit: {$productName}" . PHP_EOL;
}

echo PHP_EOL . "=== Stocks par magasin (StoreStock) ===" . PHP_EOL;
$storeStocks = App\Models\StoreStock::with('variant.product')->get();
foreach($storeStocks as $ss) {
    $productName = $ss->variant?->product?->name ?? 'N/A';
    echo "Store: {$ss->store_id} | Variant: {$ss->product_variant_id} | Qty StoreStock: {$ss->quantity} | Produit: {$productName}" . PHP_EOL;
}

echo PHP_EOL . "=== Product Variants (stock_quantity) ===" . PHP_EOL;
$variants = App\Models\ProductVariant::with('product')->take(10)->get();
foreach($variants as $v) {
    $productName = $v->product?->name ?? 'N/A';
    echo "Variant ID: {$v->id} | stock_quantity: {$v->stock_quantity} | low_threshold: {$v->low_stock_threshold} | Produit: {$productName}" . PHP_EOL;
}

echo PHP_EOL . "=== Comparaison StoreStock vs ProductVariant ===" . PHP_EOL;
foreach($variants as $v) {
    $totalStoreStock = App\Models\StoreStock::where('product_variant_id', $v->id)->sum('quantity');
    $productName = $v->product?->name ?? 'N/A';
    $match = ($totalStoreStock == $v->stock_quantity) ? '✓' : '✗ DESYNC';
    echo "Variant {$v->id}: StoreStock total = {$totalStoreStock} | ProductVariant.stock_quantity = {$v->stock_quantity} {$match} | {$productName}" . PHP_EOL;
}
