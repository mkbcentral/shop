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
        Schema::create('shwary_transactions', function (Blueprint $table) {
            $table->id();
            
            // Identifiants Shwary
            $table->string('transaction_id')->nullable()->index();
            $table->string('reference')->nullable()->index();
            
            // Informations de paiement
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('CDF');
            $table->string('phone_number');
            $table->string('country_code', 2)->default('CD');
            
            // Statut
            $table->string('status')->default('pending')->index();
            // Statuts possibles: pending, processing, completed, success, failed, cancelled, rejected, expired
            
            // Métadonnées (organization_id, plan, user_id, etc.)
            $table->json('metadata')->nullable();
            
            // Données de réponse Shwary
            $table->json('response_data')->nullable();
            
            // Timestamps spécifiques
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            
            $table->timestamps();
            
            // Index composé pour les recherches
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shwary_transactions');
    }
};
