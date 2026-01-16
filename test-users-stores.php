<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\User;

echo "\n=== USERS AND THEIR STORES ===\n";

$users = User::with(['defaultOrganization', 'currentStore'])->get();

foreach ($users as $user) {
    echo "\n--- User ID: {$user->id} - {$user->name} ---\n";
    echo "Email: {$user->email}\n";
    echo "Default Org ID: " . ($user->default_organization_id ?? 'NULL') . "\n";
    echo "Default Org Name: " . ($user->defaultOrganization->name ?? 'N/A') . "\n";
    echo "Current Store ID: " . ($user->current_store_id ?? 'NULL') . "\n";
    echo "Current Store Name: " . ($user->currentStore->name ?? 'N/A') . "\n";
}

echo "\n=== STORES BY ORGANIZATION ===\n";

$stores = DB::table('stores')
    ->select('id', 'name', 'organization_id')
    ->orderBy('organization_id')
    ->get();

foreach ($stores as $store) {
    echo "Store ID: {$store->id} - {$store->name} (Org: {$store->organization_id})\n";
}

echo "\n=== PRODUCTS BY ORGANIZATION ===\n";

$products = DB::table('products')
    ->select('id', 'name', 'store_id', 'organization_id')
    ->orderBy('organization_id')
    ->get();

foreach ($products as $product) {
    echo "Product ID: {$product->id} - {$product->name} (Store: {$product->store_id}, Org: {$product->organization_id})\n";
}
