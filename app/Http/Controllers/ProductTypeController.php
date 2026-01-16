<?php

namespace App\Http\Controllers;

use App\Services\ProductTypeService;
use Illuminate\Http\Request;

class ProductTypeController extends Controller
{
    public function __construct(
        protected ProductTypeService $productTypeService
    ) {}

    /**
     * Display a listing of product types
     */
    public function index()
    {
        $productTypes = $this->productTypeService->getProductTypesWithCounts();

        return view('product-types.index', compact('productTypes'));
    }

    /**
     * Show the form for creating a new product type
     */
    public function create()
    {
        return view('product-types.create');
    }

    /**
     * Store a newly created product type
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:product_types,slug',
            'icon' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'has_variants' => 'boolean',
            'has_expiry_date' => 'boolean',
            'has_weight' => 'boolean',
            'has_dimensions' => 'boolean',
            'has_serial_number' => 'boolean',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer',
        ]);

        try {
            $productType = $this->productTypeService->createProductType($validated);

            return redirect()
                ->route('product-types.edit', $productType)
                ->with('success', 'Type de produit créé avec succès.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la création: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified product type
     */
    public function edit(int $id)
    {
        $productType = $this->productTypeService->getProductTypeById($id);

        if (!$productType) {
            return redirect()
                ->route('product-types.index')
                ->with('error', 'Type de produit introuvable.');
        }

        return view('product-types.edit', compact('productType'));
    }

    /**
     * Update the specified product type
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:product_types,slug,' . $id,
            'icon' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'has_variants' => 'boolean',
            'has_expiry_date' => 'boolean',
            'has_weight' => 'boolean',
            'has_dimensions' => 'boolean',
            'has_serial_number' => 'boolean',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer',
        ]);

        try {
            $productType = $this->productTypeService->updateProductType($id, $validated);

            return redirect()
                ->route('product-types.edit', $productType)
                ->with('success', 'Type de produit mis à jour avec succès.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified product type
     */
    public function destroy(int $id)
    {
        try {
            $this->productTypeService->deleteProductType($id);

            return redirect()
                ->route('product-types.index')
                ->with('success', 'Type de produit supprimé avec succès.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Toggle active status
     */
    public function toggleActive(int $id)
    {
        try {
            $this->productTypeService->toggleActive($id);

            return back()->with('success', 'Statut modifié avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}
