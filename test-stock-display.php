<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Product;
use App\Models\ProductVariant;

// Test pour l'utilisateur du Store 1 (Admin)
$admin = User::with('roles')->find(1);
Auth::login($admin);

echo '=== ADMIN (Store 1 - Ben Shop) ===' . PHP_EOL;
echo 'current_store_id: ' . ($admin->current_store_id ?? 'NULL') . PHP_EOL;
echo 'user_can_access_all_stores(): ' . (user_can_access_all_stores() ? 'true' : 'false') . PHP_EOL;
echo PHP_EOL;

$products = Product::with('variants')->take(3)->get();
foreach ($products as $product) {
    echo "Product: {$product->name}" . PHP_EOL;
    echo "  - total_stock (attribute): {$product->total_stock}" . PHP_EOL;
    echo "  - getStoreStock(1): {$product->getStoreStock(1)}" . PHP_EOL;
    echo "  - getStoreStock(2): {$product->getStoreStock(2)}" . PHP_EOL;
    
    foreach ($product->variants as $variant) {
        echo "  Variant {$variant->id}:" . PHP_EOL;
        echo "    - stock_quantity (global): {$variant->stock_quantity}" . PHP_EOL;
        echo "    - current_stock (attribute): {$variant->current_stock}" . PHP_EOL;
        echo "    - getStoreStock(1): {$variant->getStoreStock(1)}" . PHP_EOL;
        echo "    - getStoreStock(2): {$variant->getStoreStock(2)}" . PHP_EOL;
    }
    echo PHP_EOL;
}

// Test pour l'utilisateur du Store 2 (John Doe)
$user2 = User::with('roles')->find(2);
Auth::login($user2);

echo '=== JOHN DOE (Store 2 - Ben busness) ===' . PHP_EOL;
echo 'current_store_id: ' . ($user2->current_store_id ?? 'NULL') . PHP_EOL;
echo 'user_can_access_all_stores(): ' . (user_can_access_all_stores() ? 'true' : 'false') . PHP_EOL;
echo PHP_EOL;

$products = Product::with('variants')->take(3)->get();
foreach ($products as $product) {
    echo "Product: {$product->name}" . PHP_EOL;
    echo "  - total_stock (attribute): {$product->total_stock}" . PHP_EOL;
    
    foreach ($product->variants as $variant) {
        echo "  Variant {$variant->id}:" . PHP_EOL;
        echo "    - stock_quantity (global): {$variant->stock_quantity}" . PHP_EOL;
        echo "    - current_stock (attribute): {$variant->current_stock}" . PHP_EOL;
    }
    echo PHP_EOL;
}
