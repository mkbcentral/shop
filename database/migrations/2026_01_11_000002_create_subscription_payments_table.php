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
        Schema::create('subscription_payments', function (Blueprint $table) {
            $table->id();
            
            // Organisation concernée
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            
            // Utilisateur ayant effectué le paiement
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // Référence unique du paiement
            $table->string('reference')->unique();
            
            // Plan concerné
            $table->string('plan');
            
            // Durée en mois
            $table->integer('duration_months')->default(1);
            
            // Montants
            $table->decimal('amount', 15, 2);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('total', 15, 2);
            $table->string('currency', 3)->default('CDF');
            
            // Code promo utilisé
            $table->string('promo_code')->nullable();
            
            // Méthode de paiement
            $table->enum('payment_method', [
                'cash',           // Espèces
                'bank_transfer',  // Virement bancaire
                'mobile_money',   // Mobile Money (M-Pesa, Airtel Money, Orange Money)
                'card',           // Carte bancaire
                'stripe',         // Stripe
                'paypal',         // PayPal
                'other',          // Autre
            ])->default('mobile_money');
            
            // Détails de la méthode de paiement
            $table->string('payment_provider')->nullable(); // Ex: "M-Pesa", "Airtel Money"
            $table->string('transaction_id')->nullable();   // ID de transaction du provider
            
            // Statut du paiement
            $table->enum('status', [
                'pending',    // En attente
                'processing', // En cours de traitement
                'completed',  // Terminé
                'failed',     // Échoué
                'refunded',   // Remboursé
                'cancelled',  // Annulé
            ])->default('pending');
            
            // Dates
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            
            // Période couverte
            $table->timestamp('period_starts_at')->nullable();
            $table->timestamp('period_ends_at')->nullable();
            
            // Facture/Reçu
            $table->string('invoice_number')->nullable();
            $table->string('receipt_path')->nullable();
            
            // Notes
            $table->text('notes')->nullable();
            
            // Métadonnées (réponse API paiement, etc.)
            $table->json('metadata')->nullable();
            
            // IP et user agent
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index
            $table->index('organization_id');
            $table->index('reference');
            $table->index('status');
            $table->index('payment_method');
            $table->index('created_at');
            $table->index(['organization_id', 'status']);
        });
        
        // Ajouter la clé étrangère sur subscription_histories
        Schema::table('subscription_histories', function (Blueprint $table) {
            $table->foreign('subscription_payment_id')
                  ->references('id')
                  ->on('subscription_payments')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_histories', function (Blueprint $table) {
            $table->dropForeign(['subscription_payment_id']);
        });
        
        Schema::dropIfExists('subscription_payments');
    }
};
