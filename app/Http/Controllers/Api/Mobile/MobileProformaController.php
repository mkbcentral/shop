<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\ProformaInvoice;
use App\Models\ProformaInvoiceItem;
use App\Repositories\ProductVariantRepository;
use App\Services\ProformaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\ProformaInvoiceMail;

/**
 * Controller API Mobile - Gestion des Proformas (Devis)
 *
 * Permet de gérer les devis/proformas via l'API mobile
 */
class MobileProformaController extends Controller
{
    public function __construct(
        private ProformaService $proformaService,
        private ProductVariantRepository $variantRepository,
    ) {}

    /**
     * Liste des proformas (paginée avec filtres)
     *
     * GET /api/mobile/proformas
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = (int) $request->input('per_page', 20);
            $perPage = min(max($perPage, 10), 100);

            $search = $request->input('search');
            $status = $request->input('status');
            $period = $request->input('period');
            $dateFrom = $request->input('date_from');
            $dateTo = $request->input('date_to');
            $sortField = $request->input('sort_by', 'proforma_date');
            $sortDirection = $request->input('sort_dir', 'desc');

            // Apply period filter
            if ($period && !$dateFrom && !$dateTo) {
                [$dateFrom, $dateTo] = $this->applyPeriodFilter($period);
            }

            // Query
            $query = ProformaInvoice::query()
                ->with(['user', 'store', 'items'])
                ->orderBy($sortField, $sortDirection);

            // Search filter
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('proforma_number', 'like', "%{$search}%")
                      ->orWhere('client_name', 'like', "%{$search}%")
                      ->orWhere('client_phone', 'like', "%{$search}%")
                      ->orWhere('client_email', 'like', "%{$search}%");
                });
            }

            // Status filter
            if ($status) {
                $query->where('status', $status);
            }

            // Date range filter
            if ($dateFrom) {
                $query->whereDate('proforma_date', '>=', $dateFrom);
            }
            if ($dateTo) {
                $query->whereDate('proforma_date', '<=', $dateTo);
            }

            $proformas = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => [
                    'proformas' => $proformas->map(fn($p) => $this->formatProforma($p)),
                    'pagination' => [
                        'current_page' => $proformas->currentPage(),
                        'last_page' => $proformas->lastPage(),
                        'per_page' => $proformas->perPage(),
                        'total' => $proformas->total(),
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des proformas',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Détail d'un proforma
     *
     * GET /api/mobile/proformas/{id}
     */
    public function show(int $id): JsonResponse
    {
        try {
            $proforma = ProformaInvoice::with(['items.productVariant.product', 'user', 'store'])
                ->find($id);

            if (!$proforma) {
                return response()->json([
                    'success' => false,
                    'message' => 'Proforma non trouvé',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $this->formatProformaDetailed($proforma),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du proforma',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Créer un nouveau proforma
     *
     * POST /api/mobile/proformas
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'client_name' => 'required|string|max:255',
            'client_phone' => 'nullable|string|max:50',
            'client_email' => 'nullable|email|max:255',
            'client_address' => 'nullable|string|max:500',
            'proforma_date' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:proforma_date',
            'notes' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_variant_id' => 'nullable|integer',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            $data = $validator->validated();
            $user = Auth::user();
            $data['user_id'] = $user->id;
            $data['organization_id'] = $user->default_organization_id;
            
            // Récupérer le store_id pour l'API mobile
            $storeId = $user->current_store_id ?? $user->default_store_id;
            if (!$storeId) {
                // Prendre le premier store de l'utilisateur
                $firstStore = $user->stores()->first();
                $storeId = $firstStore?->id;
            }
            
            if (!$storeId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun magasin associé à votre compte',
                ], 400);
            }
            
            $data['store_id'] = $storeId;
            $data['status'] = ProformaInvoice::STATUS_DRAFT;

            // Extraire les items avant la création
            $items = $data['items'];
            unset($data['items']);

            // Calculer les totaux
            $subtotal = 0;
            foreach ($items as $item) {
                $itemTotal = ($item['quantity'] * $item['unit_price']) - ($item['discount'] ?? 0);
                $subtotal += $itemTotal;
            }

            $data['subtotal'] = $subtotal;
            $data['tax_amount'] = 0;
            $data['discount'] = 0;
            $data['total'] = $subtotal;

            // Créer le proforma
            $proforma = ProformaInvoice::create($data);

            // Créer les items
            foreach ($items as $itemData) {
                // Vérifier si la variante existe, sinon mettre null
                if (isset($itemData['product_variant_id'])) {
                    $variantExists = \App\Models\ProductVariant::where('id', $itemData['product_variant_id'])->exists();
                    if (!$variantExists) {
                        $itemData['product_variant_id'] = null;
                    }
                }
                
                $itemData['total'] = ($itemData['quantity'] * $itemData['unit_price']) - ($itemData['discount'] ?? 0);
                $proforma->items()->create($itemData);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Proforma créé avec succès',
                'data' => $this->formatProformaDetailed($proforma->fresh(['items.productVariant.product', 'user', 'store'])),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Modifier un proforma
     *
     * PUT /api/mobile/proformas/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $proforma = ProformaInvoice::find($id);

        if (!$proforma) {
            return response()->json([
                'success' => false,
                'message' => 'Proforma non trouvé',
            ], 404);
        }

        // Vérifier si le proforma peut être modifié
        if (in_array($proforma->status, [ProformaInvoice::STATUS_CONVERTED, ProformaInvoice::STATUS_EXPIRED])) {
            return response()->json([
                'success' => false,
                'message' => 'Ce proforma ne peut plus être modifié',
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'client_name' => 'sometimes|required|string|max:255',
            'client_phone' => 'nullable|string|max:50',
            'client_email' => 'nullable|email|max:255',
            'client_address' => 'nullable|string|max:500',
            'proforma_date' => 'sometimes|required|date',
            'valid_until' => 'sometimes|required|date|after_or_equal:proforma_date',
            'notes' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'items' => 'sometimes|required|array|min:1',
            'items.*.product_variant_id' => 'nullable|integer',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            $data = $validator->validated();

            // Si les items sont fournis, recalculer les totaux
            if (isset($data['items'])) {
                // Supprimer les anciens items
                $proforma->items()->delete();

                $subtotal = 0;
                foreach ($data['items'] as $itemData) {
                    // Vérifier si la variante existe, sinon mettre null
                    if (isset($itemData['product_variant_id'])) {
                        $variantExists = \App\Models\ProductVariant::where('id', $itemData['product_variant_id'])->exists();
                        if (!$variantExists) {
                            $itemData['product_variant_id'] = null;
                        }
                    }
                    
                    $itemTotal = ($itemData['quantity'] * $itemData['unit_price']) - ($itemData['discount'] ?? 0);
                    $subtotal += $itemTotal;
                    
                    $itemData['total'] = $itemTotal;
                    $proforma->items()->create($itemData);
                }

                $data['subtotal'] = $subtotal;
                $data['total'] = $subtotal;
                
                unset($data['items']);
            }

            $proforma->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Proforma modifié avec succès',
                'data' => $this->formatProformaDetailed($proforma->fresh(['items.productVariant.product', 'user', 'store'])),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Supprimer un proforma
     *
     * DELETE /api/mobile/proformas/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $proforma = ProformaInvoice::find($id);

            if (!$proforma) {
                return response()->json([
                    'success' => false,
                    'message' => 'Proforma non trouvé',
                ], 404);
            }

            // Vérifier si le proforma peut être supprimé
            if ($proforma->status === ProformaInvoice::STATUS_CONVERTED) {
                return response()->json([
                    'success' => false,
                    'message' => 'Un proforma converti ne peut pas être supprimé',
                ], 400);
            }

            $proformaNumber = $proforma->proforma_number;
            $proforma->delete();

            return response()->json([
                'success' => true,
                'message' => "Proforma {$proformaNumber} supprimé avec succès",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Changer le statut d'un proforma
     *
     * POST /api/mobile/proformas/{id}/change-status
     */
    public function changeStatus(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:draft,sent,accepted,rejected,expired',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $proforma = ProformaInvoice::find($id);

            if (!$proforma) {
                return response()->json([
                    'success' => false,
                    'message' => 'Proforma non trouvé',
                ], 404);
            }

            // Vérifier si le changement de statut est autorisé
            if ($proforma->status === ProformaInvoice::STATUS_CONVERTED) {
                return response()->json([
                    'success' => false,
                    'message' => 'Un proforma converti ne peut pas changer de statut',
                ], 400);
            }

            $proforma->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'Statut modifié avec succès',
                'data' => $this->formatProforma($proforma),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Convertir un proforma en vente/facture
     *
     * POST /api/mobile/proformas/{id}/convert-to-sale
     */
    public function convertToSale(int $id): JsonResponse
    {
        try {
            $proforma = ProformaInvoice::with('items')->find($id);

            if (!$proforma) {
                return response()->json([
                    'success' => false,
                    'message' => 'Proforma non trouvé',
                ], 404);
            }

            // Vérifier le statut
            if ($proforma->status === ProformaInvoice::STATUS_CONVERTED) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce proforma a déjà été converti',
                ], 400);
            }

            if ($proforma->status !== ProformaInvoice::STATUS_ACCEPTED) {
                return response()->json([
                    'success' => false,
                    'message' => 'Seuls les proformas acceptés peuvent être convertis',
                ], 400);
            }

            $invoice = $this->proformaService->convertToInvoice($proforma);

            return response()->json([
                'success' => true,
                'message' => 'Proforma converti en facture avec succès',
                'data' => [
                    'invoice_id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'sale_id' => $invoice->sale_id,
                    'proforma' => $this->formatProforma($proforma->fresh()),
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
     * Dupliquer un proforma
     *
     * POST /api/mobile/proformas/{id}/duplicate
     */
    public function duplicate(int $id): JsonResponse
    {
        try {
            $originalProforma = ProformaInvoice::with('items')->find($id);

            if (!$originalProforma) {
                return response()->json([
                    'success' => false,
                    'message' => 'Proforma non trouvé',
                ], 404);
            }

            DB::beginTransaction();

            // Créer une copie
            $newProforma = $originalProforma->replicate();
            $newProforma->proforma_number = null; // Sera généré automatiquement
            $newProforma->status = ProformaInvoice::STATUS_DRAFT;
            $newProforma->proforma_date = now();
            $newProforma->valid_until = now()->addDays(30);
            $newProforma->converted_to_invoice_id = null;
            $newProforma->converted_at = null;
            $newProforma->save();

            // Copier les items
            foreach ($originalProforma->items as $item) {
                $newItem = $item->replicate();
                $newItem->proforma_invoice_id = $newProforma->id;
                $newItem->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Proforma dupliqué avec succès',
                'data' => $this->formatProformaDetailed($newProforma->fresh(['items.productVariant.product', 'user', 'store'])),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Envoyer le proforma par email
     *
     * POST /api/mobile/proformas/{id}/send-email
     */
    public function sendEmail(int $id, Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ], [
                'email.required' => 'L\'adresse email est requise',
                'email.email' => 'L\'adresse email n\'est pas valide',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation échouée',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $proforma = ProformaInvoice::with(['items.productVariant.product', 'store', 'user'])->find($id);

            if (!$proforma) {
                return response()->json([
                    'success' => false,
                    'message' => 'Proforma non trouvé',
                ], 404);
            }

            $email = $request->input('email');

            // Envoyer l'email avec le PDF en pièce jointe
            Mail::to($email)->send(new ProformaInvoiceMail($proforma));

            return response()->json([
                'success' => true,
                'message' => 'Proforma envoyé avec succès à ' . $email,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Recherche de produits pour le proforma
     *
     * GET /api/mobile/proformas/search-products
     */
    public function searchProducts(Request $request): JsonResponse
    {
        try {
            $search = $request->input('q', '');
            $limit = (int) $request->input('limit', 10);
            $limit = min(max($limit, 5), 50);

            if (strlen($search) < 2) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                ]);
            }

            $variants = $this->variantRepository->query()
                ->with(['product.category'])
                ->whereHas('product', function($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('reference', 'like', "%{$search}%");
                })
                ->orWhere('sku', 'like', "%{$search}%")
                ->limit($limit)
                ->get()
                ->map(function($variant) {
                    return [
                        'id' => $variant->id,
                        'name' => $variant->full_name,
                        'price' => $variant->final_price,
                        'stock' => $variant->stock_quantity,
                        'sku' => $variant->sku,
                        'product' => [
                            'id' => $variant->product->id,
                            'name' => $variant->product->name,
                            'category' => $variant->product->category->name ?? null,
                        ],
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $variants,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la recherche',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Statistiques des proformas
     *
     * GET /api/mobile/proformas/statistics
     */
    public function statistics(Request $request): JsonResponse
    {
        try {
            $period = $request->input('period', 'this_month');
            $dateFrom = $request->input('date_from');
            $dateTo = $request->input('date_to');

            // Apply period filter
            if ($period && !$dateFrom && !$dateTo) {
                [$dateFrom, $dateTo] = $this->applyPeriodFilter($period);
            }

            $query = ProformaInvoice::query();

            if ($dateFrom) {
                $query->whereDate('proforma_date', '>=', $dateFrom);
            }
            if ($dateTo) {
                $query->whereDate('proforma_date', '<=', $dateTo);
            }

            $stats = [
                'total_count' => (clone $query)->count(),
                'total_amount' => (clone $query)->sum('total'),
                'by_status' => [
                    'draft' => [
                        'count' => (clone $query)->where('status', ProformaInvoice::STATUS_DRAFT)->count(),
                        'amount' => (clone $query)->where('status', ProformaInvoice::STATUS_DRAFT)->sum('total'),
                    ],
                    'sent' => [
                        'count' => (clone $query)->where('status', ProformaInvoice::STATUS_SENT)->count(),
                        'amount' => (clone $query)->where('status', ProformaInvoice::STATUS_SENT)->sum('total'),
                    ],
                    'accepted' => [
                        'count' => (clone $query)->where('status', ProformaInvoice::STATUS_ACCEPTED)->count(),
                        'amount' => (clone $query)->where('status', ProformaInvoice::STATUS_ACCEPTED)->sum('total'),
                    ],
                    'rejected' => [
                        'count' => (clone $query)->where('status', ProformaInvoice::STATUS_REJECTED)->count(),
                        'amount' => (clone $query)->where('status', ProformaInvoice::STATUS_REJECTED)->sum('total'),
                    ],
                    'converted' => [
                        'count' => (clone $query)->where('status', ProformaInvoice::STATUS_CONVERTED)->count(),
                        'amount' => (clone $query)->where('status', ProformaInvoice::STATUS_CONVERTED)->sum('total'),
                    ],
                    'expired' => [
                        'count' => (clone $query)->where('status', ProformaInvoice::STATUS_EXPIRED)->count(),
                        'amount' => (clone $query)->where('status', ProformaInvoice::STATUS_EXPIRED)->sum('total'),
                    ],
                ],
                'conversion_rate' => 0,
            ];

            // Calculer le taux de conversion
            $acceptedCount = $stats['by_status']['accepted']['count'] + $stats['by_status']['converted']['count'];
            if ($stats['total_count'] > 0) {
                $stats['conversion_rate'] = round(($acceptedCount / $stats['total_count']) * 100, 2);
            }

            return response()->json([
                'success' => true,
                'data' => $stats,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du calcul des statistiques',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    // ==================== MÉTHODES PRIVÉES ====================

    /**
     * Appliquer un filtre de période
     */
    private function applyPeriodFilter(string $period): array
    {
        $now = now();

        return match ($period) {
            'today' => [$now->format('Y-m-d'), $now->format('Y-m-d')],
            'yesterday' => [
                $now->copy()->subDay()->format('Y-m-d'),
                $now->copy()->subDay()->format('Y-m-d')
            ],
            'this_week' => [
                $now->copy()->startOfWeek()->format('Y-m-d'),
                $now->format('Y-m-d')
            ],
            'last_week' => [
                $now->copy()->subWeek()->startOfWeek()->format('Y-m-d'),
                $now->copy()->subWeek()->endOfWeek()->format('Y-m-d')
            ],
            'this_month' => [
                $now->copy()->startOfMonth()->format('Y-m-d'),
                $now->format('Y-m-d')
            ],
            'last_month' => [
                $now->copy()->subMonth()->startOfMonth()->format('Y-m-d'),
                $now->copy()->subMonth()->endOfMonth()->format('Y-m-d')
            ],
            'last_3_months' => [
                $now->copy()->subMonths(3)->startOfMonth()->format('Y-m-d'),
                $now->format('Y-m-d')
            ],
            'this_year' => [
                $now->copy()->startOfYear()->format('Y-m-d'),
                $now->format('Y-m-d')
            ],
            default => [null, null],
        };
    }

    /**
     * Formater un proforma (liste)
     */
    private function formatProforma(ProformaInvoice $proforma): array
    {
        return [
            'id' => $proforma->id,
            'proforma_number' => $proforma->proforma_number,
            'client_name' => $proforma->client_name,
            'client_phone' => $proforma->client_phone,
            'client_email' => $proforma->client_email,
            'proforma_date' => $proforma->proforma_date?->format('Y-m-d'),
            'valid_until' => $proforma->valid_until?->format('Y-m-d'),
            'subtotal' => $proforma->subtotal,
            'tax_amount' => $proforma->tax_amount,
            'discount' => $proforma->discount,
            'total' => $proforma->total,
            'status' => $proforma->status,
            'status_label' => $this->getStatusLabel($proforma->status),
            'is_expired' => $proforma->valid_until ? $proforma->valid_until->isPast() : false,
            'user' => [
                'id' => $proforma->user->id,
                'name' => $proforma->user->name,
            ],
            'store' => $proforma->store ? [
                'id' => $proforma->store->id,
                'name' => $proforma->store->name,
            ] : null,
            'items_count' => $proforma->items->count(),
            'created_at' => $proforma->created_at?->toIso8601String(),
        ];
    }

    /**
     * Formater un proforma avec détails
     */
    private function formatProformaDetailed(ProformaInvoice $proforma): array
    {
        $data = $this->formatProforma($proforma);

        $data['client_address'] = $proforma->client_address;
        $data['notes'] = $proforma->notes;
        $data['terms_conditions'] = $proforma->terms_conditions;
        $data['converted_to_invoice_id'] = $proforma->converted_to_invoice_id;
        $data['converted_at'] = $proforma->converted_at?->toIso8601String();

        $data['items'] = $proforma->items->map(function ($item) {
            return [
                'id' => $item->id,
                'product_variant_id' => $item->product_variant_id,
                'description' => $item->description,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'discount' => $item->discount,
                'total' => $item->total,
                'product_variant' => $item->productVariant ? [
                    'id' => $item->productVariant->id,
                    'sku' => $item->productVariant->sku,
                    'name' => $item->productVariant->full_name,
                    'stock' => $item->productVariant->stock_quantity,
                    'product' => [
                        'id' => $item->productVariant->product->id,
                        'name' => $item->productVariant->product->name,
                    ],
                ] : null,
            ];
        })->toArray();

        $data['updated_at'] = $proforma->updated_at?->toIso8601String();

        return $data;
    }

    /**
     * Obtenir le libellé du statut
     */
    private function getStatusLabel(string $status): string
    {
        return match ($status) {
            ProformaInvoice::STATUS_DRAFT => 'Brouillon',
            ProformaInvoice::STATUS_SENT => 'Envoyé',
            ProformaInvoice::STATUS_ACCEPTED => 'Accepté',
            ProformaInvoice::STATUS_REJECTED => 'Rejeté',
            ProformaInvoice::STATUS_CONVERTED => 'Converti',
            ProformaInvoice::STATUS_EXPIRED => 'Expiré',
            default => $status,
        };
    }
}
