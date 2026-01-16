<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer le premier utilisateur ou en créer un
        $owner = User::first();

        if (!$owner) {
            $owner = User::create([
                'name' => 'Admin Principal',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
        }

        // Créer ou mettre à jour une organisation de test principale
        $organization = Organization::updateOrCreate(
            ['slug' => 'stk-demo-sarl'],
            [
                'name' => 'STK Demo SARL',
                'type' => 'company',
                'legal_form' => 'sarl',
                'legal_name' => 'STK Demo Société à Responsabilité Limitée',
                'owner_id' => $owner->id,
                'tax_id' => 'NIF-123456789',
                'registration_number' => 'RCCM-2024-001',
                'email' => 'contact@stkdemo.com',
                'phone' => '+243 123 456 789',
                'address' => '123 Avenue de la Liberté',
                'city' => 'Kinshasa',
                'country' => 'CD',
                'website' => 'https://stkdemo.com',
                'subscription_plan' => 'professional',
                'subscription_starts_at' => now(),
                'subscription_ends_at' => now()->addYear(),
                'is_trial' => false,
                'max_stores' => 10,
                'max_users' => 50,
                'max_products' => 10000,
                'currency' => 'USD',
                'timezone' => 'Africa/Kinshasa',
                'is_active' => true,
                'is_verified' => true,
                'verified_at' => now(),
                'settings' => [
                    'language' => 'fr',
                    'features' => ['multi_store', 'advanced_reports', 'api_access'],
                ],
            ]
        );

        // Attacher l'owner à l'organisation avec le rôle 'owner' (si pas déjà membre)
        if (!$organization->members()->where('user_id', $owner->id)->exists()) {
            $organization->members()->attach($owner->id, [
                'role' => 'owner',
                'accepted_at' => now(),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Définir cette organisation comme organisation par défaut de l'owner
        $owner->update(['default_organization_id' => $organization->id]);

        // Créer ou récupérer d'autres utilisateurs pour les rôles
        $this->createMemberIfNeeded('Manager Principal', 'manager@example.com', $organization, 'manager', $owner->id);
        $this->createMemberIfNeeded('Comptable', 'accountant@example.com', $organization, 'accountant', $owner->id);
        $this->createMemberIfNeeded('Membre Simple', 'member@example.com', $organization, 'member', $owner->id);

        // Associer les magasins existants à l'organisation
        $stores = Store::whereNull('organization_id')->get();
        $storeNumber = 1;

        foreach ($stores as $store) {
            $store->update([
                'organization_id' => $organization->id,
                'store_number' => $storeNumber++,
            ]);
        }

        // Créer ou mettre à jour une deuxième organisation de test (pour tester le multi-organisations)
        $organization2 = Organization::updateOrCreate(
            ['slug' => 'boutique-express'],
            [
                'name' => 'Boutique Express',
                'type' => 'individual',
                'legal_form' => 'individual',
                'owner_id' => $owner->id,
                'email' => 'info@boutiqueexpress.com',
                'phone' => '+243 987 654 321',
                'address' => '45 Boulevard du Commerce',
                'city' => 'Lubumbashi',
                'country' => 'CD',
                'subscription_plan' => 'starter',
                'subscription_starts_at' => now(),
                'subscription_ends_at' => now()->addMonths(6),
                'is_trial' => false,
                'max_stores' => 3,
                'max_users' => 10,
                'max_products' => 500,
                'currency' => 'CDF',
                'timezone' => 'Africa/Lubumbashi',
                'is_active' => true,
                'is_verified' => true,
                'verified_at' => now(),
                'settings' => [
                    'language' => 'fr',
                    'features' => ['multi_store'],
                ],
            ]
        );

        // Attacher l'owner à la deuxième organisation (si pas déjà membre)
        if (!$organization2->members()->where('user_id', $owner->id)->exists()) {
            $organization2->members()->attach($owner->id, [
                'role' => 'owner',
                'accepted_at' => now(),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✅ Organisations créées avec succès!');
        $this->command->info("   - {$organization->name} (ID: {$organization->id})");
        $this->command->info("   - {$organization2->name} (ID: {$organization2->id})");
        $this->command->info("   - Propriétaire: {$owner->name} ({$owner->email})");
        $this->command->info("   - {$stores->count()} magasin(s) associé(s) à {$organization->name}");
    }

    /**
     * Créer un membre s'il n'existe pas déjà
     */
    private function createMemberIfNeeded(string $name, string $email, Organization $organization, string $role, int $invitedBy): void
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
        }

        // Vérifier si l'utilisateur n'est pas déjà membre
        if (!$organization->members()->where('user_id', $user->id)->exists()) {
            $organization->members()->attach($user->id, [
                'role' => $role,
                'invited_by' => $invitedBy,
                'invited_at' => now()->subDays(7),
                'accepted_at' => now()->subDays(5),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
