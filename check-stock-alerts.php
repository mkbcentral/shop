<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Analyse des alertes de stock par store:\n";
echo "=========================================\n\n";

// Compter tous les variants out of stock
$allOutOfStock = DB::table('product_variants')
    ->where('stock_quantity', 0)
    ->count();

echo "Total variants en rupture de stock (tous stores): $allOutOfStock\n\n";

// Compter par store via la relation product
$outOfStockByStore = DB::table('product_variants')
    ->join('products', 'product_variants.product_id', '=', 'products.id')
    ->select('products.store_id', DB::raw('COUNT(*) as count'))
    ->where('product_variants.stock_quantity', 0)
    ->groupBy('products.store_id')
    ->get();

echo "Répartition par store:\n";
foreach ($outOfStockByStore as $item) {
    $storeName = DB::table('stores')->where('id', $item->store_id)->value('name');
    echo "  Store {$item->store_id} ({$storeName}): {$item->count} variants en rupture\n";
}

echo "\n";

// Compter low stock
$allLowStock = DB::table('product_variants')
    ->whereRaw('stock_quantity <= low_stock_threshold')
    ->where('stock_quantity', '>', 0)
    ->count();

echo "Total variants en stock bas (tous stores): $allLowStock\n\n";

// Compter low stock par store
$lowStockByStore = DB::table('product_variants')
    ->join('products', 'product_variants.product_id', '=', 'products.id')
    ->select('products.store_id', DB::raw('COUNT(*) as count'))
    ->whereRaw('product_variants.stock_quantity <= product_variants.low_stock_threshold')
    ->where('product_variants.stock_quantity', '>', 0)
    ->groupBy('products.store_id')
    ->get();

echo "Stock bas par store:\n";
foreach ($lowStockByStore as $item) {
    $storeName = DB::table('stores')->where('id', $item->store_id)->value('name');
    echo "  Store {$item->store_id} ({$storeName}): {$item->count} variants en stock bas\n";
}

echo "\n";

// Lister les stores
echo "Liste des stores:\n";
$stores = DB::table('stores')->select('id', 'name')->get();
foreach ($stores as $store) {
    $productCount = DB::table('products')->where('store_id', $store->id)->count();
    echo "  Store {$store->id}: {$store->name} - {$productCount} produits\n";
}
