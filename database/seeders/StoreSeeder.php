<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        // Créer le magasin principal
        $mainStore = Store::create([
            'name' => 'Magasin Principal',
            'code' => 'MAG-001',
            'address' => 'Avenue du Commerce, Centre-Ville',
            'phone' => '+243 XXX XXX XXX',
            'email' => 'principal@boutique.com',
            'is_active' => true,
            'is_main' => true,
        ]);

        // Créer d'autres magasins
        $stores = [
            [
                'name' => 'Boutique Gombe',
                'code' => 'MAG-002',
                'address' => 'Boulevard du 30 Juin, Gombe',
                'phone' => '+243 XXX XXX XXX',
                'email' => 'gombe@boutique.com',
                'is_active' => true,
                'is_main' => false,
            ],
            [
                'name' => 'Boutique Limete',
                'code' => 'MAG-003',
                'address' => 'Avenue Limete, Limete',
                'phone' => '+243 XXX XXX XXX',
                'email' => 'limete@boutique.com',
                'is_active' => true,
                'is_main' => false,
            ],
        ];

        foreach ($stores as $storeData) {
            Store::create($storeData);
        }

        // Assigner tous les utilisateurs existants au magasin principal
        $users = User::all();
        foreach ($users as $user) {
            $mainStore->users()->attach($user->id, [
                'role' => 'admin',
                'is_default' => true,
            ]);

            // Définir le magasin principal comme magasin actuel
            $user->update(['current_store_id' => $mainStore->id]);
        }

        $this->command->info('✅ Magasins créés avec succès');
        $this->command->info('✅ Utilisateurs assignés au magasin principal');
    }
}
