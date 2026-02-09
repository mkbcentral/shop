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
        // Fix product_types table
        Schema::table('product_types', function (Blueprint $table) {
            // Drop the old global unique constraint on slug
            $table->dropUnique(['slug']);

            // Add composite unique constraint (slug + organization_id)
            // This allows same slug across different organizations
            $table->unique(['slug', 'organization_id'], 'product_types_slug_org_unique');
        });

        // Fix categories table (same issue)
        Schema::table('categories', function (Blueprint $table) {
            // Drop the old global unique constraint on slug
            $table->dropUnique(['slug']);

            // Add composite unique constraint (slug + organization_id)
            $table->unique(['slug', 'organization_id'], 'categories_slug_org_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_types', function (Blueprint $table) {
            $table->dropUnique('product_types_slug_org_unique');
            $table->unique(['slug']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique('categories_slug_org_unique');
            $table->unique(['slug']);
        });
    }
};
