<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\SubscriptionService;

echo "=== Plans en cache ===\n\n";
$plans = SubscriptionService::getPlansFromCache();

foreach ($plans as $slug => $plan) {
    echo "Plan: {$slug}\n";
    echo "  - Nom: " . ($plan['name'] ?? 'N/A') . "\n";
    echo "  - Prix: " . ($plan['price'] ?? 0) . "\n";
    echo "  - Max Stores: " . ($plan['max_stores'] ?? 'N/A') . "\n";
    echo "  - Max Users: " . ($plan['max_users'] ?? 'N/A') . "\n";
    echo "  - Max Products: " . ($plan['max_products'] ?? 'N/A') . "\n";
    echo "\n";
}

echo "=== Devise ===\n";
echo SubscriptionService::getCurrencyFromCache() . "\n";
