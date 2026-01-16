<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

$userId = $argv[1] ?? null;

if (!$userId) {
    echo "Usage: php check-user.php <user_id>\n";
    exit(1);
}

echo "=== Vérification de l'utilisateur {$userId} ===\n\n";

$user = User::with(['organizations', 'stores'])->find($userId);

if (!$user) {
    echo "❌ Utilisateur {$userId} INTROUVABLE\n";
    exit(1);
}

echo "✅ Utilisateur trouvé\n";
echo "ID: {$user->id}\n";
echo "Nom: {$user->name}\n";
echo "Email: {$user->email}\n";
echo "Default Org ID: " . ($user->default_organization_id ?? 'NULL') . "\n";
echo "Current Store ID: " . ($user->current_store_id ?? 'NULL') . "\n\n";

echo "=== Organisations ===\n";
if ($user->organizations->count() > 0) {
    foreach ($user->organizations as $org) {
        $isOwner = ($org->owner_id === $user->id) ? '👑 OWNER' : '👤 Member';
        echo "  - Org #{$org->id}: {$org->name} ({$isOwner})\n";
    }
} else {
    echo "  ❌ Aucune organisation\n";
}

echo "\n=== Stores ===\n";
if ($user->stores->count() > 0) {
    foreach ($user->stores as $store) {
        echo "  - Store #{$store->id}: {$store->name}\n";
    }
} else {
    echo "  ❌ Aucun store\n";
}
