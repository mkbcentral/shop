<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Accès complet à toutes les fonctionnalités du système',
                'permissions' => [
                    // Gestion système
                    'system.settings',
                    'system.backup',
                    'system.logs',

                    // Gestion utilisateurs
                    'users.view',
                    'users.create',
                    'users.edit',
                    'users.delete',
                    'users.assign-role',
                    'users.assign-store',

                    // Gestion magasins
                    'stores.view',
                    'stores.create',
                    'stores.edit',
                    'stores.delete',
                    'stores.manage-users',
                    'stores.view-statistics',

                    // Gestion rôles
                    'roles.view',
                    'roles.create',
                    'roles.edit',
                    'roles.delete',

                    // Gestion produits
                    'products.view',
                    'products.create',
                    'products.edit',
                    'products.delete',
                    'products.manage-stock',

                    // Gestion catégories
                    'categories.view',
                    'categories.create',
                    'categories.edit',
                    'categories.delete',

                    // Gestion ventes
                    'sales.view',
                    'sales.create',
                    'sales.edit',
                    'sales.delete',
                    'sales.refund',

                    // Gestion achats
                    'purchases.view',
                    'purchases.create',
                    'purchases.edit',
                    'purchases.delete',

                    // Gestion clients
                    'clients.view',
                    'clients.create',
                    'clients.edit',
                    'clients.delete',

                    // Gestion fournisseurs
                    'suppliers.view',
                    'suppliers.create',
                    'suppliers.edit',
                    'suppliers.delete',

                    // Gestion transferts
                    'transfers.view',
                    'transfers.create',
                    'transfers.approve',
                    'transfers.receive',
                    'transfers.cancel',

                    // Rapports
                    'reports.sales',
                    'reports.purchases',
                    'reports.stock',
                    'reports.financial',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Administrateur avec accès à la plupart des fonctionnalités',
                'permissions' => [
                    // Gestion utilisateurs (limité)
                    'users.view',
                    'users.create',
                    'users.edit',
                    'users.assign-store',

                    // Gestion magasins
                    'stores.view',
                    'stores.edit',
                    'stores.manage-users',
                    'stores.view-statistics',

                    // Gestion produits
                    'products.view',
                    'products.create',
                    'products.edit',
                    'products.delete',
                    'products.manage-stock',

                    // Gestion catégories
                    'categories.view',
                    'categories.create',
                    'categories.edit',
                    'categories.delete',

                    // Gestion ventes
                    'sales.view',
                    'sales.create',
                    'sales.edit',
                    'sales.refund',

                    // Gestion achats
                    'purchases.view',
                    'purchases.create',
                    'purchases.edit',
                    'purchases.delete',

                    // Gestion clients
                    'clients.view',
                    'clients.create',
                    'clients.edit',
                    'clients.delete',

                    // Gestion fournisseurs
                    'suppliers.view',
                    'suppliers.create',
                    'suppliers.edit',
                    'suppliers.delete',

                    // Gestion transferts
                    'transfers.view',
                    'transfers.create',
                    'transfers.approve',
                    'transfers.receive',
                    'transfers.cancel',

                    // Rapports
                    'reports.sales',
                    'reports.purchases',
                    'reports.stock',
                    'reports.financial',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'description' => 'Gérant de magasin avec accès aux opérations quotidiennes',
                'permissions' => [
                    // Gestion magasins (lecture seule)
                    'stores.view',
                    'stores.view-statistics',

                    // Gestion produits
                    'products.view',
                    'products.create',
                    'products.edit',
                    'products.manage-stock',

                    // Gestion catégories
                    'categories.view',
                    'categories.create',
                    'categories.edit',

                    // Gestion ventes
                    'sales.view',
                    'sales.create',
                    'sales.edit',
                    'sales.refund',

                    // Gestion achats
                    'purchases.view',
                    'purchases.create',
                    'purchases.edit',

                    // Gestion clients
                    'clients.view',
                    'clients.create',
                    'clients.edit',

                    // Gestion fournisseurs
                    'suppliers.view',
                    'suppliers.create',
                    'suppliers.edit',

                    // Gestion transferts
                    'transfers.view',
                    'transfers.create',
                    'transfers.receive',

                    // Rapports
                    'reports.sales',
                    'reports.purchases',
                    'reports.stock',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Cashier',
                'slug' => 'cashier',
                'description' => 'Caissier avec accès aux ventes et clients',
                'permissions' => [
                    // Gestion produits (lecture seule)
                    'products.view',

                    // Gestion ventes
                    'sales.view',
                    'sales.create',

                    // Gestion clients
                    'clients.view',
                    'clients.create',
                    'clients.edit',

                    // Rapports (limité)
                    'reports.sales',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Staff',
                'slug' => 'staff',
                'description' => 'Employé avec accès limité aux fonctionnalités de base',
                'permissions' => [
                    // Gestion produits (lecture seule)
                    'products.view',

                    // Gestion stock
                    'products.manage-stock',

                    // Gestion clients (lecture seule)
                    'clients.view',

                    // Gestion transferts (lecture seule)
                    'transfers.view',
                ],
                'is_active' => true,
            ],
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['slug' => $roleData['slug']],
                $roleData
            );
        }

        $this->command->info('Roles created successfully!');
    }
}
