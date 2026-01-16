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
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('product_type_id')->nullable()->after('id')->constrained();
            $table->foreignId('parent_id')->nullable()->after('product_type_id')->constrained('categories');
            $table->integer('level')->default(0)->after('parent_id');
            $table->string('path')->nullable()->after('level');
            $table->string('icon')->nullable()->after('slug');
            $table->boolean('is_active')->default(true)->after('icon');

            $table->index(['product_type_id', 'parent_id']);
            $table->index('path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['product_type_id']);
            $table->dropForeign(['parent_id']);
            $table->dropIndex(['product_type_id', 'parent_id']);
            $table->dropIndex(['path']);
            $table->dropColumn(['product_type_id', 'parent_id', 'level', 'path', 'icon', 'is_active']);
        });
    }
};
