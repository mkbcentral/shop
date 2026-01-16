<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ProductVariantRepository;
use App\Repositories\StockMovementRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        protected ProductRepository $productRepository,
        protected ProductVariantRepository $productVariantRepository,
        protected StockMovementRepository $stockMovementRepository,
        protected CategoryRepository $categoryRepository
    ) {}

    /**
     * Rapport de tous les produits
     */
    public function products(Request $request)
    {
        $products = $this->productRepository->forReport(
            $request->filled('category_id') ? (int) $request->category_id : null,
            $request->filled('status') ? $request->status : null
        );

        $categoryName = $request->category_id 
            ? $this->categoryRepository->find($request->category_id)?->name 
            : 'Toutes';

        $data = [
            'title' => 'Liste des Produits',
            'date' => now()->format('d/m/Y H:i'),
            'products' => $products,
            'filters' => [
                'category' => $categoryName,
                'status' => $request->status ?? 'Tous',
            ],
            'totals' => [
                'products' => $products->count(),
                'variants' => $products->sum(fn($p) => $p->variants->count()),
                'total_stock' => $products->sum(fn($p) => $p->variants->sum('stock_quantity')),
                'total_value' => $products->sum(fn($p) => $p->variants->sum('stock_quantity') * ($p->cost_price ?? 0)),
            ],
        ];

        $pdf = Pdf::loadView('reports.products', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('produits_' . now()->format('Y-m-d_His') . '.pdf');
    }

    /**
     * Rapport de l'état du stock
     */
    public function stock(Request $request)
    {
        $variants = $this->productVariantRepository->forStockReport(
            $request->filled('stock_level') ? $request->stock_level : null,
            $request->filled('category_id') ? (int) $request->category_id : null
        );

        $categoryName = $request->category_id 
            ? $this->categoryRepository->find($request->category_id)?->name 
            : 'Toutes';

        $data = [
            'title' => 'État du Stock',
            'date' => now()->format('d/m/Y H:i'),
            'variants' => $variants,
            'filters' => [
                'stock_level' => match($request->stock_level) {
                    'out' => 'Rupture de stock',
                    'low' => 'Stock faible',
                    'normal' => 'Stock normal',
                    default => 'Tous'
                },
                'category' => $categoryName,
            ],
            'totals' => [
                'variants' => $variants->count(),
                'total_stock' => $variants->sum('stock_quantity'),
                'total_value' => $variants->sum(fn($v) => $v->stock_quantity * ($v->product->cost_price ?? 0)),
                'out_of_stock' => $variants->where('stock_quantity', '<=', 0)->count(),
                'low_stock' => $variants->filter(fn($v) => $v->stock_quantity > 0 && $v->stock_quantity <= $v->low_stock_threshold)->count(),
            ],
        ];

        $pdf = Pdf::loadView('reports.stock', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('etat_stock_' . now()->format('Y-m-d_His') . '.pdf');
    }

    /**
     * Rapport des mouvements de stock
     */
    public function stockMovements(Request $request)
    {
        $movements = $this->stockMovementRepository->forReport(
            $request->filled('date_from') ? $request->date_from : null,
            $request->filled('date_to') ? $request->date_to : null,
            $request->filled('type') ? $request->type : null,
            $request->filled('movement_type') ? $request->movement_type : null,
            $request->filled('product_variant_id') ? (int) $request->product_variant_id : null
        );

        $data = [
            'title' => 'Mouvements de Stock',
            'date' => now()->format('d/m/Y H:i'),
            'movements' => $movements,
            'filters' => [
                'date_from' => $request->date_from ? \Carbon\Carbon::parse($request->date_from)->format('d/m/Y') : 'N/A',
                'date_to' => $request->date_to ? \Carbon\Carbon::parse($request->date_to)->format('d/m/Y') : 'N/A',
                'type' => match($request->type) {
                    'in' => 'Entrées',
                    'out' => 'Sorties',
                    default => 'Tous'
                },
                'movement_type' => $request->movement_type ?? 'Tous',
            ],
            'totals' => [
                'movements' => $movements->count(),
                'entries' => $movements->where('type', 'in')->sum('quantity'),
                'exits' => $movements->where('type', 'out')->sum('quantity'),
                'total_value' => $movements->sum(fn($m) => $m->quantity * ($m->unit_price ?? 0)),
            ],
        ];

        $pdf = Pdf::loadView('reports.stock-movements', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('mouvements_stock_' . now()->format('Y-m-d_His') . '.pdf');
    }

    /**
     * Rapport d'inventaire valorisé
     */
    public function inventory(Request $request)
    {
        $variants = $this->productVariantRepository->forInventoryReport();

        // Grouper par catégorie
        $byCategory = $variants->groupBy(fn($v) => $v->product->category?->name ?? 'Sans catégorie');

        $data = [
            'title' => 'Inventaire Valorisé',
            'date' => now()->format('d/m/Y H:i'),
            'byCategory' => $byCategory,
            'totals' => [
                'variants' => $variants->count(),
                'total_stock' => $variants->sum('stock_quantity'),
                'total_cost_value' => $variants->sum(fn($v) => $v->stock_quantity * ($v->product->cost_price ?? 0)),
                'total_sale_value' => $variants->sum(fn($v) => $v->stock_quantity * ($v->product->selling_price ?? 0)),
            ],
        ];

        $pdf = Pdf::loadView('reports.inventory', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('inventaire_' . now()->format('Y-m-d_His') . '.pdf');
    }

    /**
     * Rapport des alertes de stock
     */
    public function stockAlerts(Request $request)
    {
        $outOfStock = $this->productVariantRepository->outOfStockWithDetails();
        $lowStock = $this->productVariantRepository->lowStockWithDetails();

        $data = [
            'title' => 'Alertes de Stock',
            'date' => now()->format('d/m/Y H:i'),
            'outOfStock' => $outOfStock,
            'lowStock' => $lowStock,
            'totals' => [
                'out_of_stock_count' => $outOfStock->count(),
                'low_stock_count' => $lowStock->count(),
                'total_alerts' => $outOfStock->count() + $lowStock->count(),
            ],
        ];

        $pdf = Pdf::loadView('reports.stock-alerts', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('alertes_stock_' . now()->format('Y-m-d_His') . '.pdf');
    }
}
