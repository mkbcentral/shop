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
        Schema::create('organization_taxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();

            // Informations de la taxe
            $table->string('name');                          // Ex: TVA, Taxe Municipale, etc.
            $table->string('code', 50)->nullable();          // Code court: TVA, TM, etc.
            $table->text('description')->nullable();         // Description de la taxe

            // Taux et calcul
            $table->decimal('rate', 8, 4);                   // Taux en pourcentage (ex: 16.0000 pour 16%)
            $table->enum('type', ['percentage', 'fixed'])->default('percentage'); // Type de calcul
            $table->decimal('fixed_amount', 15, 2)->nullable(); // Montant fixe si type = fixed

            // Application
            $table->boolean('is_compound')->default(false);  // Taxe composée (calculée sur le total + autres taxes)
            $table->boolean('is_included_in_price')->default(false); // Taxe incluse dans le prix affiché
            $table->integer('priority')->default(0);         // Ordre d'application (pour taxes composées)

            // Applicabilité
            $table->boolean('apply_to_all_products')->default(true);  // Appliquer à tous les produits
            $table->json('product_categories')->nullable();           // Catégories de produits concernées
            $table->json('excluded_product_ids')->nullable();         // Produits exclus

            // Seuils (optionnel - pour les organisations exemptées selon leur taille)
            $table->decimal('min_amount', 15, 2)->nullable(); // Montant minimum pour appliquer la taxe
            $table->decimal('max_amount', 15, 2)->nullable(); // Montant maximum de la taxe

            // État
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);   // Taxe par défaut pour les nouvelles ventes

            // Période de validité (optionnel)
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();

            // Informations légales
            $table->string('tax_number')->nullable();        // Numéro d'identification fiscale
            $table->string('authority')->nullable();         // Autorité fiscale (DGI, Mairie, etc.)

            $table->timestamps();
            $table->softDeletes();

            // Index pour les recherches fréquentes
            $table->index(['organization_id', 'is_active']);
            $table->index(['organization_id', 'is_default']);
            $table->unique(['organization_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_taxes');
    }
};
