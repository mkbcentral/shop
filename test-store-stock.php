<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Store;
use App\Models\StoreStock;
use App\Models\Product;
use App\Models\ProductVariant;

// Voir les magasins
echo '=== Magasins ===' . PHP_EOL;
Store::all()->each(fn($s) => print($s->id . ' - ' . $s->name . PHP_EOL));

// Voir les store_stock
echo PHP_EOL . '=== Stock par magasin (store_stock) ===' . PHP_EOL;
$stocks = StoreStock::with(['store', 'variant.product'])->get();
foreach($stocks as $s) {
    $storeName = $s->store->name ?? 'N/A';
    $productName = $s->variant->product->name ?? 'N/A';
    echo "Store {$s->store_id} ({$storeName}) - Variant {$s->product_variant_id} ({$productName}) - Qty: {$s->quantity}" . PHP_EOL;
}

// Voir le store_id des produits
echo PHP_EOL . '=== Produits et leur store_id ===' . PHP_EOL;
Product::take(10)->get()->each(fn($p) => print($p->id . ' - ' . $p->name . ' - Store: ' . ($p->store_id ?? 'NULL') . PHP_EOL));

// Tester la requête avec le filtre
echo PHP_EOL . '=== Test du filtre ProductRepository ===' . PHP_EOL;
$stores = Store::all();
foreach($stores as $store) {
    // Simuler le contexte de magasin
    echo PHP_EOL . "== Store: {$store->name} (ID: {$store->id}) ==" . PHP_EOL;
    
    // Compter les produits par store_id
    $byStoreId = Product::where('store_id', $store->id)->count();
    echo "  - Produits par store_id: {$byStoreId}" . PHP_EOL;
    
    // Compter les produits avec stock dans ce store
    $withStock = Product::whereHas('variants', function($vq) use ($store) {
        $vq->whereHas('storeStocks', function($sq) use ($store) {
            $sq->where('store_id', $store->id)
               ->where('quantity', '>', 0);
        });
    })->count();
    echo "  - Produits avec stock dans ce magasin: {$withStock}" . PHP_EOL;
    
    // Total avec OR
    $total = Product::where(function($q) use ($store) {
        $q->where('store_id', $store->id)
          ->orWhereHas('variants', function($vq) use ($store) {
              $vq->whereHas('storeStocks', function($sq) use ($store) {
                  $sq->where('store_id', $store->id)
                     ->where('quantity', '>', 0);
              });
          });
    })->count();
    echo "  - Total (avec OR): {$total}" . PHP_EOL;
}
