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
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_type_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('code');
            $table->enum('type', ['text', 'number', 'select', 'boolean', 'date', 'color']);
            $table->json('options')->nullable();
            $table->string('unit')->nullable();
            $table->text('default_value')->nullable();
            $table->boolean('is_required')->default(false);
            $table->boolean('is_variant_attribute')->default(false);
            $table->boolean('is_filterable')->default(true);
            $table->boolean('is_visible')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();

            $table->unique(['product_type_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_attributes');
    }
};
