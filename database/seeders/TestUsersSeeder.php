<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use App\Services\StoreService;
use Illuminate\Database\Seeder;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $storeService = app(StoreService::class);

        // Récupérer les magasins existants
        $stores = Store::all();

        if ($stores->isEmpty()) {
            $this->command->error('⚠️  Aucun magasin trouvé. Exécutez d\'abord StoreSeeder.');
            return;
        }

        $store1 = $stores->first();
        $store2 = $stores->skip(1)->first() ?? $store1;

        $this->command->info('🏪 Création des utilisateurs de test...');

        // 1. Admin Global (voit tous les magasins)
        $admin = User::firstOrCreate(
            ['email' => 'admin@stk.com'],
            [
                'name' => 'Administrateur Système',
                'password' => bcrypt('password'),
                'role' => 'admin', // Rôle global admin
                'is_active' => true,
            ]
        );

        if ($admin->wasRecentlyCreated) {
            $storeService->assignUserToStore($store1->id, $admin->id, 'admin', true);
            $this->command->info("✅ Admin créé : admin@stk.com (accès à TOUS les magasins)");
        }

        // 2. Manager du Magasin 1
        $manager = User::firstOrCreate(
            ['email' => 'manager@stk.com'],
            [
                'name' => 'Manager Magasin 1',
                'password' => bcrypt('password'),
                'role' => 'user',
                'is_active' => true,
            ]
        );

        if ($manager->wasRecentlyCreated) {
            $storeService->assignUserToStore($store1->id, $manager->id, 'manager', true);
            $this->command->info("✅ Manager créé : manager@stk.com (Magasin: {$store1->name})");
        }

        // 3. Cashier du Magasin 1 (FILTRE - ne voit que son magasin)
        $cashier1 = User::firstOrCreate(
            ['email' => 'cashier1@stk.com'],
            [
                'name' => 'Caissier Magasin 1',
                'password' => bcrypt('password'),
                'role' => 'user',
                'is_active' => true,
            ]
        );

        if ($cashier1->wasRecentlyCreated) {
            $storeService->assignUserToStore($store1->id, $cashier1->id, 'cashier', true);
            $this->command->info("✅ Cashier créé : cashier1@stk.com (Magasin: {$store1->name})");
        }

        // 4. Staff du Magasin 1 (FILTRE - ne voit que son magasin)
        $staff1 = User::firstOrCreate(
            ['email' => 'staff1@stk.com'],
            [
                'name' => 'Employé Magasin 1',
                'password' => bcrypt('password'),
                'role' => 'user',
                'is_active' => true,
            ]
        );

        if ($staff1->wasRecentlyCreated) {
            $storeService->assignUserToStore($store1->id, $staff1->id, 'staff', true);
            $this->command->info("✅ Staff créé : staff1@stk.com (Magasin: {$store1->name})");
        }

        // 5. Cashier du Magasin 2 (si existe)
        if ($store2->id !== $store1->id) {
            $cashier2 = User::firstOrCreate(
                ['email' => 'cashier2@stk.com'],
                [
                    'name' => 'Caissier Magasin 2',
                    'password' => bcrypt('password'),
                    'role' => 'user',
                    'is_active' => true,
                ]
            );

            if ($cashier2->wasRecentlyCreated) {
                $storeService->assignUserToStore($store2->id, $cashier2->id, 'cashier', true);
                $this->command->info("✅ Cashier créé : cashier2@stk.com (Magasin: {$store2->name})");
            }
        }

        $this->command->info('');
        $this->command->info('🎉 Utilisateurs de test créés avec succès !');
        $this->command->info('');
        $this->command->table(
            ['Email', 'Mot de passe', 'Rôle', 'Magasin', 'Accès'],
            [
                ['admin@stk.com', 'password', 'admin', 'Tous', 'GLOBAL'],
                ['manager@stk.com', 'password', 'manager', $store1->name, 'Complet'],
                ['cashier1@stk.com', 'password', 'cashier', $store1->name, 'FILTRÉ'],
                ['staff1@stk.com', 'password', 'staff', $store1->name, 'FILTRÉ'],
                $store2->id !== $store1->id ? ['cashier2@stk.com', 'password', 'cashier', $store2->name, 'FILTRÉ'] : null,
            ]
        );

        $this->command->info('');
        $this->command->warn('⚠️  IMPORTANT:');
        $this->command->info('   - Admin voit les données de TOUS les magasins');
        $this->command->info('   - Manager voit toutes les données de son magasin');
        $this->command->info('   - Cashier/Staff voient UNIQUEMENT les données de leur magasin (FILTRÉ)');
    }
}
