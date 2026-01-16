<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== ANALYSE DES UTILISATEURS ET ORGANISATIONS ===" . PHP_EOL . PHP_EOL;

// Utilisateurs
$users = DB::table('users')->get(['id', 'name', 'email', 'default_organization_id']);

foreach ($users as $user) {
    echo "👤 {$user->name} ({$user->email})" . PHP_EOL;
    echo "   Default Org ID: " . ($user->default_organization_id ?? 'NULL') . PHP_EOL;

    // Ses organisations
    $orgs = DB::table('organization_user')
        ->join('organizations', 'organization_user.organization_id', '=', 'organizations.id')
        ->where('organization_user.user_id', $user->id)
        ->select('organizations.id', 'organizations.name', 'organization_user.role')
        ->get();

    if ($orgs->count() > 0) {
        echo "   Membre de:" . PHP_EOL;
        foreach ($orgs as $org) {
            echo "      - Org #{$org->id}: {$org->name} (rôle: {$org->role})" . PHP_EOL;
        }
    } else {
        echo "   ⚠️  N'appartient à aucune organisation!" . PHP_EOL;
    }

    echo PHP_EOL;
}

// Statistiques globales
echo "=== STATISTIQUES GLOBALES ===" . PHP_EOL;
$totalUsers = DB::table('users')->count();
$usersWithDefaultOrg = DB::table('users')->whereNotNull('default_organization_id')->count();
$usersInOrgs = DB::table('organization_user')->distinct('user_id')->count('user_id');

echo "Total utilisateurs: {$totalUsers}" . PHP_EOL;
echo "Avec organisation par défaut: {$usersWithDefaultOrg}" . PHP_EOL;
echo "Membres d'au moins une organisation: {$usersInOrgs}" . PHP_EOL;

if ($totalUsers > $usersInOrgs) {
    $orphans = $totalUsers - $usersInOrgs;
    echo "⚠️  {$orphans} utilisateur(s) orphelin(s) (sans organisation)!" . PHP_EOL;
}

// Vérifier les invitations
echo PHP_EOL . "=== INVITATIONS ===" . PHP_EOL;
$pendingInvitations = DB::table('organization_invitations')
    ->whereNull('accepted_at')
    ->where('expires_at', '>', now())
    ->count();

$acceptedInvitations = DB::table('organization_invitations')
    ->whereNotNull('accepted_at')
    ->count();

$expiredInvitations = DB::table('organization_invitations')
    ->whereNull('accepted_at')
    ->where('expires_at', '<=', now())
    ->count();

echo "Invitations en attente: {$pendingInvitations}" . PHP_EOL;
echo "Invitations acceptées: {$acceptedInvitations}" . PHP_EOL;
echo "Invitations expirées: {$expiredInvitations}" . PHP_EOL;

// Vérifier les stores et leurs organisations
echo PHP_EOL . "=== MAGASINS PAR ORGANISATION ===" . PHP_EOL;
$orgsWithStores = DB::table('organizations')
    ->leftJoin('stores', 'organizations.id', '=', 'stores.organization_id')
    ->select('organizations.id', 'organizations.name', DB::raw('COUNT(stores.id) as store_count'))
    ->groupBy('organizations.id', 'organizations.name')
    ->get();

foreach ($orgsWithStores as $org) {
    echo "Org #{$org->id} ({$org->name}): {$org->store_count} magasin(s)" . PHP_EOL;
}

// Vérifier l'intégrité des données
echo PHP_EOL . "=== VÉRIFICATIONS D'INTÉGRITÉ ===" . PHP_EOL;

// Products sans store valide
$productsWithInvalidStore = DB::table('products as p')
    ->leftJoin('stores as s', function($join) {
        $join->on('p.store_id', '=', 's.id')
             ->on('p.organization_id', '=', 's.organization_id');
    })
    ->whereNotNull('p.store_id')
    ->whereNull('s.id')
    ->count();

if ($productsWithInvalidStore > 0) {
    echo "❌ {$productsWithInvalidStore} produits avec store_id d'une autre organisation!" . PHP_EOL;
} else {
    echo "✅ Tous les produits ont des stores valides" . PHP_EOL;
}

// Sales sans store valide
$salesWithInvalidStore = DB::table('sales as s')
    ->leftJoin('stores as st', function($join) {
        $join->on('s.store_id', '=', 'st.id')
             ->on('s.organization_id', '=', 'st.organization_id');
    })
    ->whereNotNull('s.store_id')
    ->whereNull('st.id')
    ->count();

if ($salesWithInvalidStore > 0) {
    echo "❌ {$salesWithInvalidStore} ventes avec store_id d'une autre organisation!" . PHP_EOL;
} else {
    echo "✅ Toutes les ventes ont des stores valides" . PHP_EOL;
}

// Check organization limits
echo PHP_EOL . "=== LIMITES DES ORGANISATIONS ===" . PHP_EOL;
$orgs = DB::table('organizations')->get(['id', 'name', 'max_users', 'max_stores', 'max_products']);

foreach ($orgs as $org) {
    $userCount = DB::table('organization_user')->where('organization_id', $org->id)->count();
    $storeCount = DB::table('stores')->where('organization_id', $org->id)->count();
    $productCount = DB::table('products')->where('organization_id', $org->id)->count();

    echo "Org #{$org->id} ({$org->name}):" . PHP_EOL;
    echo "   Utilisateurs: {$userCount}/{$org->max_users}";
    if ($userCount >= $org->max_users) echo " ⚠️  LIMITE ATTEINTE";
    echo PHP_EOL;

    echo "   Magasins: {$storeCount}/{$org->max_stores}";
    if ($storeCount >= $org->max_stores) echo " ⚠️  LIMITE ATTEINTE";
    echo PHP_EOL;

    echo "   Produits: {$productCount}/{$org->max_products}";
    if ($productCount >= $org->max_products) echo " ⚠️  LIMITE ATTEINTE";
    echo PHP_EOL . PHP_EOL;
}
