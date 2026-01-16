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
        // Mettre à jour tous les produits sans store_id en utilisant le store_id = 1
        DB::table('products')
            ->whereNull('store_id')
            ->update(['store_id' => 1]);

        // Supprimer l'ancienne contrainte de clé étrangère et la recréer sans nullOnDelete
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable(false)->change();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer la contrainte
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
        });

        // Remettre store_id en nullable et recréer la contrainte avec nullOnDelete
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->change();
            $table->foreign('store_id')->references('id')->on('stores')->nullOnDelete();
        });
    }
};
