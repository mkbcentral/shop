<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Artisan;

echo "=== NETTOYAGE COMPLET SESSIONS ET CACHE ===\n\n";

// Supprimer tous les fichiers de session
$sessionsPath = storage_path('framework/sessions');
if (is_dir($sessionsPath)) {
    $files = glob($sessionsPath . '/*');
    $count = 0;
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
            $count++;
        }
    }
    echo "✅ {$count} fichier(s) de session supprimé(s)\n";
} else {
    echo "⚠️  Dossier sessions introuvable\n";
}

// Vider tous les caches
echo "\n=== VIDAGE DES CACHES ===\n";
Artisan::call('cache:clear');
echo "✅ Cache application vidé\n";

Artisan::call('config:clear');
echo "✅ Cache config vidé\n";

Artisan::call('view:clear');
echo "✅ Cache views vidé\n";

Artisan::call('route:clear');
echo "✅ Cache routes vidé\n";

echo "\n✅ Nettoyage terminé!\n\n";
echo "ÉTAPES SUIVANTES:\n";
echo "1. Fermez tous les onglets du navigateur\n";
echo "2. Videz le cache navigateur (Ctrl+Shift+Delete)\n";
echo "3. Rouvrez en navigation privée\n";
echo "4. Connectez-vous\n";
