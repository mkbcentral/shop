<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== ANALYSE DES DONNÉES PAR ORGANISATION ===" . PHP_EOL . PHP_EOL;

$tables = [
    'products' => 'Produits',
    'categories' => 'Catégories',
    'clients' => 'Clients',
    'suppliers' => 'Fournisseurs',
    'sales' => 'Ventes',
    'purchases' => 'Achats',
    'invoices' => 'Factures',
    'payments' => 'Paiements',
    'stock_movements' => 'Mouvements de stock',
    'product_variants' => 'Variantes produits',
    'store_transfers' => 'Transferts magasins'
];

foreach ($tables as $table => $label) {
    $total = DB::table($table)->count();
    $withOrg = DB::table($table)->whereNotNull('organization_id')->count();
    $withoutOrg = $total - $withOrg;

    $status = $withoutOrg > 0 ? '❌' : '✅';
    echo sprintf('%s %-25s: %2d/%2d avec org_id', $status, $label, $withOrg, $total);
    if ($withoutOrg > 0) echo ' ⚠️  ' . $withoutOrg . ' sans org!';
    echo PHP_EOL;
}

echo PHP_EOL . "=== RÉPARTITION PAR ORGANISATION ===" . PHP_EOL;
$orgs = DB::table('organizations')->get(['id', 'name']);
foreach ($orgs as $org) {
    echo PHP_EOL . "📊 Organisation #{$org->id}: {$org->name}" . PHP_EOL;

    $counts = [
        'products' => DB::table('products')->where('organization_id', $org->id)->count(),
        'categories' => DB::table('categories')->where('organization_id', $org->id)->count(),
        'sales' => DB::table('sales')->where('organization_id', $org->id)->count(),
        'clients' => DB::table('clients')->where('organization_id', $org->id)->count(),
    ];

    foreach ($counts as $type => $count) {
        if ($count > 0) {
            echo "   - " . ucfirst($type) . ": {$count}" . PHP_EOL;
        }
    }
}

echo PHP_EOL . "=== VÉRIFICATION DES RELATIONS ===" . PHP_EOL;

// Vérifier les produits sans catégorie valide
$invalidProducts = DB::table('products as p')
    ->leftJoin('categories as c', function($join) {
        $join->on('p.category_id', '=', 'c.id')
             ->on('p.organization_id', '=', 'c.organization_id');
    })
    ->whereNotNull('p.category_id')
    ->whereNull('c.id')
    ->count();

if ($invalidProducts > 0) {
    echo "❌ {$invalidProducts} produits avec des catégories d'autres organisations!" . PHP_EOL;
} else {
    echo "✅ Tous les produits ont des catégories valides (même organisation)" . PHP_EOL;
}

// Vérifier les ventes sans client valide
$invalidSales = DB::table('sales as s')
    ->leftJoin('clients as c', function($join) {
        $join->on('s.client_id', '=', 'c.id')
             ->on('s.organization_id', '=', 'c.organization_id');
    })
    ->whereNotNull('s.client_id')
    ->whereNull('c.id')
    ->count();

if ($invalidSales > 0) {
    echo "❌ {$invalidSales} ventes avec des clients d'autres organisations!" . PHP_EOL;
} else {
    echo "✅ Toutes les ventes ont des clients valides (même organisation)" . PHP_EOL;
}

echo PHP_EOL . "=== STORES ===" . PHP_EOL;
$storesWithOrg = DB::table('stores')->whereNotNull('organization_id')->count();
$totalStores = DB::table('stores')->count();
echo "Magasins: {$storesWithOrg}/{$totalStores} avec organization_id" . PHP_EOL;

if ($storesWithOrg < $totalStores) {
    echo "⚠️  Il y a des magasins sans organisation!" . PHP_EOL;
}
