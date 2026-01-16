<?php

use App\Models\Role;
use App\Models\User;
use App\Services\UserService;

// Afficher tous les rôles
echo "=== RÔLES CRÉÉS ===\n\n";
$roles = Role::all(['name', 'slug', 'description']);
foreach ($roles as $role) {
    echo "- {$role->name} ({$role->slug})\n";
    echo "  {$role->description}\n";
    echo "  Permissions: " . count($role->permissions ?? []) . "\n\n";
}

// Test de création d'utilisateur avec rôles
echo "\n=== TEST CRÉATION UTILISATEUR ===\n\n";

try {
    $userService = app(UserService::class);

    // Trouver ou créer un utilisateur de test
    $testUser = User::firstOrCreate(
        ['email' => 'test@manager.com'],
        [
            'name' => 'Test Manager',
            'password' => bcrypt('password123'),
        ]
    );

    // Assigner le rôle manager
    $testUser->assignRole('manager');

    echo "Utilisateur créé: {$testUser->name}\n";
    echo "Email: {$testUser->email}\n";
    echo "Rôles: " . $testUser->roles->pluck('name')->join(', ') . "\n\n";

    // Tester les permissions
    echo "=== TEST PERMISSIONS ===\n\n";

    $permissions = [
        'sales.create',
        'products.edit',
        'users.delete',
        'system.settings',
    ];

    foreach ($permissions as $permission) {
        $has = $testUser->hasPermission($permission) ? '✓' : '✗';
        echo "{$has} {$permission}\n";
    }

    echo "\n✓ Tous les tests ont réussi!\n";

} catch (\Exception $e) {
    echo "✗ Erreur: {$e->getMessage()}\n";
}
