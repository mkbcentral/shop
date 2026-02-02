<?php

namespace App\Http\Middleware;

use App\Enums\PaymentStatus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganizationIsPaid
{
    /**
     * Handle an incoming request.
     * 
     * Ce middleware ne redirige plus vers une page de paiement séparée.
     * Le paiement est géré via un modal (PaymentModal) qui bloque l'interface du dashboard.
     * Le modal s'affiche automatiquement quand l'organisation n'a pas complété son paiement.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Le paiement est maintenant géré via un modal overlay dans le dashboard
        // Le PaymentModal component vérifie automatiquement si le paiement est nécessaire
        // et bloque l'interface avec un modal non-fermable
        return $next($request);
    }
}
