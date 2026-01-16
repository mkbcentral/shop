<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * Service de vérification des limites du plan d'abonnement
 * Utilise les données des plans stockées dans le cache/DB
 */
class PlanLimitService
{
    /**
     * Récupère l'organisation courante
     */
    public function getCurrentOrganization(): ?Organization
    {
        $user = Auth::user();
        
        if (!$user) {
            return null;
        }

        $organizationId = session('current_organization_id') ?? $user->default_organization_id;
        
        return Organization::find($organizationId);
    }

    /**
     * Récupère les limites du plan depuis le cache/DB
     */
    public function getPlanLimits(string $planSlug): array
    {
        $plans = SubscriptionService::getPlansFromCache();
        
        if (isset($plans[$planSlug])) {
            return [
                'max_stores' => $plans[$planSlug]['max_stores'] ?? 1,
                'max_users' => $plans[$planSlug]['max_users'] ?? 2,
                'max_products' => $plans[$planSlug]['max_products'] ?? 100,
            ];
        }

        // Fallback sur les constantes du service
        return SubscriptionService::PLAN_LIMITS[$planSlug] ?? SubscriptionService::PLAN_LIMITS['free'];
    }

    /**
     * Vérifie si l'organisation peut ajouter un magasin
     */
    public function canAddStore(?Organization $organization = null): bool
    {
        $organization = $organization ?? $this->getCurrentOrganization();
        
        if (!$organization) {
            return false;
        }

        // Utilise les limites de l'organisation (qui sont définies lors de la souscription)
        return $organization->stores()->count() < $organization->max_stores;
    }

    /**
     * Vérifie si l'organisation peut ajouter un utilisateur
     */
    public function canAddUser(?Organization $organization = null): bool
    {
        $organization = $organization ?? $this->getCurrentOrganization();
        
        if (!$organization) {
            return false;
        }

        return $organization->members()->count() < $organization->max_users;
    }

    /**
     * Vérifie si l'organisation peut ajouter un produit
     */
    public function canAddProduct(?Organization $organization = null): bool
    {
        $organization = $organization ?? $this->getCurrentOrganization();
        
        if (!$organization) {
            return false;
        }

        $currentProductCount = $this->getProductCount($organization);
        
        return $currentProductCount < $organization->max_products;
    }

    /**
     * Compte le nombre total de produits pour l'organisation
     */
    public function getProductCount(?Organization $organization = null): int
    {
        $organization = $organization ?? $this->getCurrentOrganization();
        
        if (!$organization) {
            return 0;
        }

        // Compte les produits de tous les magasins de l'organisation
        return Product::whereIn('store_id', $organization->stores()->pluck('id'))->count();
    }

    /**
     * Récupère les statistiques d'utilisation du plan
     */
    public function getUsageStats(?Organization $organization = null): array
    {
        $organization = $organization ?? $this->getCurrentOrganization();
        
        if (!$organization) {
            return [
                'stores' => ['current' => 0, 'max' => 0, 'percentage' => 0, 'can_add' => false],
                'users' => ['current' => 0, 'max' => 0, 'percentage' => 0, 'can_add' => false],
                'products' => ['current' => 0, 'max' => 0, 'percentage' => 0, 'can_add' => false],
            ];
        }

        $storeCount = $organization->stores()->count();
        $userCount = $organization->members()->count();
        $productCount = $this->getProductCount($organization);

        // Récupérer les infos du plan depuis le cache pour affichage
        $planSlug = $organization->subscription_plan->value;
        $plans = SubscriptionService::getPlansFromCache();
        $planInfo = $plans[$planSlug] ?? [];

        return [
            'stores' => [
                'current' => $storeCount,
                'max' => $organization->max_stores,
                'remaining' => max(0, $organization->max_stores - $storeCount),
                'percentage' => $organization->max_stores > 0 
                    ? round(($storeCount / $organization->max_stores) * 100) 
                    : 0,
                'can_add' => $storeCount < $organization->max_stores,
            ],
            'users' => [
                'current' => $userCount,
                'max' => $organization->max_users,
                'remaining' => max(0, $organization->max_users - $userCount),
                'percentage' => $organization->max_users > 0 
                    ? round(($userCount / $organization->max_users) * 100) 
                    : 0,
                'can_add' => $userCount < $organization->max_users,
            ],
            'products' => [
                'current' => $productCount,
                'max' => $organization->max_products,
                'remaining' => max(0, $organization->max_products - $productCount),
                'percentage' => $organization->max_products > 0 
                    ? round(($productCount / $organization->max_products) * 100) 
                    : 0,
                'can_add' => $productCount < $organization->max_products,
            ],
            'plan' => [
                'name' => $planInfo['name'] ?? $organization->subscription_plan->label(),
                'value' => $planSlug,
                'price' => $planInfo['price'] ?? 0,
                'features' => $planInfo['features'] ?? [],
            ],
        ];
    }

