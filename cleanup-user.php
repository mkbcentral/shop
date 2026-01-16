<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$email = 'jameswembo@gmail.com';

echo "=== Nettoyage de l'utilisateur $email ===\n\n";

DB::statement('SET FOREIGN_KEY_CHECKS=0;');

try {
    $user = \App\Models\User::where('email', $email)->first();

    if (!$user) {
        echo "❌ Utilisateur non trouvé\n";
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        exit(0);
    }

    echo "✅ Utilisateur trouvé (ID: {$user->id})\n";

    // Récupérer les IDs des organisations
    $orgIds = $user->organizations()->pluck('organizations.id')->toArray();
    echo "📋 Organisations à supprimer: " . implode(', ', $orgIds) . "\n";

    // Supprimer les magasins
    if (!empty($orgIds)) {
        $storesDeleted = \App\Models\Store::whereIn('organization_id', $orgIds)->delete();
        echo "🏪 Magasins supprimés: $storesDeleted\n";
    }

    // Détacher les relations
    DB::table('organization_user')->where('user_id', $user->id)->delete();
    DB::table('store_user')->where('user_id', $user->id)->delete();

    // Supprimer les organisations
    if (!empty($orgIds)) {
        $orgsDeleted = \App\Models\Organization::whereIn('id', $orgIds)->delete();
        echo "📋 Organisations supprimées: $orgsDeleted\n";
    }

    // Supprimer l'utilisateur
    $user->delete();
    echo "✅ Utilisateur supprimé avec succès!\n";
    echo "\nVous pouvez maintenant créer un nouveau compte.\n";

} catch (\Exception $e) {
    echo "\n❌ Erreur: " . $e->getMessage() . "\n";
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    exit(1);
}

DB::statement('SET FOREIGN_KEY_CHECKS=1;');
