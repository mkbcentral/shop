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
        Schema::table('users', function (Blueprint $table) {
            // Organisation par défaut (pour login)
            $table->foreignId('default_organization_id')
                  ->nullable()
                  ->after('current_store_id')
                  ->constrained('organizations')
                  ->nullOnDelete();

            $table->index('default_organization_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['default_organization_id']);
            $table->dropColumn('default_organization_id');
        });
    }
};