    /**
     * Vérifie toutes les limites et retourne les erreurs
     */
    public function checkLimits(?Organization $organization = null): array
    {
        $organization = $organization ?? $this->getCurrentOrganization();
        $errors = [];
        
        if (!$organization) {
            $errors[] = 'Aucune organisation trouvée.';
            return $errors;
        }

        $stats = $this->getUsageStats($organization);

        if ($stats['stores']['percentage'] >= 100) {
            $errors[] = "Limite de magasins atteinte ({$stats['stores']['current']}/{$stats['stores']['max']}). Passez à un plan supérieur.";
        }

        if ($stats['users']['percentage'] >= 100) {
            $errors[] = "Limite d'utilisateurs atteinte ({$stats['users']['current']}/{$stats['users']['max']}). Passez à un plan supérieur.";
        }

        if ($stats['products']['percentage'] >= 100) {
            $errors[] = "Limite de produits atteinte ({$stats['products']['current']}/{$stats['products']['max']}). Passez à un plan supérieur.";
        }

        return $errors;
    }

    /**
     * Vérifie si une fonctionnalité est disponible pour le plan
     * Utilise les features du cache/DB
     */
    public function hasFeature(string $feature, ?Organization $organization = null): bool
    {
        $organization = $organization ?? $this->getCurrentOrganization();
        
        if (!$organization) {
            return false;
        }

        $planSlug = $organization->subscription_plan->value;
        $plans = SubscriptionService::getPlansFromCache();
        
        // Récupérer les features du plan
        $planFeatures = $plans[$planSlug]['features'] ?? [];
        
        // Vérifier si la feature existe dans la liste (recherche partielle)
        foreach ($planFeatures as $planFeature) {
            if (stripos($planFeature, $feature) !== false) {
                return true;
            }
        }

        // Définition des fonctionnalités techniques par plan (non affichées mais actives)
        $technicalFeatures = [
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

        $availableTechFeatures = $technicalFeatures[$planSlug] ?? [];
        
        return in_array($feature, $availableTechFeatures);
    }

    /**
     * Génère un message d'alerte si les limites sont proches
     */
    public function getWarnings(?Organization $organization = null): array
    {
        $organization = $organization ?? $this->getCurrentOrganization();
        $warnings = [];
        
        if (!$organization) {
            return $warnings;
        }

        $stats = $this->getUsageStats($organization);

        // Alertes à 80% d'utilisation
        if ($stats['stores']['percentage'] >= 80 && $stats['stores']['percentage'] < 100) {
            $remaining = $stats['stores']['remaining'];
            $warnings[] = "⚠️ Vous approchez de la limite de magasins. Il vous reste {$remaining} magasin(s) disponible(s).";
        }

        if ($stats['users']['percentage'] >= 80 && $stats['users']['percentage'] < 100) {
            $remaining = $stats['users']['remaining'];
            $warnings[] = "⚠️ Vous approchez de la limite d'utilisateurs. Il vous reste {$remaining} place(s) disponible(s).";
        }

        if ($stats['products']['percentage'] >= 80 && $stats['products']['percentage'] < 100) {
            $remaining = $stats['products']['remaining'];
            $warnings[] = "⚠️ Vous approchez de la limite de produits. Il vous reste {$remaining} produit(s) disponible(s).";
        }

        return $warnings;
    }

    /**
     * Récupère les suggestions de mise à niveau
     */
    public function getUpgradeSuggestion(?Organization $organization = null): ?array
    {
        $organization = $organization ?? $this->getCurrentOrganization();
        
        if (!$organization) {
            return null;
        }

        $stats = $this->getUsageStats($organization);
        $currentPlanSlug = $organization->subscription_plan->value;
        $plans = SubscriptionService::getPlansFromCache();
        
        // Trouver le plan supérieur
        $planOrder = ['free' => 0, 'starter' => 1, 'professional' => 2, 'enterprise' => 3];
        $currentOrder = $planOrder[$currentPlanSlug] ?? 0;
        
        // Si déjà au plan max, pas de suggestion
        if ($currentOrder >= 3) {
            return null;
        }

        // Si utilisation > 80%, suggérer une mise à niveau
        $needsUpgrade = $stats['stores']['percentage'] >= 80 
            || $stats['users']['percentage'] >= 80 
            || $stats['products']['percentage'] >= 80;

        if (!$needsUpgrade) {
            return null;
        }

        // Trouver le plan supérieur
        $nextPlanSlug = array_search($currentOrder + 1, $planOrder);
        $nextPlan = $plans[$nextPlanSlug] ?? null;

        if (!$nextPlan) {
            return null;
        }

        return [
            'current_plan' => $currentPlanSlug,
            'suggested_plan' => $nextPlanSlug,
            'suggested_plan_name' => $nextPlan['name'] ?? $nextPlanSlug,
            'suggested_plan_price' => $nextPlan['price'] ?? 0,
            'reason' => 'Vous approchez des limites de votre plan actuel.',
        ];
    }
}
