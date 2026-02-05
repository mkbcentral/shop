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
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->json('technical_features')->nullable()->after('features')
                ->comment('Fonctionnalités techniques activées pour ce plan (clés internes)');
        });

        // Mettre à jour les plans existants avec les fonctionnalités techniques par défaut
        $this->seedTechnicalFeatures();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn('technical_features');
        });
    }

    /**
     * Ajouter les fonctionnalités techniques par défaut aux plans existants
     */
    protected function seedTechnicalFeatures(): void
    {
        $features = [
            'free' => [
                'basic_pos',
                'basic_inventory',
                'basic_reports',
            ],
            'starter' => [
                'basic_pos',
                'basic_inventory',
                'basic_reports',
                'advanced_reports',
                'multi_store',
                'export_excel',
            ],
            'professional' => [
                'basic_pos',
                'basic_inventory',
                'basic_reports',
                'advanced_reports',
                'multi_store',
                'export_excel',
                'export_pdf',
                'api_access',
                'custom_reports',
                'integrations',
            ],
            'enterprise' => [
                'basic_pos',
                'basic_inventory',
                'basic_reports',
                'advanced_reports',
                'multi_store',
                'export_excel',
                'export_pdf',
                'api_access',
                'custom_reports',
                'integrations',
                'unlimited',
                'dedicated_support',
                'custom_development',
                'sla',
            ],
        ];

        foreach ($features as $slug => $planFeatures) {
            \App\Models\SubscriptionPlan::where('slug', $slug)->update([
                'technical_features' => $planFeatures,
            ]);
        }
    }
};
