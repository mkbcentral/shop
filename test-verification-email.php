<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Mail;

echo "=== Test d'envoi d'email de vérification ===\n\n";

// Récupérer un utilisateur non vérifié
$user = User::whereNull('email_verified_at')->first();

if (!$user) {
    echo "❌ Aucun utilisateur non vérifié trouvé.\n";
    echo "Créez d'abord un compte via l'interface d'inscription.\n";
    exit(1);
}

echo "✓ Utilisateur trouvé: {$user->name} ({$user->email})\n";
echo "✓ Email vérifié: " . ($user->hasVerifiedEmail() ? 'Oui' : 'Non') . "\n\n";

echo "Envoi de l'email de vérification...\n";

try {
    // Activer le log des emails
    config(['mail.default' => 'smtp']);

    $user->sendEmailVerificationNotification();

    echo "✓ Email envoyé avec succès!\n";
    echo "✓ Vérifiez Mailhog sur http://localhost:8025\n\n";

    // Afficher la configuration mail
    echo "Configuration SMTP:\n";
    echo "  - Host: " . config('mail.mailers.smtp.host') . "\n";
    echo "  - Port: " . config('mail.mailers.smtp.port') . "\n";
    echo "  - From: " . config('mail.from.address') . "\n";

} catch (Exception $e) {
    echo "❌ Erreur lors de l'envoi: " . $e->getMessage() . "\n";
    echo "\nTrace:\n" . $e->getTraceAsString() . "\n";
}
