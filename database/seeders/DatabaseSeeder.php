<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed in proper order (ProductTypes before Categories since categories reference product types)
        $this->call([
            SubscriptionPlanSeeder::class,
            RoleSeeder::class,
            MenuItemSeeder::class,
            ProductTypeSeeder::class, // Product types must be seeded before categories
            CategorySeeder::class,    // Categories reference product types
            SubscriptionPlanSeeder::class,
            DefautUserSuperAdminSeeder::class,
            AvailableFeaturesSeeder::class,
        ]);
    }
}
