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
        // Modifier l'enum pour ajouter les nouvelles valeurs
        DB::statement("ALTER TABLE subscription_histories MODIFY COLUMN action ENUM(
            'created',
            'upgraded',
            'downgraded',
            'renewed',
            'cancelled',
            'expired',
            'reactivated',
            'trial_started',
            'trial_ended',
            'dates_modified',
            'extended'
        ) NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remettre l'enum original (sans les nouvelles valeurs)
        // Note: Cela échouera si des enregistrements utilisent les nouvelles valeurs
        DB::statement("ALTER TABLE subscription_histories MODIFY COLUMN action ENUM(
            'created',
            'upgraded',
            'downgraded',
            'renewed',
            'cancelled',
            'expired',
            'reactivated',
            'trial_started',
            'trial_ended'
        ) NOT NULL");
    }
};
