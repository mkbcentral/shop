<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change ENUM to VARCHAR for flexibility with roles from the roles table
        DB::statement("ALTER TABLE organization_user MODIFY COLUMN role VARCHAR(50) NOT NULL DEFAULT 'member'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to ENUM (only if data is compatible)
        DB::statement("ALTER TABLE organization_user MODIFY COLUMN role ENUM('owner', 'admin', 'manager', 'accountant', 'member') NOT NULL DEFAULT 'member'");
    }
};
