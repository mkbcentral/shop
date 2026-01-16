<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n=== MIGRATION VERS ORGANISATION PAR DÉFAUT ===\n";

$defaultOrgId = 6; // Default Organization

echo "\n1. État actuel:\n";
$tables = [
    'products' => 'produits',
    'stores' => 'magasins',
    'categories' => 'catégories',
    'sales' => 'ventes',
];

// Vérifier les tables optionnelles
$optionalTables = [
    'expenses' => 'dépenses',
    'customers' => 'clients',
    'suppliers' => 'fournisseurs',
];

foreach ($optionalTables as $table => $label) {
    try {
        DB::table($table)->count();
        $tables[$table] = $label;
    } catch (\Exception $e) {
        echo "   ⚠️  Table '{$table}' ignorée (n'existe pas)\n";
    }
}

foreach ($tables as $table => $label) {
    $count = DB::table($table)->count();
    $withOrg = DB::table($table)->whereNotNull('organization_id')->count();
    echo "   - {$label}: {$count} enregistrements ({$withOrg} avec org_id)\n";
}

echo "\n2. Migration en cours...\n";

DB::beginTransaction();

try {
    $updated = [];

    foreach ($tables as $table => $label) {
        $count = DB::table($table)
            ->whereNotNull('organization_id')
            ->update(['organization_id' => $defaultOrgId]);
        $updated[$label] = $count;
        echo "   ✅ {$label}: {$count} enregistrements migrés\n";
    }

    // Migrer aussi users.default_organization_id
    $usersUpdated = DB::table('users')
        ->whereNotNull('default_organization_id')
        ->update(['default_organization_id' => $defaultOrgId]);
    echo "   ✅ utilisateurs: {$usersUpdated} default_organization_id mis à jour\n";

    DB::commit();

    echo "\n✅ Migration terminée avec succès!\n";

    echo "\n3. Vérification:\n";
    foreach ($tables as $table => $label) {
        $inDefaultOrg = DB::table($table)
            ->where('organization_id', $defaultOrgId)
            ->count();
        echo "   - {$label} dans org #{$defaultOrgId}: {$inDefaultOrg}\n";
    }

    $usersInDefaultOrg = DB::table('users')
        ->where('default_organization_id', $defaultOrgId)
        ->count();
    echo "   - utilisateurs avec default_org #{$defaultOrgId}: {$usersInDefaultOrg}\n";

} catch (\Exception $e) {
    DB::rollBack();
    echo "\n❌ Erreur: {$e->getMessage()}\n";
    echo "Transaction annulée.\n";
}
