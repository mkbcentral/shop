<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Durée du service en minutes (override du type par défaut)
            $table->integer('duration_minutes')->nullable()->after('unit_of_measure');

            // Type de tarification : fixe, horaire, par séance
            $table->enum('pricing_type', ['fixed', 'hourly', 'per_session'])->default('fixed')
                  ->after('duration_minutes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['duration_minutes', 'pricing_type']);
        });
    }
};
