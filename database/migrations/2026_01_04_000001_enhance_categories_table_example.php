<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration example for enhancing the categories table
 * 
 * Uncomment the fields you want to add and run: php artisan migrate
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Add additional columns as needed
            
            // $table->boolean('is_active')->default(true)->after('slug');
            // $table->integer('display_order')->default(0)->after('is_active');
            // $table->string('icon')->nullable()->after('display_order');
            // $table->string('color')->nullable()->after('icon');
            // $table->string('image')->nullable()->after('color');
            // $table->text('meta_title')->nullable()->after('image');
            // $table->text('meta_description')->nullable()->after('meta_title');
            // $table->json('metadata')->nullable()->after('meta_description');
            // $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade');
            // $table->softDeletes(); // For soft delete support
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Drop the added columns in reverse order
            
            // $table->dropColumn([
            //     'is_active',
            //     'display_order',
            //     'icon',
            //     'color',
            //     'image',
            //     'meta_title',
            //     'meta_description',
            //     'metadata',
            //     'parent_id',
            // ]);
            // $table->dropSoftDeletes();
        });
    }
};
