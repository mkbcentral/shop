<?php

declare(strict_types=1);

namespace App\Livewire\Pos\Components;

use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

/**
 * Composant Grille de Produits POS
 * Gère l'affichage, la recherche et le filtrage des produits
 */
class PosProductGrid extends Component
{
    use WithPagination;

    // Recherche et filtrage
    public string $search = '';
    public string $categoryFilter = '';
    public string $barcodeInput = '';

    // Configuration
    public int $perPage = 20;

    /**
     * Écoute le focus sur la recherche (raccourci clavier F2)
     */
    #[On('focus-search')]
    public function focusSearch(): void
    {
        $this->dispatch('focus-search-input');
    }

    /**
     * Rafraîchit la grille après un paiement validé (mise à jour du stock)
     */
    #[On('payment-completed')]
    public function refreshAfterPayment(): void
    {
        // Force le rafraîchissement du composant pour mettre à jour les stocks
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Sélectionne un produit (dispatch vers le panier)
     */
    public function selectProduct(int $variantId): void
    {
        $this->dispatch('product-selected', variantId: $variantId);
    }

    /**
     * Gestion du scan de code-barres
     */
    public function handleBarcodeScan(): void
    {
        if (empty($this->barcodeInput)) {
            return;
        }

        $variant = \App\Models\ProductVariant::where('sku', $this->barcodeInput)
            ->orWhere('barcode', $this->barcodeInput)
            ->first();

        if ($variant) {
            $this->selectProduct($variant->id);
            $this->barcodeInput = '';
        } else {
            $this->dispatch('show-toast',
                message: 'Produit introuvable avec ce code-barres.',
                type: 'error'
            );
            $this->barcodeInput = '';
        }
    }

    /**
     * Obtient le storeId courant
     */
    private function getCurrentStoreId(): ?int
    {
        $storeId = current_store_id();
        $user = auth()->user();

        if (!user_can_access_all_stores() && !$storeId && $user) {
            $firstStore = $user->stores()->first();
            if ($firstStore) {
                $user->update(['current_store_id' => $firstStore->id]);
                return $firstStore->id;
            }
        }

        return $storeId;
    }

    /**
     * Construit la requête des produits
     */
    private function buildProductsQuery(ProductRepository $productRepository, ?int $storeId)
    {
        $canAccessAllStores = user_can_access_all_stores();

        $query = $productRepository->query()
            ->with([
                'category:id,name',
                'variants' => function($q) use ($storeId, $canAccessAllStores) {
                    $q->select('id', 'product_id', 'size', 'color', 'sku', 'stock_quantity');

                    // Toujours charger storeStocks pour éviter le lazy loading
                    if ($storeId) {
                        $q->with(['storeStocks' => function($sq) use ($storeId) {
                            $sq->where('store_id', $storeId);
                        }]);
                    } else {
                        // Charger tous les storeStocks si accès à tous les stores
                        $q->with('storeStocks');
                    }

                    // Filtrer les variantes avec du stock
                    if ($storeId && !$canAccessAllStores) {
                        $q->whereHas('storeStocks', function($sq) use ($storeId) {
                            $sq->where('store_id', $storeId)->where('quantity', '>', 0);
                        });
                    } else {
                        $q->where('stock_quantity', '>', 0);
                    }
                }
            ])
            ->select('id', 'name', 'reference', 'price', 'category_id', 'status', 'image', 'store_id')
            ->where('status', 'active');

        // Filtre par magasin - produit doit avoir du stock dans le magasin OU être créé par ce magasin
        if (!user_can_access_all_stores() && $storeId) {
            $query->where(function($q) use ($storeId) {
                // Produit créé par ce magasin
                $q->where('store_id', $storeId)
                // OU produit ayant du stock dans ce magasin
                ->orWhereHas('variants.storeStocks', function($sq) use ($storeId) {
                    $sq->where('store_id', $storeId)->where('quantity', '>', 0);
                });
            });
        } elseif (!user_can_access_all_stores() && !$storeId) {
            $query->whereRaw('1 = 0');
        }

        // Filtre de recherche
        if ($this->search) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(fn($q) => $q->where('name', 'like', $searchTerm)
                ->orWhere('reference', 'like', $searchTerm));
        }

        // Filtre par catégorie
        if ($this->categoryFilter) {
            $query->where('category_id', $this->categoryFilter);
        }

        return $query->orderBy('name');
    }

    /**
     * Récupère les catégories (avec cache)
     */
    private function getCachedCategories(?int $storeId): \Illuminate\Support\Collection
    {
        $cacheKey = $storeId ? "pos.categories.store.{$storeId}" : 'pos.categories.all';

        return \Illuminate\Support\Facades\Cache::remember(
            $cacheKey,
            3600,
            fn() => app(CategoryRepository::class)->all()
        );
    }

    public function render(ProductRepository $productRepository)
    {
        $storeId = $this->getCurrentStoreId();

        return view('livewire.pos.components.pos-product-grid', [
            'products' => $this->buildProductsQuery($productRepository, $storeId)->paginate($this->perPage),
            'categories' => $this->getCachedCategories($storeId),
        ]);
    }
}
