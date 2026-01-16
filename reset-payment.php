<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Organization;
use App\Enums\PaymentStatus;

$org = Organization::find(1);

if (!$org) {
    echo "Organisation non trouvée!\n";
    exit(1);
}

echo "Organisation: {$org->name}\n";
echo "Statut actuel: {$org->payment_status->value}\n";

$org->update([
    'payment_status' => PaymentStatus::PENDING,
    'payment_reference' => null,
    'payment_method' => null,
    'payment_completed_at' => null,
    'is_active' => false,
]);

echo "\n✅ Paiement réinitialisé à PENDING\n";
echo "Nouveau statut: {$org->fresh()->payment_status->value}\n";
