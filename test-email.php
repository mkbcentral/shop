<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Mail;

try {
    Mail::raw('Ceci est un email de test depuis ShopFlow', function ($message) {
        $message->to('test@example.com')
                ->subject('Test Email - ShopFlow');
    });

    echo "Email envoyé avec succès!\n";
    echo "Vérifiez Mailhog sur http://localhost:8025\n";
} catch (Exception $e) {
    echo "Erreur lors de l'envoi: " . $e->getMessage() . "\n";
}
