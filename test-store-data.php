<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::find(1);
echo "User ID: {$user->id}\n";
echo "Name: {$user->name}\n";
echo "current_store_id: " . ($user->current_store_id ?? 'NULL') . "\n";
echo "Is Admin: " . ($user->isAdmin() ? 'YES' : 'NO') . "\n";

// Simuler la vue globale
$totalSalesAllStores = App\Models\Sale::sum('total');
$totalSalesStore1 = App\Models\Sale::where('store_id', 1)->sum('total');
$totalSalesStore2 = App\Models\Sale::where('store_id', 2)->sum('total');

echo "\n=== VENTES (TOTAUX) ===\n";
echo "Total TOUS stores: " . number_format($totalSalesAllStores, 0, ',', ' ') . "\n";
echo "Total Store 1: " . number_format($totalSalesStore1, 0, ',', ' ') . "\n";
echo "Total Store 2: " . number_format($totalSalesStore2, 0, ',', ' ') . "\n";

// Count
echo "\n=== COUNT VENTES ===\n";
echo "Sales TOUS: " . App\Models\Sale::count() . "\n";
echo "Sales Store 1: " . App\Models\Sale::where('store_id', 1)->count() . "\n";
echo "Sales Store 2: " . App\Models\Sale::where('store_id', 2)->count() . "\n";

// Produits
echo "\n=== COUNT PRODUITS ===\n";
echo "Produits TOUS: " . App\Models\Product::count() . "\n";
echo "Produits Store 1: " . App\Models\Product::where('store_id', 1)->count() . "\n";
echo "Produits Store 2: " . App\Models\Product::where('store_id', 2)->count() . "\n";
