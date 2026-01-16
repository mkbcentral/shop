<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\User;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;

$userId = $argv[1] ?? 12;
$orgId = $argv[2] ?? 11;

echo "=== TEST D'ACCÈS À ORGANIZATION PAYMENT ===\n\n";

// Simuler l'authentification
$user = User::find($userId);
if (!$user) {
    echo "❌ Utilisateur {$userId} introuvable\n";
    exit(1);
}

Auth::login($user);
echo "✅ Authentifié comme: {$user->name} (ID: {$user->id})\n\n";

// Vérifier l'organisation
$org = Organization::find($orgId);
if (!$org) {
    echo "❌ Organisation {$orgId} introuvable\n";
    exit(1);
}

echo "=== Organisation ===\n";
echo "ID: {$org->id}\n";
echo "Nom: {$org->name}\n";
echo "Propriétaire: {$org->owner_id}\n";
echo "Plan: {$org->subscription_plan->value}\n";
echo "Payment Status: {$org->payment_status->value}\n";
echo "Active: " . ($org->is_active ? 'Yes' : 'No') . "\n\n";

// Vérifier l'accès
echo "=== Vérifications d'accès ===\n";

if ($org->owner_id !== $user->id) {
    echo "❌ L'utilisateur N'EST PAS le propriétaire (abort 403)\n";
    exit(1);
} else {
    echo "✅ L'utilisateur EST le propriétaire\n";
}

if ($org->isAccessible()) {
    echo "⚠️  Organisation déjà accessible (redirige vers dashboard)\n";
    exit(0);
} else {
    echo "✅ Organisation nécessite un paiement\n";
}

// Vérifier les plans
echo "\n=== Chargement des données de plan ===\n";
$allPlans = App\Services\SubscriptionService::getPlansFromCache();
$planSlug = $org->subscription_plan->value;
$planData = $allPlans[$planSlug] ?? [];

if (empty($planData)) {
    echo "❌ Données de plan introuvables pour '{$planSlug}'\n";
    echo "Plans disponibles: " . implode(', ', array_keys($allPlans)) . "\n";
    exit(1);
} else {
    echo "✅ Plan trouvé: {$planData['name']}\n";
    echo "   Prix: {$planData['price']} €\n";
}

echo "\n✅ TOUT EST OK - La page devrait s'afficher correctement\n";
