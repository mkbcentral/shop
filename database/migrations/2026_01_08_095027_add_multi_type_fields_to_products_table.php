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
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('product_type_id')->nullable()->after('store_id')->constrained();
            $table->date('expiry_date')->nullable()->after('status');
            $table->date('manufacture_date')->nullable()->after('expiry_date');
            $table->decimal('weight', 10, 3)->nullable()->after('manufacture_date');
            $table->decimal('length', 10, 2)->nullable()->after('weight');
            $table->decimal('width', 10, 2)->nullable()->after('length');
            $table->decimal('height', 10, 2)->nullable()->after('width');
            $table->string('unit_of_measure')->default('piece')->after('height');
            $table->string('brand')->nullable()->after('unit_of_measure');
            $table->string('model')->nullable()->after('brand');

            $table->index('product_type_id');
            $table->index('expiry_date');
            $table->index('brand');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['product_type_id']);
            $table->dropIndex(['product_type_id']);
            $table->dropIndex(['expiry_date']);
            $table->dropIndex(['brand']);
            $table->dropColumn([
                'product_type_id', 'expiry_date', 'manufacture_date',
                'weight', 'length', 'width', 'height',
                'unit_of_measure', 'brand', 'model'
            ]);
        });
    }
};
