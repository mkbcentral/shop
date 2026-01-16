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
        Schema::table('organizations', function (Blueprint $table) {
            // Payment status: pending, completed, failed, refunded, cancelled
            $table->string('payment_status')->default('pending')->after('subscription_ends_at');

            // Payment method: stripe, paypal, bank_transfer, etc.
            $table->string('payment_method')->nullable()->after('payment_status');

            // Payment transaction reference
            $table->string('payment_reference')->nullable()->after('payment_method');

            // Payment completed at
            $table->timestamp('payment_completed_at')->nullable()->after('payment_reference');

            // Trial days (useful for giving trial period)
            $table->integer('trial_days')->default(0)->after('is_trial');

            // Index for faster queries
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropIndex(['payment_status']);
            $table->dropColumn([
                'payment_status',
                'payment_method',
                'payment_reference',
                'payment_completed_at',
                'trial_days',
            ]);
        });
    }
};
