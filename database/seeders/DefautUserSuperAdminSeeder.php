<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefautUserSuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer ou récupérer le rôle super-admin
        $superAdminRole = \App\Models\Role::firstOrCreate(
            ['slug' => 'super-admin'],
            [
                'name' => 'super-admin',
                'description' => 'Super Administrateur - Accès complet au système'
            ]
        );

        // Créer ou mettre à jour le super admin user
        $user = \App\Models\User::updateOrCreate(
            ['email' => 'mkbcentral@gmail.com'],
            [
                'name' => 'MKB Central',
                'password' => bcrypt('Mkbc@12345'),
                'role' => 'super_admin',
                'is_active' => true,
            ]
        );

        // Assigner le rôle super-admin
        if (!$user->hasRole('super-admin')) {
            $user->assignRole('super-admin');
        }

        // Vérifier l'email
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }
        if (!empty($allOrganizations)) {
            $user->organizations()->sync($allOrganizations);
            $this->command->info('Organizations assigned: ' . count($allOrganizations));
        } else {
            $this->command->warn('No organizations found to assign.');
        }

        //Show success message
        $this->command->info('Super Admin User Created:');
        $this->command->info('Name: ' . $user->name);
        $this->command->info('Email: ' . $user->email);
        $this->command->info('Role: ' . $user->role);
    }
}
