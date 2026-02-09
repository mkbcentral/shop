<?php

namespace App\Http\Middleware;

use App\Models\SubscriptionPlan;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware de Rate Limiting API basé sur le plan d'abonnement
 * 
 * Limites par plan:
 * - professional: 1000 requêtes par heure
 * - enterprise: 10000 requêtes par heure (ou illimité selon config)
 * 
 * Usage: ->middleware('api.rate.limit')
 */
class ApiRateLimiter
{
    /**
     * Limites de requêtes par heure selon le plan
     * -1 signifie illimité
     */
    public const PLAN_RATE_LIMITS = [
        'free' => 100,         // Ne devrait pas avoir accès à l'API
        'starter' => 200,      // Ne devrait pas avoir accès à l'API
        'professional' => 1000,
        'enterprise' => 10000,
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        // Super-admin: pas de rate limiting
        if ($user->hasRole('super-admin')) {
            return $next($request);
        }

        // Récupérer l'organisation
        $organization = $this->getCurrentOrganization($user);

        if (!$organization) {
            return $next($request);
        }

        $planSlug = $organization->subscription_plan->value;
        $maxAttempts = $this->getRateLimitForPlan($planSlug);

        // Si illimité, continuer
        if ($maxAttempts === -1) {
            return $next($request);
        }

        // Clé unique par organisation (pas par utilisateur, car les limites sont par organisation)
        $key = 'api:rate:org:' . $organization->id;
        $decayMinutes = 60; // Fenêtre d'1 heure

        // Vérifier le rate limit
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $retryAfter = RateLimiter::availableIn($key);
            
            return response()->json([
                'success' => false,
                'message' => 'Trop de requêtes API. Veuillez réessayer plus tard.',
                'error' => 'rate_limit_exceeded',
                'retry_after' => $retryAfter,
                'limit' => $maxAttempts,
                'remaining' => 0,
                'current_plan' => $planSlug,
            ], 429)->withHeaders([
                'X-RateLimit-Limit' => $maxAttempts,
                'X-RateLimit-Remaining' => 0,
                'Retry-After' => $retryAfter,
                'X-RateLimit-Reset' => now()->addSeconds($retryAfter)->timestamp,
            ]);
        }

        // Incrémenter le compteur
        RateLimiter::hit($key, $decayMinutes * 60);

        // Calculer les requêtes restantes
        $remaining = max(0, $maxAttempts - RateLimiter::attempts($key));

        // Ajouter les headers de rate limit à la réponse
        $response = $next($request);

        $response->headers->set('X-RateLimit-Limit', (string) $maxAttempts);
        $response->headers->set('X-RateLimit-Remaining', (string) $remaining);
        $response->headers->set('X-RateLimit-Plan', $planSlug);

        return $response;
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
     * Récupère la limite de requêtes pour un plan donné
     * 
     * Essaie d'abord de récupérer depuis la base de données (champ api_rate_limit)
     * puis utilise les valeurs par défaut si non défini.
     */
    protected function getRateLimitForPlan(string $planSlug): int
    {
        // Essayer de récupérer depuis le cache/DB
        $cacheKey = "plan_rate_limit:{$planSlug}";
        
        return Cache::remember($cacheKey, 3600, function () use ($planSlug) {
            $plan = SubscriptionPlan::where('slug', $planSlug)->first();
            
            // Si le plan a une limite définie dans technical_features ou autre champ
            if ($plan && isset($plan->api_rate_limit)) {
                return $plan->api_rate_limit;
            }
            
            // Sinon utiliser les valeurs par défaut
            return self::PLAN_RATE_LIMITS[$planSlug] ?? self::PLAN_RATE_LIMITS['professional'];
        });
    }

    /**
     * Récupère les statistiques d'utilisation du rate limit pour une organisation
     */
    public static function getUsageStats(int $organizationId): array
    {
        $key = 'api:rate:org:' . $organizationId;
        $attempts = RateLimiter::attempts($key);
        
        return [
            'requests_made' => $attempts,
            'key' => $key,
        ];
    }

    /**
     * Réinitialise le compteur de rate limit pour une organisation
     */
    public static function resetForOrganization(int $organizationId): void
    {
        $key = 'api:rate:org:' . $organizationId;
        RateLimiter::clear($key);
    }
}
