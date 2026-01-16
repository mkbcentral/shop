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
        // Table des items de menu
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');                    // Nom affiché
            $table->string('code')->unique();          // Code unique (ex: 'products', 'sales.index')
            $table->text('icon')->nullable();          // Icône SVG path
            $table->string('route')->nullable();       // Nom de la route Laravel
            $table->string('url')->nullable();         // URL si pas de route
            $table->foreignId('parent_id')->nullable()->constrained('menu_items')->onDelete('cascade');
            $table->integer('order')->default(0);      // Ordre d'affichage
            $table->string('section')->nullable();     // Section (Inventaire, Transactions, etc.)
            $table->boolean('is_active')->default(true);
            $table->string('badge_type')->nullable();  // Type de badge (count, text)
            $table->string('badge_color')->default('indigo');
            $table->timestamps();

            $table->index(['parent_id', 'order']);
            $table->index('section');
        });

        // Table pivot pour les permissions de menu par rôle
        Schema::create('menu_item_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['menu_item_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_item_role');
        Schema::dropIfExists('menu_items');
    }
};
