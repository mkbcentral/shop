<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::first();
echo "User: {$user->name}" . PHP_EOL;
echo "current_store_id: " . ($user->current_store_id ?? 'NULL') . PHP_EOL;

$stores = $user->stores;
echo "Stores de l'utilisateur: " . $stores->pluck('id')->join(', ') . PHP_EOL;

echo PHP_EOL . "=== Tous les magasins ===" . PHP_EOL;
$allStores = App\Models\Store::all();
foreach($allStores as $store) {
    echo "Store ID: {$store->id} | Name: {$store->name}" . PHP_EOL;
}
