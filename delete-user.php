<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

$email = 'jameswembo@gmail.com';

echo "=== Suppression de l'utilisateur $email ===\n\n";

DB::beginTransaction();
try {
    $user = User::where('email', $email)->first();

    if (!$user) {
        echo "❌ Utilisateur non trouvé\n";
        DB::rollBack();
        exit(1);
    }

    echo "✅ Utilisateur trouvé (ID: {$user->id})\n";

    // Supprimer les relations
    $orgs = $user->organizations()->count();
    $stores = $user->stores()->count();

    echo "📋 Suppression de {$orgs} organisation(s)\n";
    echo "🏪 Détachement de {$stores} magasin(s)\n";

    // Détacher des magasins
    $user->stores()->detach();

    // Détacher des organisations et supprimer les organisations dont il est propriétaire
    foreach ($user->organizations as $org) {
        if ($org->owner_id == $user->id) {
            echo "   - Suppression de l'organisation: {$org->name}\n";

            // Supprimer les magasins de l'organisation
            \App\Models\Store::where('organization_id', $org->id)->delete();

            // Détacher tous les membres
            $org->members()->detach();

            // Supprimer l'organisation
            $org->delete();
        } else {
            // Simplement détacher l'utilisateur
            $org->members()->detach($user->id);
        }
    }

    // Supprimer l'utilisateur
    $user->delete();

    DB::commit();
    echo "\n✅ Utilisateur supprimé avec succès!\n";
    echo "Vous pouvez maintenant créer un nouveau compte.\n";

} catch (\Exception $e) {
    DB::rollBack();
    echo "\n❌ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
