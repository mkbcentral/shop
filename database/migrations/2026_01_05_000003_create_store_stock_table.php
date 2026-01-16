<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('product_variant_id')->constrained('product_variants')->cascadeOnDelete();
            $table->integer('quantity')->default(0);
            $table->integer('low_stock_threshold')->default(10);
            $table->integer('min_stock_threshold')->default(0);
            $table->date('last_inventory_date')->nullable();
            $table->timestamps();

            $table->unique(['store_id', 'product_variant_id']);
            $table->index('store_id');
            $table->index('product_variant_id');
            $table->index('quantity');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_stock');
    }
};
