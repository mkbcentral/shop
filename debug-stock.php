<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\StockMovement;
use App\Models\StoreStock;
use App\Models\ProductVariant;
use App\Models\StoreTransfer;

echo "=== ANALYZING TRF-2026-0002 ===\n";

// Get transfer
$transfer = StoreTransfer::where('transfer_number', 'TRF-2026-0002')->first();
echo "Transfer: {$transfer->transfer_number}\n";
echo "From Store: {$transfer->from_store_id} -> To Store: {$transfer->to_store_id}\n";
echo "Status: {$transfer->status}\n\n";

// Count movements for this transfer
$outMovements = StockMovement::where('reference', 'TRF-2026-0002')->where('type', 'out')->count();
$inMovements = StockMovement::where('reference', 'TRF-2026-0002')->where('type', 'in')->count();

echo "OUT movements count: {$outMovements}\n";
echo "IN movements count: {$inMovements}\n\n";

// Detail movements
echo "=== MOVEMENTS DETAIL ===\n";
$movements = StockMovement::where('reference', 'TRF-2026-0002')->orderBy('created_at')->get();
foreach($movements as $m) {
    echo "ID:{$m->id} | Type:{$m->type} | Var:{$m->product_variant_id} | Qty:{$m->quantity} | Store:{$m->store_id} | {$m->created_at}\n";
}

echo "\n=== EXPECTED VS ACTUAL ===\n";
// For variant 4, product had initial stock of 40
// Transfer requested 10
// Expected: Store 1 = 30, Store 2 = 10
// Actual: Store 1 = 20, Store 2 = 20

$stock1 = StoreStock::where('store_id', 1)->where('product_variant_id', 4)->first();
$stock2 = StoreStock::where('store_id', 2)->where('product_variant_id', 4)->first();

echo "Variant 4:\n";
echo "  Store 1 stock: {$stock1->quantity} (expected: 30)\n";
echo "  Store 2 stock: " . ($stock2 ? $stock2->quantity : 'N/A') . " (expected: 10)\n";

// Calculate what should happen
$initial = 40;
$transferred = 10;
echo "\nCalculation check:\n";
echo "  Initial: {$initial}\n";
echo "  Transferred: {$transferred}\n";
echo "  Expected Source: " . ($initial - $transferred) . "\n";
echo "  Expected Dest: {$transferred}\n";

// Check if there were any other movements that affected this variant
echo "\n=== ALL MOVEMENTS FOR VARIANT 4 ===\n";
$allMovements = StockMovement::where('product_variant_id', 4)->orderBy('created_at')->get();
foreach($allMovements as $m) {
    echo "ID:{$m->id} | Type:{$m->type} | Qty:{$m->quantity} | Ref:{$m->reference} | Store:{$m->store_id} | {$m->created_at}\n";
}

// Sum up what stock should be
$sumIn = StockMovement::where('product_variant_id', 4)->where('type', 'in')->sum('quantity');
$sumOut = StockMovement::where('product_variant_id', 4)->where('type', 'out')->sum('quantity');
echo "\nTotal IN: {$sumIn}\n";
echo "Total OUT: {$sumOut}\n";
echo "Net: " . ($sumIn - $sumOut) . "\n";
