<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to ensure user has verified their email before accessing protected routes
 */
class EnsureEmailVerifiedBeforeAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Si pas d'utilisateur authentifié, continuer (le middleware auth s'en chargera)
        if (!$user) {
            return $next($request);
        }

        // Exclure la page de paiement (vérification par URL - plus fiable que routeIs)
        if (preg_match('#^organization/\d+/payment$#', $request->path())) {
            return $next($request);
        }

        // Routes exclues de la vérification d'email
        $excludedRoutes = [
            'verification.*',     // Routes de vérification d'email
            'logout',             // Permettre la déconnexion
            'password.*',         // Routes de réinitialisation de mot de passe
            'organization.payment', // Permettre l'accès à la page de paiement
        ];

        // Vérifier si la route actuelle est exclue
        foreach ($excludedRoutes as $pattern) {
            if ($request->routeIs($pattern)) {
                return $next($request);
            }
        }

        // Si l'utilisateur n'a pas vérifié son email, le rediriger vers la page de vérification
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('warning', 'Veuillez vérifier votre adresse email avant de continuer.');
        }

        return $next($request);
    }
}
