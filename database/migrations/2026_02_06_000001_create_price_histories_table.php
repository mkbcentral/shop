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
        Schema::create('price_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_variant_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');

            // Type de prix modifié
            $table->enum('price_type', ['price', 'cost_price', 'additional_price'])
                  ->default('price')
                  ->comment('Type de prix: price=vente, cost_price=achat, additional_price=supplément variante');

            // Valeurs avant/après
            $table->decimal('old_price', 15, 2)->nullable()->comment('Ancien prix');
            $table->decimal('new_price', 15, 2)->comment('Nouveau prix');

            // Calcul automatique de la variation
            $table->decimal('price_difference', 15, 2)->nullable()->comment('Différence (new - old)');
            $table->decimal('percentage_change', 8, 2)->nullable()->comment('Variation en pourcentage');

            // Contexte et raison
            $table->string('reason')->nullable()->comment('Raison du changement');
            $table->string('source')->default('manual')->comment('Source: manual, import, api, bulk_update');

            // Métadonnées
            $table->json('metadata')->nullable()->comment('Données supplémentaires (ex: référence fournisseur)');

            $table->timestamp('changed_at')->useCurrent()->comment('Date/heure du changement');
            $table->timestamps();

            // Index pour les recherches fréquentes
            $table->index(['product_id', 'changed_at']);
            $table->index(['product_variant_id', 'changed_at']);
            $table->index(['organization_id', 'changed_at']);
            $table->index(['price_type', 'changed_at']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_histories');
    }
};
