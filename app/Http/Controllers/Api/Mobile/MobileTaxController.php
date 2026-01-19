<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\OrganizationTax;
use App\Services\TaxService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * Controller API Mobile - Gestion des Taxes
 *
 * Permet de gérer les taxes de l'organisation pour le POS
 */
class MobileTaxController extends Controller
{
    public function __construct(
        private TaxService $taxService,
    ) {}

    /**
     * Liste des taxes de l'organisation
     *
     * GET /api/mobile/taxes
     */
    public function index(): JsonResponse
    {
        try {
            $user = Auth::user();
            $organization = $user->currentOrganization ?? $user->organizations()->first();

            if (!$organization) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune organisation trouvée',
                ], 404);
            }

            $taxes = $this->taxService->getActiveTaxes($organization);

            return response()->json([
                'success' => true,
                'data' => $this->taxService->formatTaxesForApi($taxes),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des taxes',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Récupérer la taxe par défaut
     *
     * GET /api/mobile/taxes/default
     */
    public function default(): JsonResponse
    {
        try {
            $user = Auth::user();
            $organization = $user->currentOrganization ?? $user->organizations()->first();

            if (!$organization) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune organisation trouvée',
                ], 404);
            }

            $tax = $this->taxService->getDefaultTax($organization);

            if (!$tax) {
                return response()->json([
                    'success' => true,
                    'data' => null,
                    'message' => 'Aucune taxe par défaut configurée',
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $tax->id,
                    'name' => $tax->name,
                    'code' => $tax->code,
                    'rate' => (float) $tax->rate,
                    'formatted_rate' => $tax->formatted_rate,
                    'type' => $tax->type,
                    'is_included_in_price' => $tax->is_included_in_price,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de la taxe par défaut',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Détail d'une taxe
     *
     * GET /api/mobile/taxes/{id}
     */
    public function show(int $id): JsonResponse
    {
        try {
            $user = Auth::user();
            $organization = $user->currentOrganization ?? $user->organizations()->first();

            if (!$organization) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune organisation trouvée',
                ], 404);
            }

            $tax = $organization->taxes()->find($id);

            if (!$tax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Taxe non trouvée',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $tax->id,
                    'name' => $tax->name,
                    'code' => $tax->code,
                    'description' => $tax->description,
                    'rate' => (float) $tax->rate,
                    'formatted_rate' => $tax->formatted_rate,
                    'type' => $tax->type,
                    'fixed_amount' => $tax->fixed_amount,
                    'is_compound' => $tax->is_compound,
                    'is_included_in_price' => $tax->is_included_in_price,
                    'priority' => $tax->priority,
                    'apply_to_all_products' => $tax->apply_to_all_products,
                    'product_categories' => $tax->product_categories,
                    'is_default' => $tax->is_default,
                    'is_active' => $tax->is_active,
                    'valid_from' => $tax->valid_from?->toDateString(),
                    'valid_until' => $tax->valid_until?->toDateString(),
                    'tax_number' => $tax->tax_number,
                    'authority' => $tax->authority,
                    'created_at' => $tax->created_at?->toIso8601String(),
                    'updated_at' => $tax->updated_at?->toIso8601String(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de la taxe',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Créer une nouvelle taxe
     *
     * POST /api/mobile/taxes
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'description' => 'nullable|string',
            'rate' => 'required|numeric|min:0|max:100',
            'type' => 'required|in:percentage,fixed',
            'fixed_amount' => 'nullable|numeric|min:0',
            'is_compound' => 'nullable|boolean',
            'is_included_in_price' => 'nullable|boolean',
            'priority' => 'nullable|integer|min:0',
            'apply_to_all_products' => 'nullable|boolean',
            'product_categories' => 'nullable|array',
            'product_categories.*' => 'integer|exists:categories,id',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'tax_number' => 'nullable|string|max:100',
            'authority' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Le nom de la taxe est obligatoire',
            'code.required' => 'Le code de la taxe est obligatoire',
            'rate.required' => 'Le taux de la taxe est obligatoire',
            'rate.max' => 'Le taux ne peut pas dépasser 100%',
            'type.required' => 'Le type de calcul est obligatoire',
            'type.in' => 'Le type doit être "percentage" ou "fixed"',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = Auth::user();
            $organization = $user->currentOrganization ?? $user->organizations()->first();

            if (!$organization) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune organisation trouvée',
                ], 404);
            }

            // Vérifier l'unicité du code dans l'organisation
            $existingTax = $organization->taxes()->where('code', $request->code)->first();
            if ($existingTax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une taxe avec ce code existe déjà',
                ], 422);
            }

            $data = $validator->validated();
            $tax = $this->taxService->createTax($organization, $data);

            return response()->json([
                'success' => true,
                'message' => 'Taxe créée avec succès',
                'data' => [
                    'id' => $tax->id,
                    'name' => $tax->name,
                    'code' => $tax->code,
                    'rate' => (float) $tax->rate,
                    'formatted_rate' => $tax->formatted_rate,
                    'is_default' => $tax->is_default,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Modifier une taxe
     *
     * PUT /api/mobile/taxes/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'code' => 'sometimes|required|string|max:50',
            'description' => 'nullable|string',
            'rate' => 'sometimes|required|numeric|min:0|max:100',
            'type' => 'sometimes|required|in:percentage,fixed',
            'fixed_amount' => 'nullable|numeric|min:0',
            'is_compound' => 'nullable|boolean',
            'is_included_in_price' => 'nullable|boolean',
            'priority' => 'nullable|integer|min:0',
            'apply_to_all_products' => 'nullable|boolean',
            'product_categories' => 'nullable|array',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'tax_number' => 'nullable|string|max:100',
            'authority' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = Auth::user();
            $organization = $user->currentOrganization ?? $user->organizations()->first();

            if (!$organization) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune organisation trouvée',
                ], 404);
            }

            $tax = $organization->taxes()->find($id);

            if (!$tax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Taxe non trouvée',
                ], 404);
            }

            // Vérifier l'unicité du code si modifié
            if ($request->has('code') && $request->code !== $tax->code) {
                $existingTax = $organization->taxes()
                    ->where('code', $request->code)
                    ->where('id', '!=', $id)
                    ->first();

                if ($existingTax) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Une taxe avec ce code existe déjà',
                    ], 422);
                }
            }

            $data = $validator->validated();
            $tax = $this->taxService->updateTax($tax, $data);

            return response()->json([
                'success' => true,
                'message' => 'Taxe modifiée avec succès',
                'data' => [
                    'id' => $tax->id,
                    'name' => $tax->name,
                    'code' => $tax->code,
                    'rate' => (float) $tax->rate,
                    'formatted_rate' => $tax->formatted_rate,
                    'is_default' => $tax->is_default,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Supprimer une taxe
     *
     * DELETE /api/mobile/taxes/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $user = Auth::user();
            $organization = $user->currentOrganization ?? $user->organizations()->first();

            if (!$organization) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune organisation trouvée',
                ], 404);
            }

            $tax = $organization->taxes()->find($id);

            if (!$tax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Taxe non trouvée',
                ], 404);
            }

            $taxName = $tax->name;
            $this->taxService->deleteTax($tax);

            return response()->json([
                'success' => true,
                'message' => "Taxe \"{$taxName}\" supprimée avec succès",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Définir une taxe comme taxe par défaut
     *
     * POST /api/mobile/taxes/{id}/set-default
     */
    public function setDefault(int $id): JsonResponse
    {
        try {
            $user = Auth::user();
            $organization = $user->currentOrganization ?? $user->organizations()->first();

            if (!$organization) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune organisation trouvée',
                ], 404);
            }

            $tax = $organization->taxes()->find($id);

            if (!$tax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Taxe non trouvée',
                ], 404);
            }

            $tax->setAsDefault();

            return response()->json([
                'success' => true,
                'message' => "Taxe \"{$tax->name}\" définie comme taxe par défaut",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Calculer les taxes pour un montant
     *
     * POST /api/mobile/taxes/calculate
     *
     * Body: { "amount": 10000, "product_id": 1, "tax_ids": [1, 2] }
     */
    public function calculate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0',
            'product_id' => 'nullable|integer|exists:products,id',
            'category_id' => 'nullable|integer|exists:categories,id',
            'tax_ids' => 'nullable|array',
            'tax_ids.*' => 'integer|exists:organization_taxes,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = Auth::user();
            $organization = $user->currentOrganization ?? $user->organizations()->first();

            if (!$organization) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune organisation trouvée',
                ], 404);
            }

            $result = $this->taxService->calculateTaxes(
                $organization,
                (float) $request->amount,
                $request->product_id,
                $request->category_id,
                $request->tax_ids
            );

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du calcul des taxes',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Calculer les taxes pour plusieurs lignes de vente
     *
     * POST /api/mobile/taxes/calculate-lines
     *
     * Body: { "lines": [{ "amount": 10000, "product_id": 1 }, { "amount": 5000 }] }
     */
    public function calculateLines(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'lines' => 'required|array|min:1',
            'lines.*.amount' => 'required|numeric|min:0',
            'lines.*.product_id' => 'nullable|integer|exists:products,id',
            'lines.*.category_id' => 'nullable|integer|exists:categories,id',
            'lines.*.tax_ids' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = Auth::user();
            $organization = $user->currentOrganization ?? $user->organizations()->first();

            if (!$organization) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune organisation trouvée',
                ], 404);
            }

            $result = $this->taxService->calculateTaxesForLines(
                $organization,
                $request->lines
            );

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du calcul des taxes',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
