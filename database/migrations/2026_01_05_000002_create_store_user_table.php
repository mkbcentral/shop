<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('role', ['admin', 'manager', 'cashier', 'staff'])->default('staff');
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->unique(['store_id', 'user_id']);
            $table->index('user_id');
            $table->index('is_default');
        });

        // Ajouter current_store_id à la table users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('current_store_id')->nullable()->after('id')->constrained('stores')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['current_store_id']);
            $table->dropColumn('current_store_id');
        });

        Schema::dropIfExists('store_user');
    }
};
