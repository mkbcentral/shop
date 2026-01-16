<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasStoreAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Si pas d'utilisateur authentifié, laisser passer (AuthMiddleware gérera)
        if (!$user) {
            return $next($request);
        }

        // Exclure la page de paiement (vérification par URL et par nom de route)
        if (preg_match('#^organization/\d+/payment$#', $request->path()) || $request->routeIs('organization.payment')) {
            return $next($request);
        }

        // Les admins, super-admins et managers peuvent voir tous les stores
        // On ne leur force pas un current_store_id s'ils n'en ont pas
        $isPrivilegedUser = $user->hasAnyRole(['admin', 'super-admin', 'manager']);

        // Si l'utilisateur n'a pas de magasin actuel
        if (!$user->current_store_id && !$isPrivilegedUser) {
            // Pour les utilisateurs non-privilégiés, on doit leur assigner un store
            // D'abord, vérifier si l'utilisateur a des magasins assignés
            $firstAssignedStore = $user->stores()->first();

            if ($firstAssignedStore) {
                // Utiliser le premier magasin assigné
                $user->update(['current_store_id' => $firstAssignedStore->id]);
            } else {
                // L'utilisateur n'a aucun magasin assigné
                // Assigner le magasin principal par défaut
                $mainStore = \App\Models\Store::where('is_main', true)->first();

                if ($mainStore) {
                    $user->update(['current_store_id' => $mainStore->id]);

                    // Assigner l'utilisateur au magasin principal s'il n'y est pas déjà
                    $user->stores()->attach($mainStore->id, [
                        'role' => 'staff',
                        'is_default' => true,
                    ]);
                } else {
                    // Pas de magasin principal, essayer le premier magasin disponible
                    $anyStore = \App\Models\Store::where('is_active', true)->first();

                    if ($anyStore) {
                        $user->update(['current_store_id' => $anyStore->id]);
                        $user->stores()->attach($anyStore->id, [
                            'role' => 'staff',
                            'is_default' => true,
                        ]);
                    }
                }
            }
        }

        // Vérifier que l'utilisateur a accès au magasin actuel (seulement pour les non-privilégiés)
        if ($user->current_store_id && !$isPrivilegedUser) {
            $hasAccess = $user->stores()->where('stores.id', $user->current_store_id)->exists();

            if (!$hasAccess) {
                // Réassigner à un magasin auquel il a accès
                $firstStore = $user->stores()->first();

                if ($firstStore) {
                    $user->update(['current_store_id' => $firstStore->id]);
                } else {
                    // Aucun magasin assigné, rediriger vers une page d'erreur
                    return redirect()->route('dashboard')
                        ->with('error', 'Vous n\'avez accès à aucun magasin. Contactez l\'administrateur.');
                }
            }
        }

        return $next($request);
    }
}
