<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Organization;
use App\Enums\SubscriptionPlan;
use App\Enums\PaymentStatus;

$orgId = $argv[1] ?? null;

if (!$orgId) {
    echo "Usage: php activate-organization.php <organization_id>\n";
    echo "Exemple: php activate-organization.php 9\n";
    exit(1);
}

echo "=== Activation de l'organisation $orgId ===\n\n";

$org = Organization::find($orgId);

if (!$org) {
    echo "❌ Organisation $orgId introuvable\n";
    exit(1);
}

echo "Organisation: {$org->name}\n";
echo "Plan actuel: {$org->subscription_plan->value}\n";
echo "Statut: " . ($org->is_active ? 'Active' : 'Inactive') . "\n";
echo "Paiement: {$org->payment_status->value}\n\n";

echo "Choisissez une action:\n";
echo "1. Activer avec le plan GRATUIT (Free)\n";
echo "2. Marquer le paiement comme COMPLÉTÉ (garder le plan actuel)\n";
echo "3. Annuler\n\n";
echo "Votre choix (1/2/3): ";

$handle = fopen("php://stdin", "r");
$line = fgets($handle);
$choice = trim($line);
fclose($handle);

switch ($choice) {
    case '1':
        $org->update([
            'subscription_plan' => SubscriptionPlan::FREE,
            'payment_status' => PaymentStatus::COMPLETED,
            'is_active' => true,
        ]);
        echo "\n✅ Organisation activée avec le plan GRATUIT!\n";
        break;

    case '2':
        $org->update([
            'payment_status' => PaymentStatus::COMPLETED,
            'is_active' => true,
        ]);
        echo "\n✅ Paiement marqué comme complété! Organisation active avec le plan {$org->subscription_plan->value}.\n";
        break;

    case '3':
        echo "\n❌ Annulé\n";
        break;

    default:
        echo "\n❌ Choix invalide\n";
}
