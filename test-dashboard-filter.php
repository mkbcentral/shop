<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Simulate authenticated user
$user = \App\Models\User::first();
auth()->login($user);

// Set current store
session(['current_store_id' => 1]);

echo "=== Test du filtrage Dashboard ===" . PHP_EOL . PHP_EOL;

$repo = new \App\Repositories\DashboardRepository();

echo "Store ID: " . session('current_store_id') . PHP_EOL;
echo "Current Store ID: " . current_store_id() . PHP_EOL;
echo "User can access all stores: " . (user_can_access_all_stores() ? 'Yes' : 'No') . PHP_EOL . PHP_EOL;

echo "Total Products: " . $repo->getTotalProducts() . PHP_EOL;
echo "Total Sales Count: " . $repo->getTotalSalesCount() . PHP_EOL;
echo "Today Sales: " . number_format($repo->getTodaySales(), 2) . PHP_EOL;
echo "Month Sales: " . number_format($repo->getMonthSales(now()), 2) . PHP_EOL;
echo "Low Stock Count: " . $repo->getLowStockCount() . PHP_EOL;
echo "Out of Stock Count: " . $repo->getOutOfStockCount() . PHP_EOL;

echo PHP_EOL . "=== Test terminé ===" . PHP_EOL;
