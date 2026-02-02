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
        // Seed categories
        $this->call([
            SubscriptionPlanSeeder::class,
            CategorySeeder::class,
            RoleSeeder::class,
            MenuItemSeeder::class,
            ProductTypeSeeder::class,
            SubscriptionPlanSeeder::class,
            DefautUserSuperAdminSeeder::class,
        ]);
    }
}
