<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\BusinessActivityType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add business_activity to organizations table
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('business_activity', 20)->default('retail')->after('type');
            $table->index('business_activity');
        });

        // Add compatible_activities to product_types table
        Schema::table('product_types', function (Blueprint $table) {
            // JSON array of compatible business activities
            // null = compatible with all activities
            $table->json('compatible_activities')->nullable()->after('is_service');
        });

        // Update existing organizations to 'mixed' as default (they can sell everything)
        \DB::table('organizations')->update(['business_activity' => 'mixed']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropIndex(['business_activity']);
            $table->dropColumn('business_activity');
        });

        Schema::table('product_types', function (Blueprint $table) {
            $table->dropColumn('compatible_activities');
        });
    }
};
