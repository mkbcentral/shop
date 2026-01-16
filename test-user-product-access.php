<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Product;
use App\Models\Organization;

echo "\n=== TEST PRODUCT ACCESS BY USER ===\n";

$users = User::with(['defaultOrganization', 'currentStore'])->get();

foreach ($users as $user) {
    echo "\n--- User #{$user->id}: {$user->name} ---\n";
    echo "Default Org: {$user->default_organization_id} (" . ($user->defaultOrganization->name ?? 'N/A') . ")\n";
    echo "Current Store: {$user->current_store_id} (" . ($user->currentStore->name ?? 'N/A') . ")\n";

    // Simuler l'authentification
    auth()->login($user);

    // Définir l'organisation courante
    if ($user->default_organization_id) {
        $org = Organization::find($user->default_organization_id);
        app()->instance('current_organization', $org);
        echo "Organization active: {$org->name}\n";
    } else {
        echo "❌ Pas d'organization par défaut\n";
    }

    // Tester la récupération des produits
    try {
        $products = Product::get();
        echo "✅ Produits visibles: {$products->count()}\n";

        if ($products->count() > 0) {
            echo "   Exemples:\n";
            foreach ($products->take(3) as $product) {
                echo "   - {$product->name} (Store: {$product->store_id}, Org: {$product->organization_id})\n";
            }
        }
    } catch (\Exception $e) {
        echo "❌ Erreur: {$e->getMessage()}\n";
    }

    // Déconnexion
    auth()->logout();
    app()->offsetUnset('current_organization');
}

echo "\n=== RÉSUMÉ ===\n";
echo "Utilisateurs sans current_store_id:\n";
$noStore = User::whereNull('current_store_id')->get();
foreach ($noStore as $user) {
    echo "- {$user->name} (Org: {$user->default_organization_id})\n";
}

echo "\nUtilisateurs avec current_store_id:\n";
$withStore = User::whereNotNull('current_store_id')->get();
foreach ($withStore as $user) {
    $store = $user->currentStore;
    echo "- {$user->name} → Store #{$store->id} ({$store->name}) Org: {$store->organization_id}\n";
}
