<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Controller API - Authentification
 *
 * Gère l'authentification via Sanctum pour l'application mobile
 */
class AuthController extends Controller
{
    /**
     * Authentification utilisateur
     *
     * POST /api/auth/login
     *
     * @throws ValidationException
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'device_name' => 'nullable|string|max:255',
        ]);

        $user = User::where('email', $request->email)->first();

        // Vérifier si l'utilisateur existe et le mot de passe est correct
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Les identifiants fournis sont incorrects.',
            ], 401);
        }

        // Vérifier si l'utilisateur est actif
        if (! $user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Votre compte a été désactivé. Contactez l\'administrateur.',
            ], 403);
        }

        // Vérifier si l'email est vérifié (optionnel)
        // if (!$user->hasVerifiedEmail()) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Veuillez vérifier votre adresse email.',
        //     ], 403);
        // }

        // Nom du device pour le token
        $deviceName = $request->device_name ?? 'mobile-app';

        // Créer le token Sanctum
        $token = $user->createToken($deviceName, ['*'])->plainTextToken;

        // Mettre à jour la date de dernière connexion
        $user->update(['last_login_at' => now()]);

        // Charger les relations nécessaires
        $user->load(['defaultOrganization', 'currentStore', 'roles']);

        return response()->json([
            'success' => true,
            'message' => 'Connexion réussie',
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer',
                'user' => $this->formatUserData($user),
            ],
        ]);
    }

    /**
     * Déconnexion utilisateur
     *
     * POST /api/auth/logout
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            // Révoquer le token actuel
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Déconnexion réussie',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la déconnexion',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Déconnexion de tous les appareils
     *
     * POST /api/auth/logout-all
     */
    public function logoutAll(Request $request): JsonResponse
    {
        try {
            // Révoquer tous les tokens de l'utilisateur
            $request->user()->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Déconnexion de tous les appareils réussie',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la déconnexion',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Récupérer l'utilisateur connecté
     *
     * GET /api/auth/me
     */
    public function me(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $user->load(['defaultOrganization', 'currentStore', 'roles']);

            return response()->json([
                'success' => true,
                'data' => $this->formatUserData($user),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des informations',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Rafraîchir le token
     *
     * POST /api/auth/refresh
     */
    public function refresh(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $deviceName = $request->device_name ?? 'mobile-app';

            // Révoquer le token actuel
            $request->user()->currentAccessToken()->delete();

            // Créer un nouveau token
            $token = $user->createToken($deviceName, ['*'])->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Token rafraîchi avec succès',
                'data' => [
                    'token' => $token,
                    'token_type' => 'Bearer',
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du rafraîchissement du token',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Formater les données utilisateur pour la réponse
     */
    private function formatUserData(User $user): array
    {
        // Récupérer l'organisation : d'abord defaultOrganization, sinon la première organisation active
        $organization = $user->defaultOrganization;
        if (!$organization) {
            $organization = $user->organizations()
                ->wherePivot('is_active', true)
                ->first();

            // Mettre à jour default_organization_id pour les prochaines connexions
            if ($organization && !$user->default_organization_id) {
                $user->update(['default_organization_id' => $organization->id]);
            }
        }

        // Récupérer la devise de l'organisation (par défaut CDF)
        $currency = $organization?->currency ?? config('app.default_currency', 'CDF');

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'is_active' => $user->is_active,
            'email_verified' => $user->hasVerifiedEmail(),
            'last_login_at' => $user->last_login_at?->toIso8601String(),
            'currency' => $currency,
            'currency_symbol' => currency_symbol($currency),
            'organization' => $organization ? [
                'id' => $organization->id,
                'name' => $organization->name,
                'slug' => $organization->slug,
                'currency' => $currency,
            ] : null,
            'current_store' => $user->currentStore ? [
                'id' => $user->currentStore->id,
                'name' => $user->currentStore->name,
                'code' => $user->currentStore->code,
            ] : null,
            'available_stores' => $this->getAvailableStores($user),
            'roles' => $user->roles->map(fn($role) => [
                'id' => $role->id,
                'name' => $role->name,
                'slug' => $role->slug,
            ])->toArray(),
            'permissions' => [
                'can_access_all_stores' => $user->role === 'super-admin' || $user->role === 'admin' || $user->role === 'manager',
            ],
        ];
    }

    /**
     * Récupérer les magasins disponibles pour l'utilisateur
     */
    private function getAvailableStores(User $user): array
    {
        // Super-admin n'a pas de stores ni d'organisation
        if ($user->hasRole('super-admin')) {
            return [];
        }

        // Récupérer l'organisation : d'abord defaultOrganization, sinon la première organisation active
        $currentOrganization = $user->defaultOrganization;
        if (!$currentOrganization) {
            $currentOrganization = $user->organizations()
                ->wherePivot('is_active', true)
                ->first();
        }

        // Admin peut accéder à tous les magasins de l'organisation
        if ($user->isAdmin()) {
            $query = \App\Models\Store::query()
                ->where('is_active', true)
                ->orderBy('name');

            if ($currentOrganization) {
                $query->where('organization_id', $currentOrganization->id);
            }

            return $query->get(['id', 'name', 'code', 'address', 'phone'])->toArray();
        }

        // Utilisateurs réguliers ne peuvent accéder qu'à leurs magasins assignés
        $query = $user->stores()
            ->where('is_active', true)
            ->orderBy('name');

        if ($currentOrganization) {
            $query->where('organization_id', $currentOrganization->id);
        }

        return $query->get(['stores.id', 'stores.name', 'stores.code', 'stores.address', 'stores.phone'])->toArray();
    }
}
