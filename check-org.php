<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Organization;

$orgId = $argv[1] ?? null;

if (!$orgId) {
    echo "Usage: php check-org.php <organization_id>\n";
    exit(1);
}

echo "=== Vérification de l'organisation {$orgId} ===\n\n";

$org = Organization::find($orgId);

if (!$org) {
    echo "❌ Organisation {$orgId} INTROUVABLE\n";
    exit(1);
}

echo "✅ Organisation trouvée\n";
echo "ID: {$org->id}\n";
echo "Nom: {$org->name}\n";
echo "Propriétaire ID: {$org->owner_id}\n";
echo "Plan: {$org->subscription_plan->value}\n";
echo "Statut paiement: {$org->payment_status->value}\n";
echo "Active: " . ($org->is_active ? 'Oui' : 'Non') . "\n";
echo "Vérifié: " . ($org->is_verified ? 'Oui' : 'Non') . "\n";
