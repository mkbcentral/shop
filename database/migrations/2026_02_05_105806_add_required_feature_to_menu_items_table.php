<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->string('required_feature')->nullable()->after('badge_color')
                ->comment('Fonctionnalité technique requise pour voir ce menu (ex: module_clients)');
        });

        // Définir les features requises pour certains menus
        $this->assignFeaturesToMenus();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn('required_feature');
        });
    }

    /**
     * Assigner les features aux menus existants
     */
    protected function assignFeaturesToMenus(): void
    {
        $menuFeatures = [
            'clients' => 'module_clients',
            'suppliers' => 'module_suppliers',
            'purchases' => 'module_purchases',
            'invoices' => 'module_invoices',
        ];

        foreach ($menuFeatures as $menuCode => $feature) {
            DB::table('menu_items')
                ->where('code', $menuCode)
                ->update(['required_feature' => $feature]);
        }
    }
};
