<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Organization;

echo "=== Nettoyage des organisations orphelines ===\n\n";

// Trouver tous les utilisateurs avec des organisations invalides
$users = User::whereNotNull('default_organization_id')->get();

foreach ($users as $user) {
    echo "Vérification utilisateur: {$user->email} (ID: {$user->id})\n";

    // Vérifier si l'organisation par défaut existe
    $defaultOrg = Organization::find($user->default_organization_id);

    if (!$defaultOrg) {
        echo "  ⚠️  Organisation par défaut {$user->default_organization_id} introuvable\n";

        // Trouver une organisation valide pour cet utilisateur
        $validOrg = $user->organizations()->first();

        if ($validOrg) {
            $user->update(['default_organization_id' => $validOrg->id]);
            echo "  ✅ Mise à jour avec l'organisation {$validOrg->id} ({$validOrg->name})\n";
        } else {
            $user->update(['default_organization_id' => null]);
            echo "  ⚠️  Aucune organisation valide trouvée, default_organization_id défini à null\n";
        }
    } else {
        // Vérifier si l'utilisateur est membre de cette organisation
        $isMember = $user->organizations()->where('organizations.id', $defaultOrg->id)->exists();

        if (!$isMember) {
            echo "  ⚠️  Utilisateur n'est pas membre de son organisation par défaut\n";

            // Trouver une organisation dont il est membre
            $validOrg = $user->organizations()->first();

            if ($validOrg) {
                $user->update(['default_organization_id' => $validOrg->id]);
                echo "  ✅ Mise à jour avec l'organisation {$validOrg->id} ({$validOrg->name})\n";
            } else {
                $user->update(['default_organization_id' => null]);
                echo "  ⚠️  Aucune organisation valide trouvée\n";
            }
        } else {
            echo "  ✅ OK - Organisation {$defaultOrg->id} ({$defaultOrg->name})\n";
        }
    }
}

echo "\n=== Nettoyage terminé ===\n";
