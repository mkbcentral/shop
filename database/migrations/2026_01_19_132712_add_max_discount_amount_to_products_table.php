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
            // Montant maximum de remise autorisé sur ce produit
            // Si null, pas de limite de remise
            // Ce montant ne peut jamais dépasser le prix de vente
            $table->decimal('max_discount_amount', 15, 2)->nullable()->after('price')
                ->comment('Montant maximum de réduction autorisée sur ce produit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('max_discount_amount');
        });
    }
};
