<?php

namespace App\Livewire\Admin;

use App\Models\SubscriptionPlan;
use App\Services\SubscriptionService;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class SubscriptionSettings extends Component
{
    // Edit modal
    public ?int $editingPlanId = null;
    public array $editForm = [
        'name' => '',
        'price' => 0,
        'max_stores' => 1,
        'max_users' => 3,
        'max_products' => 100,
        'features' => [],
        'technical_features' => [],
    ];

    public string $currency = 'CDF';

    // Pour la gestion des fonctionnalités
    public bool $showFeaturesModal = false;
    public ?int $editingFeaturesForPlanId = null;
    public array $selectedFeatures = [];

    public function mount(): void
    {
        $this->authorize('viewAny', \App\Models\Organization::class);
        $this->loadSettings();
    }

    public function loadSettings(): void
    {
        $this->currency = Cache::get('subscription_currency', 'CDF') ?: 'CDF';
    }

    public function openEditModal(int $planId): void
    {
        $plan = SubscriptionService::getPlanById($planId);

        if (!$plan) {
            $this->dispatch('show-toast', message: 'Plan introuvable', type: 'error');
            return;
        }

        $this->editingPlanId = $planId;
        $this->editForm = [
            'name' => $plan->name,
            'slug' => $plan->slug,
            'price' => $plan->price,
            'max_stores' => $plan->max_stores,
            'max_users' => $plan->max_users,
            'max_products' => $plan->max_products,
            'features_text' => implode("\n", $plan->features ?? []),
            'technical_features' => $plan->technical_features ?? [],
        ];

        $this->dispatch('open-plan-modal');
    }

    /**
     * Ouvrir le modal de gestion des fonctionnalités techniques
     */
    public function openFeaturesModal(int $planId): void
    {
        $plan = SubscriptionPlan::find($planId);

        if (!$plan) {
            $this->dispatch('show-toast', message: 'Plan introuvable', type: 'error');
            return;
        }

        $this->editingFeaturesForPlanId = $planId;
        $this->selectedFeatures = $plan->technical_features ?? [];
        $this->showFeaturesModal = true;
    }

    /**
     * Fermer le modal des fonctionnalités
     */
    public function closeFeaturesModal(): void
    {
        $this->showFeaturesModal = false;
        $this->editingFeaturesForPlanId = null;
        $this->selectedFeatures = [];
    }

    /**
     * Sauvegarder les fonctionnalités techniques
     */
    public function saveTechnicalFeatures(): void
    {
        if (!$this->editingFeaturesForPlanId) {
            return;
        }

        $plan = SubscriptionPlan::find($this->editingFeaturesForPlanId);

        if (!$plan) {
            $this->dispatch('show-toast', message: 'Plan introuvable', type: 'error');
            return;
        }

        $plan->update([
            'technical_features' => array_values($this->selectedFeatures),
        ]);

        // Invalider tous les caches liés aux plans et menus
        $this->invalidateAllRelatedCaches($plan->slug);

        $this->closeFeaturesModal();
        $this->dispatch('show-toast', message: 'Fonctionnalités mises à jour avec succès !', type: 'success');
    }

    /**
     * Invalider tous les caches liés aux plans et menus
     */
    private function invalidateAllRelatedCaches(string $planSlug): void
    {
        // Invalider le cache des plans
        Cache::forget('subscription_plans');

        // Invalider tous les caches de menus via le versioning
        \App\Services\MenuService::invalidateAllMenuCaches();
    }

    /**
     * Basculer une fonctionnalité
     */
    public function toggleFeature(string $feature): void
    {
        if (in_array($feature, $this->selectedFeatures)) {
            $this->selectedFeatures = array_values(array_filter(
                $this->selectedFeatures,
                fn($f) => $f !== $feature
            ));
        } else {
            $this->selectedFeatures[] = $feature;
        }
    }

    /**
     * Sélectionner toutes les fonctionnalités
     */
    public function selectAllFeatures(): void
    {
        $this->selectedFeatures = array_keys(SubscriptionPlan::getAvailableFeatures());
    }

    /**
     * Désélectionner toutes les fonctionnalités
     */
    public function deselectAllFeatures(): void
    {
        $this->selectedFeatures = [];
    }

    /**
     * Obtenir le nom du plan en cours d'édition pour les fonctionnalités
     */
    public function getEditingFeaturesPlanNameProperty(): ?string
    {
        if (!$this->editingFeaturesForPlanId) {
            return null;
        }

        $plan = SubscriptionPlan::find($this->editingFeaturesForPlanId);
        return $plan?->name;
    }

    public function savePlan(): void
    {
        $this->validate([
            'editForm.name' => 'required|string|max:50',
            'editForm.price' => 'required|numeric|min:0',
            'editForm.max_stores' => 'required|integer|min:1',
            'editForm.max_users' => 'required|integer|min:1',
            'editForm.max_products' => 'required|integer|min:1',
            'editForm.features_text' => 'nullable|string',
        ]);

        // Convertir le texte des fonctionnalités en tableau
        $features = array_filter(
            array_map('trim', explode("\n", $this->editForm['features_text'] ?? '')),
            fn($f) => !empty($f)
        );

        SubscriptionService::updatePlan($this->editingPlanId, [
            'name' => $this->editForm['name'],
            'price' => (int) $this->editForm['price'],
            'max_stores' => (int) $this->editForm['max_stores'],
            'max_users' => (int) $this->editForm['max_users'],
            'max_products' => (int) $this->editForm['max_products'],
            'features' => array_values($features),
        ]);

        $this->dispatch('close-plan-modal');
        $this->dispatch('show-toast', message: 'Plan mis à jour avec succès !', type: 'success');
    }

    public function togglePopular(int $planId): void
    {
        SubscriptionService::togglePlanPopularity($planId);
        $this->dispatch('show-toast', message: 'Plan mis en avant !', type: 'success');
    }

    public function resetToDefaults(): void
    {
        // Réexécuter le seeder pour réinitialiser les plans
        Artisan::call('db:seed', ['--class' => 'SubscriptionPlanSeeder']);

        $this->currency = 'CDF';
        Cache::forget('subscription_currency');

        $this->dispatch('show-toast', message: 'Paramètres réinitialisés !', type: 'success');
    }

    /**
     * Obtenir les statistiques des abonnements
     */
    public function getStatsProperty(): array
    {
        $stats = \App\Models\Organization::query()
            ->selectRaw('subscription_plan, COUNT(*) as count')
            ->groupBy('subscription_plan')
            ->orderBy('subscription_plan')
            ->pluck('count', 'subscription_plan')
            ->toArray();

        $revenueStats = SubscriptionService::getRevenueStats();

        return [
            'by_plan' => $stats,
            'total_organizations' => array_sum($stats),
            'total_revenue' => $revenueStats['total_revenue'],
            'monthly_revenue' => $revenueStats['monthly_revenue'],
        ];
    }

    public function render()
    {
        $plans = SubscriptionService::getPlansFromDatabase();
        $availableFeatures = SubscriptionPlan::getAvailableFeatures();
        $featureCategories = SubscriptionPlan::getFeatureCategories();

        return view('livewire.admin.subscription-settings', [
            'plans' => $plans,
            'stats' => $this->stats,
            'availableFeatures' => $availableFeatures,
            'featureCategories' => $featureCategories,
        ]);
    }
}
