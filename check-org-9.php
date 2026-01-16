<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Organization;

echo "=== Vérification de l'organisation 9 ===\n\n";

$org = Organization::find(9);

if (!$org) {
    echo "❌ Organisation 9 introuvable\n";
    exit(1);
}

echo "Organisation: {$org->name}\n";
echo "Propriétaire ID: {$org->owner_id}\n";
echo "Plan: {$org->subscription_plan->value}\n";
echo "Statut de paiement: {$org->payment_status->value}\n";
echo "Active: " . ($org->is_active ? 'Oui' : 'Non') . "\n";
echo "Vérifié: " . ($org->is_verified ? 'Oui' : 'Non') . "\n";

echo "\n=== Problème détecté ===\n";

if ($org->payment_status->value === 'pending' && !$org->is_active) {
    echo "⚠️  L'organisation a un paiement en attente et n'est pas active\n";
    echo "C'est pourquoi le middleware redirige vers la page de paiement\n\n";

    // Vérifier le plan
    $planSlug = $org->subscription_plan->value;
    echo "Plan actuel: $planSlug\n";

    if ($planSlug === 'free') {
        echo "\n=== Correction recommandée ===\n";
        echo "Le plan est GRATUIT mais l'organisation n'est pas active.\n";
        echo "Voulez-vous activer cette organisation ? (o/n): ";

        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        $response = trim($line);
        fclose($handle);

        if (strtolower($response) === 'o' || strtolower($response) === 'oui') {
            $org->update([
                'is_active' => true,
                'payment_status' => \App\Enums\PaymentStatus::COMPLETED
            ]);
            echo "✅ Organisation activée avec succès!\n";
        } else {
            echo "❌ Activation annulée\n";
        }
    } else {
        echo "\n⚠️  Le plan est payant ($planSlug). L'utilisateur doit compléter le paiement.\n";
    }
}
