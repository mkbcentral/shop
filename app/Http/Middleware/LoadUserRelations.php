<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoadUserRelations
{
    /**
     * Handle an incoming request.
     * Précharge les relations de l'utilisateur pour éviter les problèmes de cache
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($user = $request->user()) {
            // Forcer le chargement des rôles pour éviter les problèmes de cache
            $user->load('roles', 'stores');
        }

        return $next($request);
    }
}
