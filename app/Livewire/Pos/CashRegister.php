<?php

declare(strict_types=1);

namespace App\Livewire\Pos;

use App\Exceptions\Pos\CartEmptyException;
use App\Exceptions\Pos\InsufficientPaymentException;
use App\Exceptions\Pos\InsufficientStockException;
use App\Repositories\ProductRepository;
use App\Repositories\ClientRepository;
use App\Models\Sale;
use App\Models\Invoice;
use App\Services\Pos\CartStateManager;
use App\Services\Pos\CalculationService;
use App\Services\Pos\PaymentService;
use App\Services\Pos\PaymentData;
use App\Services\Pos\PaymentResult;
use App\Services\Pos\StatsService;
use App\Services\Pos\PrinterService;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class CashRegister extends Component
{
    use WithPagination;

    // Propriétés de recherche et filtrage
    public string $search = '';
    public string $categoryFilter = '';

    // Propriétés du panier et client
    public array $cart = [];
    public ?int $clientId = null;

    // Propriétés de paiement
    public string $paymentMethod = 'cash';
    public float $paidAmount = 0;
    public float $discount = 0;
    public float $tax = 0;
    public string $notes = '';

    // Propriétés calculées
    public float $subtotal = 0;
    public float $total = 0;
    public float $change = 0;

    // Propriétés de reçu
    public bool $showReceipt = false;
    public ?int $lastSaleId = null;
    public ?int $lastInvoiceId = null;

    // Messages de feedback
    public string $errorMessage = '';
    public string $successMessage = '';

    // Propriétés de mode et interactions
    public bool $quickSaleMode = true;
    public string $barcodeInput = '';
    public bool $showVariantModal = false;
    public mixed $selectedProduct = null;
    public array $transactionHistory = [];
    public array $todayStats = [
        'sales_count' => 0,
        'revenue' => 0,
        'transactions' => 0
    ];
    public array $changeBreakdown = [];
    public array $suggestedAmounts = [];
    public bool $showStats = false;

    // Propriétés pour la création de client
    public string $newClientName = '';
    public string $newClientPhone = '';

    protected $listeners = ['printReceipt', 'refreshStats'];

    // Services injectés
    private CartStateManager $cartManager;
    private CalculationService $calculationService;
    private PaymentService $paymentService;
    private StatsService $statsService;
    private PrinterService $printerService;

    public function boot(
        CartStateManager $cartManager,
        CalculationService $calculationService,
        PaymentService $paymentService,
        StatsService $statsService,
        PrinterService $printerService
    ) {
        $this->cartManager = $cartManager;
        $this->calculationService = $calculationService;
        $this->paymentService = $paymentService;
        $this->statsService = $statsService;
        $this->printerService = $printerService;
    }

    public function mount(): void
    {
        // Charger le panier depuis la session
        $this->syncCartFromSession();
        $this->calculateTotals();
        $this->loadTodayStats();
        $this->loadTransactionHistory();

        // Client par défaut en mode Quick Sale
        if ($this->quickSaleMode) {
            $this->clientId = $this->getDefaultClientId();
        }
    }

    /**
     * Hook appelé à chaque requête Livewire pour restaurer l'état du panier
     */
    public function hydrate(): void
    {
        $this->syncCartFromSession();
    }

    /**
     * Synchronise le panier depuis la session et initialise le cartManager
     */
    private function syncCartFromSession(): void
    {
        $this->cart = session()->get('pos_cart', []);
        $this->cartManager->initialize($this->cart);
    }

    /**
     * Persiste le panier dans la session
     */
    private function persistCart(): void
    {
        session()->put('pos_cart', $this->cart);
    }

    /**
     * Obtient l'ID de l'utilisateur authentifié
     */
    private function getUserId(): int
    {
        /** @var int|null $userId */
        $userId = auth()->id();
        if (!$userId) {
            throw new \RuntimeException('Utilisateur non authentifié');
        }
        return $userId;
    }

    /**
     * Charge les statistiques du jour
     */
    public function loadTodayStats(): void
    {
        $this->todayStats = $this->statsService->loadTodayStats($this->getUserId());
    }

    /**
     * Charge l'historique des transactions
     */
    public function loadTransactionHistory(): void
    {
        $this->transactionHistory = $this->statsService->loadTransactionHistory($this->getUserId());
    }

    /**
     * Obtient l'ID du client par défaut (Comptant) - avec cache
     */
    private function getDefaultClientId(): ?int
    {
        return \Illuminate\Support\Facades\Cache::remember(
            'pos.default_client_id',
            3600, // 1 heure
            function () {
                $defaultClient = \App\Models\Client::where('name', 'Comptant')
                    ->orWhere('name', 'Client Comptant')
                    ->first();
                return $defaultClient?->id;
            }
        );
    }

    /**
     * Computed property pour récupérer le client sélectionné - avec cache mémoire
     */
    public function getSelectedClientProperty()
    {
        if (!$this->clientId) {
            return null;
        }

        // Cache en mémoire pour éviter les requêtes répétées dans le même cycle
        static $cachedClient = null;
        static $cachedClientId = null;

        if ($cachedClientId === $this->clientId && $cachedClient !== null) {
            return $cachedClient;
        }

        $cachedClientId = $this->clientId;
        $cachedClient = \App\Models\Client::select('id', 'name', 'phone', 'email')
            ->find($this->clientId);

        return $cachedClient;
    }

    /**
     * Computed property pour récupérer la dernière vente - avec cache mémoire
     */
    public function getLastSaleProperty()
    {
        if (!$this->lastSaleId) {
            return null;
        }

        static $cachedSale = null;
        static $cachedSaleId = null;

        if ($cachedSaleId === $this->lastSaleId && $cachedSale !== null) {
            return $cachedSale;
        }

        $cachedSaleId = $this->lastSaleId;
        $cachedSale = Sale::with(['items.productVariant.product', 'client', 'store', 'user'])
            ->find($this->lastSaleId);

        return $cachedSale;
    }

    /**
     * Computed property pour récupérer la dernière facture - avec cache mémoire
     */
    public function getLastInvoiceProperty()
    {
        if (!$this->lastInvoiceId) {
            return null;
        }

        static $cachedInvoice = null;
        static $cachedInvoiceId = null;

        if ($cachedInvoiceId === $this->lastInvoiceId && $cachedInvoice !== null) {
            return $cachedInvoice;
        }

        $cachedInvoiceId = $this->lastInvoiceId;
        $cachedInvoice = Invoice::with('organization')->find($this->lastInvoiceId);

        return $cachedInvoice;
    }

    /**
     * Bascule le mode Quick Sale
     */
    public function toggleQuickSaleMode(): void
    {
        $this->quickSaleMode = !$this->quickSaleMode;

        if ($this->quickSaleMode) {
            $this->clientId = $this->getDefaultClientId();
        } else {
            $this->clientId = null;
        }
    }

    /**
     * Gestion du scan de code-barres
     */
    public function handleBarcodeScan(): void
    {
        if (empty($this->barcodeInput)) {
            return;
        }

        $variantId = $this->cartManager->findByBarcode($this->barcodeInput);

        if ($variantId) {
            $this->addToCart($variantId);
            $this->barcodeInput = '';
        } else {
            $this->errorMessage = 'Produit introuvable avec ce code-barres.';
            $this->barcodeInput = '';
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatedPaidAmount()
    {
        $this->calculateChange();
    }

    public function updatedDiscount()
    {
        $this->calculateTotals();
    }

    public function updatedTax()
    {
        $this->calculateTotals();
    }

    public function addToCart(int $variantId): void
    {
        $result = $this->cartManager->addItem($variantId);
        $this->cart = $result['cart'];
        $this->persistCart();

        $this->dispatch(
            $result['success'] ? 'cart-success' : 'cart-error',
            message: $result['message']
        );

        $this->calculateTotals();
    }

    public function updateQuantity(string $key, int $quantity): void
    {
        $result = $this->cartManager->updateQuantity($key, $quantity);
        $this->cart = $result['cart'];
        $this->persistCart();

        if (!$result['success']) {
            $this->dispatch('cart-error', message: $result['message']);
        }

        $this->calculateTotals();
    }

    public function removeFromCart(string $key): void
    {
        $result = $this->cartManager->removeItem($key);
        $this->cart = $result['cart'];
        $this->persistCart();
        $this->calculateTotals();
    }

    public function clearCart(): void
    {
        $result = $this->cartManager->clear();
        $this->cart = $result['cart'];
        session()->forget('pos_cart');
        $this->calculateTotals();
        $this->reset(['clientId', 'discount', 'tax', 'notes', 'paidAmount']);
    }

    public function calculateTotals(): void
    {
        $totals = $this->calculationService->calculateTotals(
            $this->cart,
            $this->discount,
            $this->tax
        );

        $this->subtotal = $totals['subtotal'];
        $this->tax = $totals['tax'];
        $this->total = $totals['total'];
        $this->paidAmount = $this->total;

        $this->calculateChange();
        $this->calculateSuggestedAmounts();
    }

    public function calculateChange(): void
    {
        $this->change = $this->calculationService->calculateChange(
            $this->paidAmount,
            $this->total
        );
        $this->calculateChangeBreakdown();
    }

    /**
     * Calcule la décomposition du rendu de monnaie en billets/pièces
     */
    public function calculateChangeBreakdown(): void
    {
        $this->changeBreakdown = $this->calculationService->calculateChangeBreakdown($this->change);
    }

    /**
     * Calcule les montants suggérés pour faciliter le paiement
     */
    public function calculateSuggestedAmounts(): void
    {
        $this->suggestedAmounts = $this->calculationService->calculateSuggestedAmounts($this->total);
    }

    /**
     * Utilise un montant suggéré
     */
    public function useSuggestedAmount(float $amount): void
    {
        $this->paidAmount = $amount;
        $this->calculateChange();
    }

    public function processPayment(): void
    {
        $this->successMessage = '';
        $this->errorMessage = '';

        try {
            // Préparer les données de paiement
            $items = $this->cartManager->getItemsForSale();
            $stockValidation = $this->cartManager->validateStock();

            $paymentData = PaymentData::fromComponent($this, $items, $stockValidation);

            // Traiter le paiement via le service
            $result = $this->paymentService->process($paymentData);

            // Gérer le succès
            $this->handleSuccessfulPayment($result);

        } catch (CartEmptyException | InsufficientPaymentException | InsufficientStockException $e) {
            // Erreurs de validation métier
            $this->errorMessage = $e->getMessage();
            $this->dispatch('show-toast', message: $e->getMessage(), type: 'warning');

        } catch (\Exception $e) {
            // Erreurs techniques
            $this->errorMessage = 'Erreur lors du traitement du paiement.';
            $this->dispatch('show-toast', message: 'Erreur technique: ' . $e->getMessage(), type: 'error');
        }
    }

    /**
     * Gère le succès d'un paiement
     */
    private function handleSuccessfulPayment(PaymentResult $result, bool $autoPrint = true): void
    {
        $this->lastSaleId = $result->sale->id;
        $this->lastInvoiceId = $result->invoice->id;
        $this->change = $result->change;
        $this->showReceipt = false;
        $this->successMessage = 'Vente enregistrée avec succès !';

        // Vider le panier après la vente réussie (sans toucher à lastSaleId/lastInvoiceId)
        $this->cartManager->initialize($this->cart);
        $result_clear = $this->cartManager->clear();
        $this->cart = $result_clear['cart'];
        session()->forget('pos_cart');
        $this->calculateTotals();
        $this->reset(['clientId', 'discount', 'tax', 'notes', 'paidAmount']);

        // Impression thermique automatique uniquement si demandé
        if ($autoPrint) {
            $this->dispatchPrintReceipt();
        }

        // Mettre à jour les stats et historique
        $this->statsService->invalidateStatsCache($this->getUserId());
        $this->loadTodayStats();
        $this->loadTransactionHistory();

        // Notifications
        $this->dispatch('sale-completed');
        $this->dispatch('show-toast', message: 'Vente complétée avec succès!', type: 'success');
    }

    /**
     * Valide le paiement sans imprimer
     */
    public function processPaymentOnly(): void
    {
        $this->successMessage = '';
        $this->errorMessage = '';

        try {
            // Préparer les données de paiement
            $items = $this->cartManager->getItemsForSale();
            $stockValidation = $this->cartManager->validateStock();

            $paymentData = PaymentData::fromComponent($this, $items, $stockValidation);

            // Traiter le paiement via le service
            $result = $this->paymentService->process($paymentData);

            // Gérer le succès SANS impression automatique
            $this->handleSuccessfulPayment($result, false);

        } catch (CartEmptyException | InsufficientPaymentException | InsufficientStockException $e) {
            $this->errorMessage = $e->getMessage();
            $this->dispatch('show-toast', message: $e->getMessage(), type: 'warning');

        } catch (\Exception $e) {
            $this->errorMessage = 'Erreur lors du traitement du paiement.';
            $this->dispatch('show-toast', message: 'Erreur technique: ' . $e->getMessage(), type: 'error');
        }
    }

    /**
     * Affiche la prévisualisation du reçu
     */
    public function previewReceipt(): void
    {
        if ($this->lastSaleId && $this->lastInvoiceId) {
            $this->showReceipt = true;
        } else {
            $this->dispatch('show-toast', message: 'Aucune vente à prévisualiser. Validez d\'abord la vente.', type: 'warning');
        }
    }

    /**
     * Dispatch l'événement d'impression
     */
    private function dispatchPrintReceipt(): void
    {
        if ($this->lastSaleId && $this->lastInvoiceId) {
            $lastSale = Sale::find($this->lastSaleId);
            $lastInvoice = Invoice::with('organization')->find($this->lastInvoiceId);

            if ($lastSale && $lastInvoice) {
                $receiptData = $this->printerService->prepareReceiptData(
                    $lastSale,
                    $lastInvoice,
                    $this->change
                );

                $this->dispatch('print-thermal-receipt', $receiptData);
            }
        }
    }

    /**
     * Réimprime une transaction depuis l'historique
     */
    public function reprintTransaction(int $saleId): void
    {
        $sale = Sale::with(['items.productVariant.product', 'invoice.organization'])->find($saleId);

        if (!$sale || !$sale->invoice) {
            $this->errorMessage = 'Transaction introuvable.';
            return;
        }

        $change = $this->calculationService->calculateChange((float) $sale->paid_amount, (float) $sale->total);
        $receiptData = $this->printerService->prepareReceiptData($sale, $sale->invoice, $change);

        $this->dispatch('print-thermal-receipt', $receiptData);
        $this->dispatch('show-toast', message: 'Reçu réimprimé!', type: 'info');
    }

    /**
     * Raccourci clavier : Valider la vente (F9)
     */
    #[On('keyboard-shortcut-f9')]
    public function keyboardValidateSale(): void
    {
        if (!$this->cartManager->isEmpty()) {
            $this->processPayment();
        }
    }

    /**
     * Raccourci clavier : Vider le panier (F4)
     */
    #[On('keyboard-shortcut-f4')]
    public function keyboardClearCart(): void
    {
        $this->clearCart();
    }

    /**
     * Raccourci clavier : Focus sur la recherche (F2)
     */
    #[On('keyboard-shortcut-f2')]
    public function keyboardFocusSearch(): void
    {
        $this->dispatch('focus-search');
    }

    /**
     * Bascule l'affichage des statistiques
     */
    public function toggleStats(): void
    {
        $this->showStats = !$this->showStats;
    }

    /**
     * Créer un nouveau client
     */
    public function createClient(): void
    {
        $this->validate([
            'newClientName' => 'required|string|max:255',
            'newClientPhone' => 'nullable|string|max:20',
        ], [
            'newClientName.required' => 'Le nom du client est obligatoire.',
            'newClientName.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'newClientPhone.max' => 'Le téléphone ne peut pas dépasser 20 caractères.',
        ]);

        try {
            $clientRepository = app(ClientRepository::class);

            $client = $clientRepository->create([
                'name' => $this->newClientName,
                'phone' => $this->newClientPhone ?: null,
                'email' => null,
                'address' => null,
            ]);

            // Sélectionner automatiquement le nouveau client
            $this->clientId = $client->id;

            // Réinitialiser le formulaire
            $this->reset(['newClientName', 'newClientPhone']);

            $this->successMessage = 'Client créé avec succès !';

            // Fermer le formulaire et le modal après création
            $this->dispatch('client-created');

        } catch (\Exception $e) {
            $this->errorMessage = 'Erreur lors de la création du client : ' . $e->getMessage();
        }
    }

    public function printReceipt(): void
    {
        // Réimprimer le reçu
        if ($this->lastSaleId && $this->lastInvoiceId) {
            $lastSale = Sale::find($this->lastSaleId);
            $lastInvoice = Invoice::with('organization')->find($this->lastInvoiceId);
            if ($lastSale && $lastInvoice) {
                $receiptData = $this->printerService->prepareReceiptData(
                    $lastSale,
                    $lastInvoice,
                    $this->change
                );
                $this->dispatch('print-thermal-receipt', $receiptData);
            }
        }
    }

    public function closeReceipt(): void
    {
        $this->showReceipt = false;
        $this->clearCart();
        $this->lastSaleId = null;
        $this->lastInvoiceId = null;
    }

    /**
     * Obtient le storeId courant avec auto-assignation si nécessaire
     */
    private function getStoreIdWithAutoAssign(): ?int
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
     * Construit la requête des produits optimisée
     */
    private function buildProductsQuery(ProductRepository $productRepository, ?int $storeId)
    {
        $query = $productRepository->query()
            ->with([
                'category:id,name',
                'variants' => fn($q) => $q->select('id', 'product_id', 'size', 'color', 'sku', 'stock_quantity')
                    ->where('stock_quantity', '>', 0)
            ])
            ->select('id', 'name', 'reference', 'price', 'category_id', 'status', 'image', 'store_id')
            ->where('status', 'active');

        // Filtre par magasin
        if (!user_can_access_all_stores()) {
            $storeId ? $query->where('store_id', $storeId) : $query->whereRaw('1 = 0');
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
            fn() => app(\App\Repositories\CategoryRepository::class)->all()
        );
    }

    /**
     * Récupère les clients (avec cache)
     */
    private function getCachedClients(): \Illuminate\Support\Collection
    {
        return \Illuminate\Support\Facades\Cache::remember(
            'pos.active_clients',
            600,
            fn() => \App\Models\Client::select('id', 'name', 'phone')
                ->orderBy('name')
                ->get()
        );
    }

    public function render(ProductRepository $productRepository, ClientRepository $clientRepository)
    {
        $storeId = $this->getStoreIdWithAutoAssign();

        return view('livewire.pos.cash-register', [
            'products' => $this->buildProductsQuery($productRepository, $storeId)->paginate(20),
            'clients' => $this->getCachedClients(),
            'categories' => $this->getCachedCategories($storeId),
        ]);
    }
}
