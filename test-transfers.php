<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\StoreTransfer;
use App\Models\StoreTransferItem;
use App\Models\StockMovement;
use App\Models\StoreStock;

echo '=== TRANSFERTS ===' . PHP_EOL;
$transfers = StoreTransfer::with(['items.variant.product', 'fromStore', 'toStore'])->get();

foreach ($transfers as $t) {
    echo PHP_EOL . "Transfer #{$t->id} - {$t->transfer_number}" . PHP_EOL;
    echo "  From: {$t->fromStore->name} -> To: {$t->toStore->name}" . PHP_EOL;
    echo "  Status: {$t->status}" . PHP_EOL;
    echo '  Items:' . PHP_EOL;
    foreach ($t->items as $item) {
        $productName = $item->variant->product->name ?? 'N/A';
        echo "    - Variant {$item->product_variant_id} ({$productName}): requested={$item->quantity_requested}, sent={$item->quantity_sent}, received={$item->quantity_received}" . PHP_EOL;
    }
}

echo PHP_EOL . '=== MOUVEMENTS DE STOCK (derniers 20) ===' . PHP_EOL;
$movements = StockMovement::with(['productVariant.product', 'store'])->orderBy('id', 'desc')->take(20)->get();

foreach ($movements as $m) {
    $productName = $m->productVariant->product->name ?? 'N/A';
    $storeName = $m->store->name ?? 'N/A';
    echo "{$m->type} | {$m->movement_type} | Store: {$storeName} | Variant {$m->product_variant_id} ({$productName}) | Qty: {$m->quantity} | Ref: {$m->reference}" . PHP_EOL;
}

echo PHP_EOL . '=== STOCK PAR MAGASIN ===' . PHP_EOL;
$stocks = StoreStock::with(['store', 'variant.product'])->get();
foreach ($stocks as $s) {
    $storeName = $s->store->name ?? 'N/A';
    $productName = $s->variant->product->name ?? 'N/A';
    echo "Store {$s->store_id} ({$storeName}) | Variant {$s->product_variant_id} ({$productName}) | Qty: {$s->quantity}" . PHP_EOL;
}
