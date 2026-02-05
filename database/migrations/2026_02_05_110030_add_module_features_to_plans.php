<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\SubscriptionPlan;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ajoute les features modules aux plans existants
     */
    public function up(): void
    {
        // Modules à ajouter par plan
        $modulesByPlan = [
            // Gratuit: pas de modules avancés (pas de clients, fournisseurs, achats, factures)
            'free' => [],

            // Starter: uniquement clients et factures
            'starter' => [
                'module_clients',
                'module_invoices',
            ],

            // Professionnel: tous les modules
            'professional' => [
                'module_clients',
                'module_suppliers',
                'module_purchases',
                'module_invoices',
            ],

            // Entreprise: tous les modules
            'enterprise' => [
                'module_clients',
                'module_suppliers',
                'module_purchases',
                'module_invoices',
            ],
        ];

        foreach ($modulesByPlan as $slug => $modules) {
            $plan = SubscriptionPlan::where('slug', $slug)->first();
            if ($plan) {
                $existingFeatures = $plan->technical_features ?? [];
                $newFeatures = array_unique(array_merge($existingFeatures, $modules));
                $plan->update(['technical_features' => array_values($newFeatures)]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $modulesToRemove = [
            'module_clients',
            'module_suppliers',
            'module_purchases',
            'module_invoices',
        ];

        $plans = SubscriptionPlan::all();
        foreach ($plans as $plan) {
            $features = $plan->technical_features ?? [];
            $features = array_filter($features, fn($f) => !in_array($f, $modulesToRemove));
            $plan->update(['technical_features' => array_values($features)]);
        }
    }
};
