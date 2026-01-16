<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\StoreTransfer;
use App\Models\StockMovement;
use App\Models\StoreStock;

// Compter tous les transferts
$count = StoreTransfer::count();
echo "Nombre total de transferts: $count" . PHP_EOL;

// Compter les mouvements de transfert
$movements = StockMovement::where('movement_type', 'transfer')->get();
echo "Nombre de mouvements transfer: " . $movements->count() . PHP_EOL;

foreach ($movements as $m) {
    echo "  ID: {$m->id} | {$m->type} | Variant: {$m->product_variant_id} | Qty: {$m->quantity} | Ref: {$m->reference}" . PHP_EOL;
}

// Vérifier si le store_stock a été incrémenté plusieurs fois
echo PHP_EOL . "=== Historique store_stock ===" . PHP_EOL;
$stocks = StoreStock::where('store_id', 2)->get();
foreach ($stocks as $stock) {
    echo "Variant {$stock->product_variant_id}: {$stock->quantity} | created: {$stock->created_at} | updated: {$stock->updated_at}" . PHP_EOL;
}
