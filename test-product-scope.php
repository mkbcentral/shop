<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST DU SCOPE ORGANIZATION ===" . PHP_EOL . PHP_EOL;

// Sans organisation
echo "1. Sans organization dans le conteneur:" . PHP_EOL;
try {
    $products = App\Models\Product::all();
    echo "   ✅ Produits trouvés: " . $products->count() . PHP_EOL;
} catch (\Exception $e) {
    echo "   ❌ Erreur: " . $e->getMessage() . PHP_EOL;
}

// Avec une organisation
echo PHP_EOL . "2. Avec organization #1 dans le conteneur:" . PHP_EOL;
$org = App\Models\Organization::find(1);
if ($org) {
    app()->instance('current_organization', $org);
    $products = App\Models\Product::all();
    echo "   ✅ Produits trouvés: " . $products->count() . PHP_EOL;
    echo "   Organisation active: {$org->name}" . PHP_EOL;
} else {
    echo "   ❌ Organisation #1 introuvable" . PHP_EOL;
}

// Sans scope
echo PHP_EOL . "3. Sans scope (withoutGlobalScope):" . PHP_EOL;
$allProducts = App\Models\Product::withoutGlobalScope('organization')->get();
echo "   ✅ Total produits en BDD: " . $allProducts->count() . PHP_EOL;

// Vérifier organization_id de chaque produit
echo PHP_EOL . "4. Organization_id des produits:" . PHP_EOL;
$grouped = $allProducts->groupBy('organization_id');
foreach ($grouped as $orgId => $prods) {
    $orgName = $orgId ? App\Models\Organization::find($orgId)?->name : 'NULL';
    echo "   - Org #{$orgId} ({$orgName}): {$prods->count()} produit(s)" . PHP_EOL;
}
