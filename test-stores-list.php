<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Store;
use App\Models\User;

echo "=== Test des Stores disponibles ===\n\n";

// Get first user
$user = User::first();
if ($user) {
    echo "User ID: {$user->id}\n";
    echo "User Name: {$user->name}\n";
    echo "Current Store ID: {$user->current_store_id}\n";
    echo "Default Organization ID: {$user->default_organization_id}\n\n";
}

// Total stores
$totalStores = Store::count();
echo "Total stores in database: {$totalStores}\n\n";

// Active stores
$activeStores = Store::active()->get();
echo "Active stores count: {$activeStores->count()}\n";
foreach ($activeStores as $store) {
    echo "  - Store {$store->id}: {$store->name} (is_active: {$store->is_active}, organization_id: {$store->organization_id})\n";
}
echo "\n";

// Check query without any scope
$allStores = Store::all();
echo "All stores (without scope):\n";
foreach ($allStores as $store) {
    echo "  - Store {$store->id}: {$store->name} (is_active: " . ($store->is_active ? 'true' : 'false') . ", organization_id: {$store->organization_id})\n";
}

echo "\n=== Test terminé ===\n";
