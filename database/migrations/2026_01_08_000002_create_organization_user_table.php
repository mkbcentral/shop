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
        Schema::create('organization_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Rôle dans l'organisation
            $table->enum('role', [
                'owner',         // Propriétaire (tous les droits)
                'admin',         // Administrateur
                'manager',       // Manager (gère les magasins)
                'accountant',    // Comptable (accès rapports)
                'member'         // Membre simple
            ])->default('member');

            // Invitation
            $table->timestamp('invited_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->foreignId('invited_by')->nullable()->constrained('users')->nullOnDelete();

            // Statut
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['organization_id', 'user_id']);
            $table->index('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_user');
    }
};
