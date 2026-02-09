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
        Schema::table('product_types', function (Blueprint $table) {
            // Indique si c'est un service (pas de stock physique)
            $table->boolean('is_service')->default(false)->after('has_serial_number');

            // Durée par défaut du service en minutes (optionnel)
            $table->integer('default_duration_minutes')->nullable()->after('is_service');

            // Le service nécessite-t-il une réservation ?
            $table->boolean('requires_booking')->default(false)->after('default_duration_minutes');

            // Index pour filtrer rapidement les services
            $table->index('is_service');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_types', function (Blueprint $table) {
            $table->dropIndex(['is_service']);
            $table->dropColumn(['is_service', 'default_duration_minutes', 'requires_booking']);
        });
    }
};
