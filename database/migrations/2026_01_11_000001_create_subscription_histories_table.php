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
        Schema::create('subscription_histories', function (Blueprint $table) {
            $table->id();
            
            // Organisation concernée
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            
            // Utilisateur ayant effectué le changement
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // Type d'action
            $table->enum('action', [
                'created',      // Création initiale
                'upgraded',     // Passage à un plan supérieur
                'downgraded',   // Passage à un plan inférieur
                'renewed',      // Renouvellement
                'cancelled',    // Annulation
                'expired',      // Expiration automatique
                'reactivated',  // Réactivation après expiration
                'trial_started', // Début période d'essai
                'trial_ended',  // Fin période d'essai
            ]);
            
            // Plans
            $table->string('old_plan')->nullable();
            $table->string('new_plan');
            
            // Dates de l'abonnement au moment du changement
            $table->timestamp('subscription_starts_at')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
            
            // Limites au moment du changement
            $table->integer('max_stores')->default(1);
            $table->integer('max_users')->default(3);
            $table->integer('max_products')->default(100);
            
            // Paiement associé (si applicable)
            $table->foreignId('subscription_payment_id')->nullable();
            
            // Montant (pour référence)
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('currency', 3)->default('CDF');
            
            // Notes/raison du changement
            $table->text('notes')->nullable();
            
            // Métadonnées supplémentaires
            $table->json('metadata')->nullable();
            
            // IP et user agent pour audit
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            
            $table->timestamps();
            
            // Index
            $table->index('organization_id');
            $table->index('action');
            $table->index('created_at');
            $table->index(['organization_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_histories');
    }
};
