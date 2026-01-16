<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== MISE À JOUR DES UTILISATEURS SANS ORG PAR DÉFAUT ===" . PHP_EOL . PHP_EOL;

$usersWithoutDefault = DB::table('users')
    ->whereNull('default_organization_id')
    ->get(['id', 'name', 'email']);

foreach ($usersWithoutDefault as $user) {
    // Trouver la première organisation de l'utilisateur
    $orgId = DB::table('organization_user')
        ->where('user_id', $user->id)
        ->value('organization_id');

    if ($orgId) {
        DB::table('users')
            ->where('id', $user->id)
            ->update(['default_organization_id' => $orgId]);

        echo "✅ {$user->name} - défini sur Org #{$orgId}" . PHP_EOL;
    } else {
        echo "⚠️  {$user->name} - N'appartient à aucune organisation!" . PHP_EOL;
    }
}

echo PHP_EOL . "=== VÉRIFICATION FINALE ===" . PHP_EOL;
$total = DB::table('users')->count();
$withDefault = DB::table('users')->whereNotNull('default_organization_id')->count();

echo "Utilisateurs avec default_organization_id: {$withDefault}/{$total}" . PHP_EOL;

if ($withDefault < $total) {
    echo "⚠️  Il reste " . ($total - $withDefault) . " utilisateur(s) sans organisation par défaut!" . PHP_EOL;
} else {
    echo "✅ Tous les utilisateurs ont une organisation par défaut!" . PHP_EOL;
}
