<?php

namespace App\Livewire\Admin;

use App\Models\SubscriptionPayment;
use App\Services\SubscriptionService;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class SubscriptionSettings extends Component
{
    // Plans configuration
    public array $plans = [];
    
    // Edit modal
    public bool $showEditModal = false;
    public string $editingPlan = '';
    public array $editForm = [
        'name' => '',
        'price' => 0,
        'max_stores' => 1,
        'max_users' => 3,
        'max_products' => 100,
        'features' => [],
    ];
    
    // Discount settings
    public array $discounts = [
        '3_months' => 5,
        '6_months' => 10,
        '12_months' => 20,
    ];

    // General settings
    public int $trialDays = 14;
    public string $currency = 'CDF';

    public function mount(): void
    {
        $this->authorize('viewAny', \App\Models\Organization::class);
        $this->loadSettings();
    }

    public function loadSettings(): void
    {
        // Charger les paramètres depuis le cache ou utiliser les valeurs par défaut
        // Convertir en array pour éviter les problèmes avec stdClass
        $cachedPlans = Cache::get('subscription_plans');
        $this->plans = $cachedPlans ? $this->ensureArray($cachedPlans) : $this->getDefaultPlans();
        
        $cachedDiscounts = Cache::get('subscription_discounts');
        $this->discounts = $cachedDiscounts ? $this->ensureArray($cachedDiscounts) : [
            '3_months' => 5,
            '6_months' => 10,
            '12_months' => 20,
        ];
        
        $this->trialDays = (int) Cache::get('subscription_trial_days', 14);
        $this->currency = Cache::get('subscription_currency', 'CDF') ?: 'CDF';
    }

    /**
     * Convertit récursivement un objet/array en array PHP natif
     */
    protected function ensureArray(mixed $data): array
    {
        if (is_object($data)) {
            $data = (array) $data;
        }
        
        if (!is_array($data)) {
            return [];
        }
        
        foreach ($data as $key => $value) {
            if (is_object($value) || is_array($value)) {
                $data[$key] = $this->ensureArray($value);
            }
        }
        
        return $data;
    }

    public function getDefaultPlans(): array
    {
        return [
            'free' => [
                'name' => 'Gratuit',
                'slug' => 'free',
                'price' => 0,
                'max_stores' => 1,
                'max_users' => 3,
                'max_products' => 100,
                'features' => [
                    'Jusqu\'à 1 magasin',
                    'Jusqu\'à 3 utilisateurs',
                    'Jusqu\'à 100 produits',
                    'Rapports de base',
                    'Support par email',
                ],
                'is_popular' => false,
                'color' => 'gray',
            ],
            'starter' => [
                'name' => 'Starter',
                'slug' => 'starter',
                'price' => 9900,
                'max_stores' => 3,
                'max_users' => 10,
                'max_products' => 1000,
                'features' => [
                    'Jusqu\'à 3 magasins',
                    'Jusqu\'à 10 utilisateurs',
                    'Jusqu\'à 1 000 produits',
                    'Rapports avancés',
                    'Support prioritaire',
                    'Exportation des données',
                ],
                'is_popular' => false,
                'color' => 'blue',
            ],
            'professional' => [
                'name' => 'Professionnel',
                'slug' => 'professional',
                'price' => 24900,
                'max_stores' => 10,
                'max_users' => 50,
                'max_products' => 10000,
                'features' => [
                    'Jusqu\'à 10 magasins',
                    'Jusqu\'à 50 utilisateurs',
                    'Jusqu\'à 10 000 produits',
                    'Rapports personnalisés',
                    'Support téléphonique',
                    'API access',
                    'Multi-devises',
                ],
                'is_popular' => true,
                'color' => 'purple',
            ],
            'enterprise' => [
                'name' => 'Entreprise',
                'slug' => 'enterprise',
                'price' => 49900,
                'max_stores' => 100,
                'max_users' => 500,
                'max_products' => 100000,
                'features' => [
                    'Jusqu\'à 100 magasins',
                    'Jusqu\'à 500 utilisateurs',
                    'Jusqu\'à 100 000 produits',
                    'Rapports sur mesure',
                    'Support dédié 24/7',
                    'API illimité',
                    'Multi-devises',
                    'Formation personnalisée',
                    'SLA garanti',
                ],
                'is_popular' => false,
                'color' => 'amber',
            ],
        ];
    }

    public function openEditModal(string $plan): void
    {
        $this->editingPlan = $plan;
        $this->editForm = $this->plans[$plan];
        $this->editForm['features_text'] = implode("\n", $this->editForm['features'] ?? []);
        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->editingPlan = '';
        $this->editForm = [];
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

        $this->plans[$this->editingPlan] = [
            'name' => $this->editForm['name'],
            'slug' => $this->editingPlan,
            'price' => (int) $this->editForm['price'],
            'max_stores' => (int) $this->editForm['max_stores'],
            'max_users' => (int) $this->editForm['max_users'],
            'max_products' => (int) $this->editForm['max_products'],
            'features' => array_values($features),
            'is_popular' => $this->plans[$this->editingPlan]['is_popular'] ?? false,
            'color' => $this->plans[$this->editingPlan]['color'] ?? 'gray',
        ];

        // Sauvegarder dans le cache (persistant)
        Cache::forever('subscription_plans', $this->plans);

        $this->closeEditModal();
        $this->dispatch('show-toast', message: 'Plan mis à jour avec succès !', type: 'success');
    }

    public function saveDiscounts(): void
    {
        $this->validate([
            'discounts.3_months' => 'required|numeric|min:0|max:100',
            'discounts.6_months' => 'required|numeric|min:0|max:100',
            'discounts.12_months' => 'required|numeric|min:0|max:100',
        ]);

        Cache::forever('subscription_discounts', $this->discounts);
        $this->dispatch('show-toast', message: 'Réductions mises à jour !', type: 'success');
    }

    public function saveGeneralSettings(): void
    {
        $this->validate([
            'trialDays' => 'required|integer|min:0|max:90',
            'currency' => 'required|string|size:3',
        ]);

        Cache::forever('subscription_trial_days', $this->trialDays);
        Cache::forever('subscription_currency', $this->currency);
        
        $this->dispatch('show-toast', message: 'Paramètres généraux mis à jour !', type: 'success');
    }

    public function togglePopular(string $plan): void
    {
        // Désactiver "populaire" sur tous les plans
        foreach ($this->plans as $key => $p) {
            $this->plans[$key]['is_popular'] = ($key === $plan) ? !$p['is_popular'] : false;
        }
        
        Cache::forever('subscription_plans', $this->plans);
        $this->dispatch('show-toast', message: 'Plan mis en avant !', type: 'success');
    }

    public function resetToDefaults(): void
    {
        $this->plans = $this->getDefaultPlans();
        $this->discounts = [
            '3_months' => 5,
            '6_months' => 10,
            '12_months' => 20,
        ];
        $this->trialDays = 14;
        $this->currency = 'CDF';

        Cache::forget('subscription_plans');
        Cache::forget('subscription_discounts');
        Cache::forget('subscription_trial_days');
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
            ->pluck('count', 'subscription_plan')
            ->toArray();

        $totalRevenue = SubscriptionPayment::query()
            ->where('status', 'completed')
            ->sum('total');

        $monthlyRevenue = SubscriptionPayment::query()
            ->where('status', 'completed')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('total');

        return [
            'by_plan' => $stats,
            'total_organizations' => array_sum($stats),
            'total_revenue' => $totalRevenue,
            'monthly_revenue' => $monthlyRevenue,
        ];
    }

    public function render()
    {
        return view('livewire.admin.subscription-settings', [
            'stats' => $this->stats,
        ]);
    }
}
