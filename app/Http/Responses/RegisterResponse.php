<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RegisterResponse implements RegisterResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     */
    public function toResponse($request): Response
    {
        $user = Auth::user();

        // Après l'inscription, TOUJOURS rediriger vers la page de vérification d'email
        // (sauf si l'email est déjà vérifié - cas rare)
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('success', 'Votre compte a été créé avec succès ! Veuillez vérifier votre adresse email pour continuer.');
        }

        // Si l'email est déjà vérifié (cas rare), appliquer la même logique que le login
        $organization = $user->defaultOrganization;

        if (!$organization) {
            return redirect()->route('register')
                ->with('error', 'Une erreur est survenue. Veuillez réessayer.');
        }

        // Vérifier si paiement nécessaire
        if (!$organization->isAccessible()) {
            return redirect()->route('organization.payment', ['organization' => $organization->id])
                ->with('info', 'Veuillez compléter le paiement pour accéder à votre organisation.');
        }

        // Tout est OK
        session([
            'current_organization_id' => $organization->id,
            'current_store_id' => $user->current_store_id,
        ]);

        return $request->wantsJson()
            ? new JsonResponse('', 201)
            : redirect()->intended(config('fortify.home'));
    }
}
