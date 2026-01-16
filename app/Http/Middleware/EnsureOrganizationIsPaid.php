<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganizationIsPaid
{
    /**
     * Handle an incoming request.
     * 
     * NOTE: Ce middleware ne redirige plus vers une page de paiement séparée.
     * Le paiement est maintenant géré via un modal dans le dashboard (PaymentModal).
     * Ce middleware laisse passer toutes les requêtes.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Le paiement est maintenant géré via un modal overlay dans le dashboard
        // Pas de redirection - on laisse passer toutes les requêtes
        return $next($request);
    }
}
