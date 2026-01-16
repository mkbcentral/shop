<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Simuler un utilisateur avec un store spécifique
$user = \App\Models\User::find(1); // Remplacez par votre ID utilisateur
Auth::login($user);

echo "Test des statistiques du dashboard\n";
echo "===================================\n\n";

echo "User ID: {$user->id}\n";
echo "User Name: {$user->name}\n";
echo "Current Store ID: " . ($user->current_store_id ?? 'NULL') . "\n";
echo "Is Admin: " . ($user->isAdmin() ? 'Yes' : 'No') . "\n";
echo "user_can_access_all_stores(): " . (user_can_access_all_stores() ? 'true' : 'false') . "\n";
echo "current_store_id(): " . (current_store_id() ?? 'NULL') . "\n\n";

// Tester les requêtes de stock
$dashboardRepo = app(\App\Repositories\DashboardRepository::class);

echo "=== Stock Stats ===\n";
$lowStock = $dashboardRepo->getLowStockCount();
$outOfStock = $dashboardRepo->getOutOfStockCount();

echo "Low Stock Count: $lowStock\n";
echo "Out of Stock Count: $outOfStock\n\n";

// Vérifier directement dans la base de données
echo "=== Direct DB Queries ===\n";

$storeId = current_store_id();

if ($storeId) {
    echo "Filtering by store_id: $storeId\n\n";

    $lowStockDirect = DB::table('product_variants')
        ->join('products', 'product_variants.product_id', '=', 'products.id')
        ->where('products.store_id', $storeId)
        ->whereRaw('product_variants.stock_quantity <= product_variants.low_stock_threshold')
        ->where('product_variants.stock_quantity', '>', 0)
        ->count();

    $outOfStockDirect = DB::table('product_variants')
        ->join('products', 'product_variants.product_id', '=', 'products.id')
        ->where('products.store_id', $storeId)
        ->where('product_variants.stock_quantity', 0)
        ->count();

    echo "Low Stock (Direct): $lowStockDirect\n";
    echo "Out of Stock (Direct): $outOfStockDirect\n";
} else {
    echo "No store filter (viewing all stores)\n\n";

    $lowStockDirect = DB::table('product_variants')
        ->whereRaw('product_variants.stock_quantity <= product_variants.low_stock_threshold')
        ->where('product_variants.stock_quantity', '>', 0)
        ->count();

    $outOfStockDirect = DB::table('product_variants')
        ->where('product_variants.stock_quantity', 0)
        ->count();

    echo "Low Stock (Direct): $lowStockDirect\n";
    echo "Out of Stock (Direct): $outOfStockDirect\n";
}
