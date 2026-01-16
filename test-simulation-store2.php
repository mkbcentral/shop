<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Services\ProductKPIService;

// Simuler l'utilisateur John Doe (store 2)
$user = User::with('roles')->find(2);
Auth::login($user);

echo '=== Simulation de connexion pour: ' . $user->name . ' ===' . PHP_EOL;
echo 'current_store_id: ' . ($user->current_store_id ?? 'NULL') . PHP_EOL;
echo 'user_can_access_all_stores(): ' . (user_can_access_all_stores() ? 'true' : 'false') . PHP_EOL;

// Test du ProductRepository
echo PHP_EOL . '=== Test ProductRepository ===' . PHP_EOL;
$repo = new ProductRepository();

// count() method
$count = $repo->count();
echo "ProductRepository->count(): {$count}" . PHP_EOL;

// paginate() method
$paginated = $repo->paginate(15);
echo "ProductRepository->paginate(15)->total(): {$paginated->total()}" . PHP_EOL;

// paginateWithFilters() method
$filtered = $repo->paginateWithFilters(15);
echo "ProductRepository->paginateWithFilters(15)->total(): {$filtered->total()}" . PHP_EOL;

// Afficher les produits retournés
echo PHP_EOL . '=== Produits retournés par paginateWithFilters ===' . PHP_EOL;
foreach ($filtered as $product) {
    echo "- {$product->id}: {$product->name} (store_id: {$product->store_id})" . PHP_EOL;
}

// Test du ProductKPIService
echo PHP_EOL . '=== Test ProductKPIService ===' . PHP_EOL;
$kpiService = new ProductKPIService();
$kpis = $kpiService->calculateAllKPIs();
print_r($kpis);
