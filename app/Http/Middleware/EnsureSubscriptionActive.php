<?php

namespace App\Http\Middleware;

use App\Enums\PaymentStatus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscriptionActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Si pas d'utilisateur authentifié, continuer
        if (!$user) {
            return $next($request);
        }

        // Exclure les routes publiques et d'authentification
        $excludedRoutes = [
            'organization.payment',
            'logout',
            'login',
            'register',
            'password.*',
            'verification.*',
        ];

        foreach ($excludedRoutes as $pattern) {
            if ($request->routeIs($pattern)) {
                return $next($request);
            }
        }

        // Récupérer l'organisation par défaut
        $organization = $user->defaultOrganization;

        // Si pas d'organisation, continuer
        if (!$organization) {
            return $next($request);
        }

        // Vérifier si l'organisation existe réellement (pas juste l'ID)
        if (!$organization->exists) {
            // Nettoyer l'ID d'organisation invalide et trouver une organisation valide
            $validOrg = $user->organizations()->first();
            if ($validOrg) {
                $user->update(['default_organization_id' => $validOrg->id]);
                $organization = $validOrg;
            } else {
                $user->update(['default_organization_id' => null]);
                return $next($request);
            }
        }

        // Vérifier si l'organisation nécessite un paiement
        // Note: La vérification d'email est déjà faite par le middleware EnsureEmailVerifiedBeforeAccess
        if (!$organization->isAccessible()) {
            // L'organisation a un plan payant mais n'a pas complété le paiement
            if ($organization->owner_id === $user->id) {
                return redirect()->route('organization.payment', ['organization' => $organization->id])
                    ->with('warning', 'Veuillez compléter le paiement pour accéder à votre compte.');
            }
        }

        return $next($request);
    }
}
