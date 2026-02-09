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
        Schema::create('available_features', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->comment('Identifiant technique unique (ex: module_clients)');
            $table->string('label')->comment('Nom affiché (ex: Module Clients)');
            $table->text('description')->nullable()->comment('Description de la fonctionnalité');
            $table->string('category')->default('modules')->comment('Catégorie: core, modules, reports, stores, export, integrations');
            $table->string('icon')->nullable()->comment('Icône optionnelle (heroicon ou emoji)');
            $table->boolean('is_active')->default(true)->comment('Fonctionnalité disponible dans le système');
            $table->integer('sort_order')->default(0)->comment('Ordre d\'affichage');
            $table->timestamps();

            $table->index('category');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('available_features');
    }
};
