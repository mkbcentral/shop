<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DefautUserSuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Artisan::call('user:create-super-admin', [
            '--name' => 'Ben MWILA',
            '--email' => 'mkbcentral@gmail.com',
            '--password' => 'Admin@1234',
            '--force' => true,
        ], $this->command->getOutput());
        
        Artisan::call('superadmin:assign-menus', [
            '--force' => true,
        ], $this->command->getOutput());
    }
}
