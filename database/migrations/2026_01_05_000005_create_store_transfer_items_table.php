<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_transfer_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_transfer_id')->constrained('store_transfers')->cascadeOnDelete();
            $table->foreignId('product_variant_id')->constrained('product_variants')->cascadeOnDelete();
            $table->integer('quantity_requested');
            $table->integer('quantity_sent')->nullable();
            $table->integer('quantity_received')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('store_transfer_id');
            $table->index('product_variant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_transfer_items');
    }
};
