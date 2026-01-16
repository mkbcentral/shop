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
        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('size')->nullable()->change();
            $table->string('color')->nullable()->change();

            $table->string('variant_name')->nullable()->after('product_id');
            $table->string('serial_number')->nullable()->after('barcode');
            $table->date('expiry_date')->nullable()->after('serial_number');
            $table->decimal('weight', 10, 3)->nullable()->after('expiry_date');

            $table->index('serial_number');
            $table->index('expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropIndex(['serial_number']);
            $table->dropIndex(['expiry_date']);
            $table->dropColumn(['variant_name', 'serial_number', 'expiry_date', 'weight']);

            $table->string('size')->nullable(false)->change();
            $table->string('color')->nullable(false)->change();
        });
    }
};
