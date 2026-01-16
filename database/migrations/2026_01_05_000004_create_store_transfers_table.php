<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_number')->unique();
            $table->foreignId('from_store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('to_store_id')->constrained('stores')->cascadeOnDelete();
            $table->enum('status', ['pending', 'approved', 'in_transit', 'completed', 'cancelled'])->default('pending');
            $table->datetime('transfer_date');
            $table->datetime('expected_arrival_date')->nullable();
            $table->datetime('actual_arrival_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('transfer_number');
            $table->index('from_store_id');
            $table->index('to_store_id');
            $table->index('status');
            $table->index('transfer_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_transfers');
    }
};
