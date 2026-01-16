<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\MenuItem;
use App\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add Subscription Settings menu item for Super Admin
        $menuItem = MenuItem::updateOrCreate(
            ['code' => 'subscription-settings'],
            [
                'name' => 'Paramètres Abonnements',
                'code' => 'subscription-settings',
                'route' => 'admin.subscription-settings',
                'section' => 'Administration',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
                'order' => 4,
                'badge_type' => null,
                'badge_color' => '',
                'is_active' => true,
                'parent_id' => null,
            ]
        );

        // Attach to super-admin role only
        $superAdmin = Role::where('name', 'super-admin')->orWhere('slug', 'super-admin')->first();
        if ($superAdmin && $menuItem) {
            $menuItem->roles()->sync([$superAdmin->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        MenuItem::where('code', 'subscription-settings')->delete();
    }
};
