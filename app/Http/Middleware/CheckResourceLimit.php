<?php

namespace App\Http\Middleware;

use App\Services\PlanLimitService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware de vérification des limites de ressources selon le plan d'abonnement
 *
 * Usage: ->middleware('resource.limit:products') ou ->middleware('resource.limit:stores,users')
 *
 * Types de ressources supportés:
 * - products: Limite de produits
 * - stores: Limite de magasins
 * - users: Limite d'utilisateurs
 */
class CheckResourceLimit
{
    public function __construct(
        protected PlanLimitService $planLimitService
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $resources  Types de ressources à vérifier (séparés par des virgules)
     */
    public function handle(Request $request, Closure $next, string $resources): Response
    {
        $user = $request->user();

        // Si pas d'utilisateur authentifié, continuer (sera bloqué par auth middleware)
        if (!$user) {
            return $next($request);
        }

        // Super-admin a accès illimité
        if ($user->hasRole('super-admin')) {
            return $next($request);
        }

        // Récupérer l'organisation courante
        $organization = $this->getCurrentOrganization($user);

        if (!$organization) {
            return $this->errorResponse($request, 'Aucune organisation trouvée.', 403);
        }

        // Vérifier chaque type de ressource
        $resourceTypes = array_map('trim', explode(',', $resources));

        foreach ($resourceTypes as $resourceType) {
            $checkResult = $this->checkResourceLimit($resourceType, $organization);

            if (!$checkResult['can_add']) {
                return $this->errorResponse(
                    $request,
                    $checkResult['message'],
                    403,
                    [
                        'resource_type' => $resourceType,
                        'current' => $checkResult['current'],
                        'max' => $checkResult['max'],
                        'upgrade_required' => true,
                        'current_plan' => $organization->subscription_plan->value,
                    ]
                );
            }
        }

        return $next($request);
    }

    /**
     * Récupère l'organisation courante de l'utilisateur
     */
    protected function getCurrentOrganization($user): ?\App\Models\Organization
    {
        return app()->bound('current_organization')
            ? app('current_organization')
            : ($user->currentOrganization ?? $user->defaultOrganization);
    }

    /**
     * Vérifie la limite pour un type de ressource spécifique
     */
    protected function checkResourceLimit(string $resourceType, $organization): array
    {
        return match ($resourceType) {
            'products' => $this->checkProductsLimit($organization),
            'stores' => $this->checkStoresLimit($organization),
            'users' => $this->checkUsersLimit($organization),
            default => ['can_add' => true, 'message' => '', 'current' => 0, 'max' => -1],
        };
    }

    /**
     * Vérifie la limite de produits
     */
    protected function checkProductsLimit($organization): array
    {
        $usage = $organization->getProductsUsage();

        return [
            'can_add' => $organization->canAddProduct(),
            'message' => sprintf(
                'Limite de produits atteinte (%d/%s). Passez à un plan supérieur pour créer plus de produits.',
                $usage['current'],
                $usage['unlimited'] ? '∞' : $usage['max']
            ),
            'current' => $usage['current'],
            'max' => $usage['max'],
        ];
    }

    /**
     * Vérifie la limite de magasins
     */
    protected function checkStoresLimit($organization): array
    {
        $usage = $organization->getStoresUsage();

        return [
            'can_add' => $organization->canAddStore(),
            'message' => sprintf(
                'Limite de magasins atteinte (%d/%s). Passez à un plan supérieur pour créer plus de magasins.',
                $usage['current'],
                $usage['unlimited'] ? '∞' : $usage['max']
            ),
            'current' => $usage['current'],
            'max' => $usage['max'],
        ];
    }

    /**
     * Vérifie la limite d'utilisateurs
     */
    protected function checkUsersLimit($organization): array
    {
        $usage = $organization->getUsersUsage();

        return [
            'can_add' => $organization->canAddUser(),
            'message' => sprintf(
                'Limite d\'utilisateurs atteinte (%d/%s). Passez à un plan supérieur pour inviter plus d\'utilisateurs.',
                $usage['current'],
                $usage['unlimited'] ? '∞' : $usage['max']
            ),
            'current' => $usage['current'],
            'max' => $usage['max'],
        ];
    }

    /**
     * Génère une réponse d'erreur appropriée selon le type de requête
     */
    protected function errorResponse(Request $request, string $message, int $status, array $data = []): Response
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                ...$data,
            ], $status);
        }

        return redirect()
            ->back()
            ->with('error', $message)
            ->with('upgrade_required', true);
    }
}
