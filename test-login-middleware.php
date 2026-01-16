<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Enums\PaymentStatus;

$email = $argv[1] ?? 'test@example.com';

echo "=== TEST DE CONNEXION POUR: {$email} ===\n\n";

$user = User::where('email', $email)->first();

if (!$user) {
    echo "❌ Utilisateur non trouvé\n";
    exit(1);
}

echo "User ID: {$user->id}\n";
echo "Nom: {$user->name}\n";
echo "Email: {$user->email}\n";
echo "Default Organization ID: " . ($user->default_organization_id ?? 'NULL') . "\n\n";

if ($user->default_organization_id) {
    $org = $user->defaultOrganization;

    if ($org) {
        echo "=== Organisation par défaut ===\n";
        echo "ID: {$org->id}\n";
        echo "Nom: {$org->name}\n";
        echo "Propriétaire ID: {$org->owner_id}\n";
        echo "Plan: {$org->subscription_plan->value}\n";
        echo "Statut paiement: {$org->payment_status->value}\n";
        echo "Active: " . ($org->is_active ? 'Oui' : 'Non') . "\n\n";

        // Vérifier si le middleware va rediriger
        echo "=== Vérification Middleware ===\n";

        if ($org->payment_status === PaymentStatus::PENDING && !$org->is_active) {
            echo "⚠️  Organisation a un paiement en attente ET n'est pas active\n";

            if ($org->owner_id === $user->id) {
                echo "⚠️  L'utilisateur EST le propriétaire\n";
                echo "🔴 REDIRECTION vers /organization/{$org->id}/payment\n";
            } else {
                echo "✅ L'utilisateur N'EST PAS le propriétaire\n";
                echo "✅ PAS de redirection\n";
            }
        } else {
            echo "✅ Organisation active OU paiement complété\n";
            echo "✅ PAS de redirection\n";
        }

        // Vérifier les stores
        echo "\n=== Stores accessibles ===\n";
        $stores = $user->stores;
        if ($stores->count() > 0) {
            foreach ($stores as $store) {
                echo "  - Store ID: {$store->id}, Nom: {$store->name}\n";
            }
        } else {
            echo "  ❌ Aucun store accessible\n";
        }
    } else {
        echo "❌ Organisation par défaut invalide (ID {$user->default_organization_id} n'existe pas)\n";
    }
} else {
    echo "⚠️  Aucune organisation par défaut\n";
}

echo "\n=== Organisations de l'utilisateur ===\n";
$orgs = $user->organizations;
if ($orgs->count() > 0) {
    foreach ($orgs as $org) {
        $isOwner = ($org->owner_id === $user->id) ? '👑 OWNER' : '👤 Member';
        $status = $org->is_active ? '✅' : '❌';
        echo "  - Org ID: {$org->id}, Nom: {$org->name}, {$isOwner}, Active: {$status}, Plan: {$org->subscription_plan->value}\n";
    }
} else {
    echo "  ❌ Aucune organisation\n";
}
