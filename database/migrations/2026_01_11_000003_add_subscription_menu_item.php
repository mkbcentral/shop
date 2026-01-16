<?php

use App\Models\MenuItem;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Trouver le menu parent "Organisations"
        $organizationsMenu = MenuItem::where('code', 'organizations')->first();

        if ($organizationsMenu) {
            // Ajouter le sous-menu "Abonnements"
            $subscriptionMenu = MenuItem::updateOrCreate(
                ['code' => 'subscriptions'],
                [
                    'name' => 'Abonnements',
                    'code' => 'subscriptions',
                    'route' => null,
                    'url' => '/organizations',
                    'section' => $organizationsMenu->section ?? 'Multi-Magasins',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>',
                    'parent_id' => null,
                    'order' => 4,
                    'badge_type' => '',
                    'badge_color' => '',
                    'is_active' => true,
                ]
            );

            // Attacher les mêmes rôles que le menu organisations
            $roleIds = $organizationsMenu->roles()->pluck('roles.id')->toArray();
            if (!empty($roleIds)) {
                $subscriptionMenu->roles()->sync($roleIds);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        MenuItem::where('code', 'subscriptions')->delete();
    }
};
