<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Organization;
use App\Models\Store;
use Illuminate\Support\Facades\DB;

$email = 'jameswembo@gmail.com';

echo "=== Analyse de l'utilisateur $email ===\n\n";

$user = User::where('email', $email)->first();

if (!$user) {
    echo "❌ Utilisateur non trouvé\n";
    exit(1);
}

echo "✅ Utilisateur trouvé (ID: {$user->id})\n";
echo "   - Nom: {$user->name}\n";
echo "   - Default Org ID: " . ($user->default_organization_id ?? 'NULL') . "\n";
echo "   - Current Store ID: " . ($user->current_store_id ?? 'NULL') . "\n\n";

// Vérifier les organisations
$organizations = $user->organizations()->get();
echo "📋 Organisations ({$organizations->count()}):\n";
foreach ($organizations as $org) {
    echo "   - {$org->name} (ID: {$org->id})\n";
    echo "     Role: " . $org->pivot->role . "\n";
    echo "     Active: " . ($org->pivot->is_active ? 'Oui' : 'Non') . "\n";
}
echo "\n";

// Vérifier les magasins
$stores = $user->stores()->get();
echo "🏪 Magasins ({$stores->count()}):\n";
foreach ($stores as $store) {
    echo "   - {$store->name} (ID: {$store->id})\n";
    echo "     Organization ID: {$store->organization_id}\n";
    echo "     Role: " . $store->pivot->role . "\n";
    echo "     Is Default: " . ($store->pivot->is_default ? 'Oui' : 'Non') . "\n";
}
echo "\n";

// Corriger les problèmes
echo "=== Correction des problèmes ===\n\n";

DB::beginTransaction();
try {
    $fixed = false;

    // Si pas d'organisation par défaut
    if (!$user->default_organization_id && $organizations->count() > 0) {
        $org = $organizations->first();
        $user->default_organization_id = $org->id;
        echo "✓ Organisation par défaut définie: {$org->name}\n";
        $fixed = true;
    }

    // Si pas de magasin actuel
    if (!$user->current_store_id) {
        if ($stores->count() > 0) {
            $store = $stores->first();
            $user->current_store_id = $store->id;
            echo "✓ Magasin actuel défini: {$store->name}\n";
            $fixed = true;
        } else {
            // Créer un magasin par défaut
            if ($user->default_organization_id) {
                $org = Organization::find($user->default_organization_id);
                $store = Store::create([
                    'organization_id' => $org->id,
                    'name' => 'Magasin Principal',
                    'slug' => \Illuminate\Support\Str::slug('Magasin Principal-' . $org->id),
                    'code' => 'MAIN',
                    'address' => '',
                    'city' => '',
                    'country' => 'France',
                    'phone' => '',
                    'email' => $user->email,
                    'is_active' => true,
                    'is_main' => true,
                ]);

                $user->stores()->attach($store->id, [
                    'role' => 'owner',
                    'is_default' => true,
                ]);

                $user->current_store_id = $store->id;
                echo "✓ Magasin créé et défini: {$store->name}\n";
                $fixed = true;
            }
        }
    }

    // Vérifier que le magasin actuel est bien attaché
    if ($user->current_store_id) {
        $hasAccess = $user->stores()->where('stores.id', $user->current_store_id)->exists();
        if (!$hasAccess) {
            $store = Store::find($user->current_store_id);
            if ($store) {
                $user->stores()->attach($store->id, [
                    'role' => 'owner',
                    'is_default' => true,
                ]);
                echo "✓ Relation magasin corrigée\n";
                $fixed = true;
            }
        }
    }

    if ($fixed) {
        $user->save();
        DB::commit();
        echo "\n✅ Corrections appliquées avec succès!\n";
    } else {
        DB::rollBack();
        echo "\n✅ Aucune correction nécessaire\n";
    }

} catch (\Exception $e) {
    DB::rollBack();
    echo "\n❌ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n=== État final ===\n";
$user = $user->fresh();
echo "Default Org ID: " . ($user->default_organization_id ?? 'NULL') . "\n";
echo "Current Store ID: " . ($user->current_store_id ?? 'NULL') . "\n";
echo "\n✅ Vous pouvez maintenant vous reconnecter!\n";
