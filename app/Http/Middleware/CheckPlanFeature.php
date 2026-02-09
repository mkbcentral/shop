<?php

namespace App\Http\Middleware;

use App\Services\PlanLimitService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanFeature
{
    /**
     * Stock-related features that should be disabled for service organizations
     */
    protected array $stockRelatedFeatures = [
        'module_stock',
        'basic_inventory',
        'advanced_inventory',
        'stock_management',
        'inventory_tracking',
    ];

    public function __construct(
        protected PlanLimitService $planLimitService
    ) {}

    /**
     * Handle an incoming request.
     *
     * Vérifie si l'organisation a accès à une fonctionnalité selon son plan.
     * Usage: ->middleware('feature:api_access') ou ->middleware('feature:advanced_reports,export_pdf')
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $features  Fonctionnalités requises séparées par des virgules
     */
    public function handle(Request $request, Closure $next, string $features): Response
    {
        $user = $request->user();

        // Si pas d'utilisateur authentifié, continuer (sera bloqué par auth middleware)
        if (!$user) {
            return $next($request);
        }

        // Super-admin a accès à tout
        if ($user->hasRole('super-admin')) {
            return $next($request);
        }

        // Récupérer l'organisation courante
        $organization = app()->bound('current_organization')
            ? app('current_organization')
            : $user->defaultOrganization;

        if (!$organization) {
            return $next($request);
        }

        // Vérifier chaque fonctionnalité requise
        $requiredFeatures = array_map('trim', explode(',', $features));

        // Vérifier si c'est une fonctionnalité liée au stock et si c'est une organisation de services
        if ($this->isStockFeatureForServiceOrg($requiredFeatures, $organization)) {
            $message = "La gestion de stock n'est pas disponible pour les organisations de type \"Services\". Les services ne nécessitent pas de gestion de stock.";

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'reason' => 'service_organization_no_stock',
                    'business_activity' => $organization->business_activity instanceof \App\Enums\BusinessActivityType
                        ? $organization->business_activity->value
                        : $organization->business_activity,
                ], 403);
            }

            return redirect()
                ->route('dashboard')
                ->with('warning', $message);
        }

        $missingFeatures = [];

        foreach ($requiredFeatures as $feature) {
            if (!$this->planLimitService->hasFeature($feature, $organization)) {
                $missingFeatures[] = $feature;
            }
        }

        if (!empty($missingFeatures)) {
            $featureLabels = $this->getFeatureLabels($missingFeatures);
            $planName = $organization->subscription_plan->label();
            $message = $this->buildErrorMessage($featureLabels, $planName);

            // Répondre selon le type de requête
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'required_features' => $missingFeatures,
                    'current_plan' => $organization->subscription_plan->value,
                    'upgrade_required' => true,
                ], 403);
            }

            // Redirection avec message flash pour les requêtes web
            return redirect()
                ->back()
                ->with('error', $message)
                ->with('upgrade_required', true);
        }

        return $next($request);
    }

    /**
     * Check if the requested features are stock-related and the organization is a service organization
     */
    protected function isStockFeatureForServiceOrg(array $features, $organization): bool
    {
        // Check if any requested feature is stock-related
        $hasStockFeature = !empty(array_intersect($features, $this->stockRelatedFeatures));

        if (!$hasStockFeature) {
            return false;
        }

        // Check if the organization is a service-only organization
        return $organization->isServiceOrganization();
    }

    /**
     * Convertit les clés de fonctionnalités en labels lisibles
     */
    protected function getFeatureLabels(array $features): array
    {
        $labels = [
            'basic_pos' => 'Point de vente',
            'basic_inventory' => 'Gestion de stock de base',
            'basic_reports' => 'Rapports de base',
            'advanced_reports' => 'Rapports avancés',
            'multi_store' => 'Multi-magasins',
            'export_excel' => 'Export Excel',
            'export_pdf' => 'Export PDF',
            'api_access' => 'Accès API',
            'custom_reports' => 'Rapports personnalisés',
            'integrations' => 'Intégrations tierces',
            'unlimited' => 'Ressources illimitées',
            'dedicated_support' => 'Support dédié',
            'custom_development' => 'Développement sur mesure',
            'sla' => 'SLA garanti',
        ];

        return array_map(fn($f) => $labels[$f] ?? $f, $features);
    }

    /**
     * Construit le message d'erreur approprié
     */
    protected function buildErrorMessage(array $featureLabels, string $planName): string
    {
        $count = count($featureLabels);

        if ($count === 1) {
            return "La fonctionnalité \"{$featureLabels[0]}\" n'est pas disponible avec votre plan {$planName}. Passez à un plan supérieur pour y accéder.";
        }

        $lastFeature = array_pop($featureLabels);
        $featuresText = implode(', ', $featureLabels) . ' et ' . $lastFeature;

        return "Les fonctionnalités {$featuresText} ne sont pas disponibles avec votre plan {$planName}. Passez à un plan supérieur pour y accéder.";
    }
}
