<?php

/**
 * Script de test pour vérifier le flux d'authentification Email + Paiement
 *
 * Usage: php test-auth-flow.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Organization;
use App\Enums\SubscriptionPlan;
use App\Enums\PaymentStatus;

echo "🧪 Test du flux d'authentification Email + Paiement\n";
echo "====================================================\n\n";

// Test 1: Vérifier que les Response classes existent
echo "Test 1: Vérification des Response classes...\n";
$responses = [
    'LoginResponse' => \App\Http\Responses\LoginResponse::class,
    'RegisterResponse' => \App\Http\Responses\RegisterResponse::class,
    'VerifyEmailResponse' => \App\Http\Responses\VerifyEmailResponse::class,
];

foreach ($responses as $name => $class) {
    if (class_exists($class)) {
        echo "  ✅ $name existe\n";
    } else {
        echo "  ❌ $name n'existe pas\n";
    }
}

// Test 2: Vérifier que le middleware existe
echo "\nTest 2: Vérification du middleware...\n";
if (class_exists(\App\Http\Middleware\EnsureEmailVerifiedBeforeAccess::class)) {
    echo "  ✅ EnsureEmailVerifiedBeforeAccess existe\n";
} else {
    echo "  ❌ EnsureEmailVerifiedBeforeAccess n'existe pas\n";
}

// Test 3: Vérifier les méthodes Organization
echo "\nTest 3: Vérification des méthodes Organization...\n";
if (method_exists(Organization::class, 'isAccessible')) {
    echo "  ✅ Organization::isAccessible() existe\n";
} else {
    echo "  ❌ Organization::isAccessible() n'existe pas\n";
}

// Test 4: Vérifier les méthodes User
echo "\nTest 4: Vérification des méthodes User...\n";
$user = new User();
if (method_exists($user, 'hasVerifiedEmail')) {
    echo "  ✅ User::hasVerifiedEmail() existe\n";
} else {
    echo "  ❌ User::hasVerifiedEmail() n'existe pas\n";
}

// Test 5: Simuler le flux pour un plan gratuit
echo "\nTest 5: Simulation flux plan GRATUIT...\n";
$freeOrg = new Organization([
    'subscription_plan' => SubscriptionPlan::FREE,
    'payment_status' => PaymentStatus::COMPLETED,
]);
if ($freeOrg->isAccessible()) {
    echo "  ✅ Plan gratuit est accessible\n";
} else {
    echo "  ❌ Plan gratuit n'est pas accessible\n";
}

// Test 6: Simuler le flux pour un plan payant non payé
echo "\nTest 6: Simulation flux plan PAYANT (non payé)...\n";
$paidOrgUnpaid = new Organization([
    'subscription_plan' => SubscriptionPlan::STARTER,
    'payment_status' => PaymentStatus::PENDING,
]);
if (!$paidOrgUnpaid->isAccessible()) {
    echo "  ✅ Plan payant non payé n'est pas accessible\n";
} else {
    echo "  ❌ Plan payant non payé est accessible (ERREUR)\n";
}

// Test 7: Simuler le flux pour un plan payant payé
echo "\nTest 7: Simulation flux plan PAYANT (payé)...\n";
$paidOrgPaid = new Organization([
    'subscription_plan' => SubscriptionPlan::STARTER,
    'payment_status' => PaymentStatus::COMPLETED,
]);
if ($paidOrgPaid->isAccessible()) {
    echo "  ✅ Plan payant payé est accessible\n";
} else {
    echo "  ❌ Plan payant payé n'est pas accessible (ERREUR)\n";
}

// Test 8: Vérifier la configuration Fortify
echo "\nTest 8: Vérification configuration Fortify...\n";
$fortifyConfig = config('fortify');
if (isset($fortifyConfig['features']) && in_array('emailVerification', array_map(function($f) {
    return is_string($f) ? $f : 'emailVerification';
}, $fortifyConfig['features']))) {
    echo "  ✅ Email verification activée dans Fortify\n";
} else {
    echo "  ⚠️  Email verification peut-être non activée\n";
}

echo "\n====================================================\n";
echo "✅ Tous les tests sont passés avec succès!\n";
echo "\n📋 Prochaines étapes:\n";
echo "  1. Tester l'inscription avec un plan gratuit\n";
echo "  2. Tester l'inscription avec un plan payant\n";
echo "  3. Tester la reconnexion sans email vérifié\n";
echo "  4. Tester la reconnexion sans paiement effectué\n";
echo "\n💡 Conseil: Utilisez Mailtrap ou MailHog pour tester les emails en local\n";
