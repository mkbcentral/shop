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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();

            // Informations de base
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('legal_name')->nullable();
            $table->enum('type', [
                'individual',    // Entrepreneur individuel
                'company',       // Entreprise/Société
                'franchise',     // Franchise
                'cooperative',   // Coopérative
                'group'          // Groupe commercial
            ])->default('company');

            // Informations légales
            $table->string('tax_id')->nullable();                // NIF/RCCM
            $table->string('registration_number')->nullable();   // Numéro d'immatriculation
            $table->string('legal_form')->nullable();            // SARL, SA, etc.

            // Contact
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('CD');            // Code pays

            // Branding
            $table->string('logo')->nullable();
            $table->string('website')->nullable();

            // Propriétaire (créateur)
            $table->foreignId('owner_id')->constrained('users');

            // Abonnement (pour SaaS)
            $table->enum('subscription_plan', [
                'free',          // Gratuit (limité)
                'starter',       // Démarrage
                'professional',  // Professionnel
                'enterprise'     // Entreprise
            ])->default('free');
            $table->timestamp('subscription_starts_at')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
            $table->boolean('is_trial')->default(true);

            // Limites selon abonnement
            $table->integer('max_stores')->default(1);           // Nombre max de magasins
            $table->integer('max_users')->default(3);            // Nombre max d'utilisateurs
            $table->integer('max_products')->default(100);       // Nombre max de produits

            // Configuration
            $table->json('settings')->nullable();                // Paramètres personnalisés
            $table->string('currency')->default('CDF');          // Devise par défaut
            $table->string('timezone')->default('Africa/Kinshasa');

            // Statut
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false);      // Vérifié par admin
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index('owner_id');
            $table->index('subscription_plan');
            $table->index('is_active');
            $table->index(['type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
