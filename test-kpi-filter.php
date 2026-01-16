<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Simulate authenticated user
$user = \App\Models\User::first();
auth()->login($user);

// Set current store
session(['current_store_id' => 2]);

echo "=== Test du filtrage des KPIs ===" . PHP_EOL . PHP_EOL;

echo "Store ID: " . session('current_store_id') . PHP_EOL;
echo "Current Store ID: " . current_store_id() . PHP_EOL;
echo "User can access all stores: " . (user_can_access_all_stores() ? 'Yes' : 'No') . PHP_EOL . PHP_EOL;

// Test ProductKPIService
echo "--- ProductKPIService ---" . PHP_EOL;
$productKPI = new \App\Services\ProductKPIService();
$kpis = $productKPI->calculateAllKPIs();
foreach ($kpis as $key => $value) {
    echo "$key: $value" . PHP_EOL;
}

// Test StockOverviewService
echo PHP_EOL . "--- StockOverviewService ---" . PHP_EOL;
$stockRepo = app(\App\Repositories\ProductVariantRepository::class);
$categoryRepo = app(\App\Repositories\CategoryRepository::class);
$stockOverview = new \App\Services\StockOverviewService($stockRepo, $categoryRepo);
$stockKPIs = $stockOverview->calculateKPIs();
foreach ($stockKPIs as $key => $value) {
    echo "$key: $value" . PHP_EOL;
}

// Test StockAlertService
echo PHP_EOL . "--- StockAlertService ---" . PHP_EOL;
$stockAlert = new \App\Services\StockAlertService($stockRepo);
$lowStock = $stockAlert->getLowStockVariants();
$outOfStock = $stockAlert->getOutOfStockVariants();
echo "Low stock variants: " . $lowStock->count() . PHP_EOL;
echo "Out of stock variants: " . $outOfStock->count() . PHP_EOL;

echo PHP_EOL . "=== Test terminé ===" . PHP_EOL;
