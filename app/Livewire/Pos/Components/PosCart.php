<?php

declare(strict_types=1);

namespace App\Livewire\Pos\Components;

use App\Services\Pos\CartStateManager;
use App\Services\Pos\CalculationService;
use Livewire\Component;
use Livewire\Attributes\On;

/**
 * Composant Panier POS
 * Gère l'affichage et les interactions avec le panier
 */
class PosCart extends Component
{
    // État du panier
    public array $cart = [];
    public float $subtotal = 0;
    public float $total = 0;
    public float $discount = 0;
    public float $tax = 0;

    // Client
    public ?int $selectedClientId = null;
    public bool $quickSaleMode = true;

    // Référence à la dernière vente (pour le bouton aperçu)
    public ?int $lastSaleId = null;

    // Services
    private CartStateManager $cartManager;
    private CalculationService $calculationService;

    public function boot(
        CartStateManager $cartManager,
        CalculationService $calculationService
    ): void {
        $this->cartManager = $cartManager;
        $this->calculationService = $calculationService;
    }

    public function mount(): void
    {
        $this->syncCartFromSession();
        $this->recalculateTotals();

        if ($this->quickSaleMode) {
            $this->selectedClientId = $this->getDefaultClientId();
        }
    }

    public function hydrate(): void
    {
        $this->syncCartFromSession();
    }

    /**
     * Synchronise le panier depuis la session
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
     * Obtient le client par défaut
     */
    private function getDefaultClientId(): ?int
    {
        return \Illuminate\Support\Facades\Cache::remember(
            'pos.default_client_id',
            3600,
            fn() => \App\Models\Client::where('name', 'Comptant')
                ->orWhere('name', 'Client Comptant')
                ->first()?->id
        );
    }

    /**
     * Écoute l'ajout d'un produit depuis ProductGrid
     */
    #[On('product-selected')]
    public function addToCart(int $variantId): void
    {
        $result = $this->cartManager->addItem($variantId);
        $this->cart = $result['cart'];
        $this->persistCart();

        $this->dispatch(
            $result['success'] ? 'show-toast' : 'show-toast',
            message: $result['message'],
            type: $result['success'] ? 'success' : 'error'
        );

        $this->recalculateTotals();
        $this->dispatchCartState();
    }

    /**
     * Met à jour la quantité d'un article
     */
    public function updateQuantity(string $key, int $quantity): void
    {
        $result = $this->cartManager->updateQuantity($key, $quantity);
        $this->cart = $result['cart'];
        $this->persistCart();

        if (!$result['success']) {
            $this->dispatch('show-toast', message: $result['message'], type: 'error');
        }

        $this->recalculateTotals();
        $this->dispatchCartState();
    }

    /**
     * Incrémente la quantité d'un article
     */
    public function incrementQuantity(string $key): void
    {
        if (isset($this->cart[$key])) {
            $this->updateQuantity($key, $this->cart[$key]['quantity'] + 1);
        }
    }

    /**
     * Décrémente la quantité d'un article
     */
    public function decrementQuantity(string $key): void
    {
        if (isset($this->cart[$key]) && $this->cart[$key]['quantity'] > 1) {
            $this->updateQuantity($key, $this->cart[$key]['quantity'] - 1);
        }
    }

    /**
     * Retire un article du panier
     */
    public function removeFromCart(string $key): void
    {
        $result = $this->cartManager->removeItem($key);
        $this->cart = $result['cart'];
        $this->persistCart();
        $this->recalculateTotals();
        $this->dispatchCartState();
    }

    /**
     * Vide le panier
     */
    public function clearCart(): void
    {
        $result = $this->cartManager->clear();
        $this->cart = $result['cart'];
        session()->forget('pos_cart');
        $this->reset(['discount', 'tax']);
        $this->recalculateTotals();
        $this->dispatchCartState();
    }

    /**
     * Écoute le trigger de vidage du panier (raccourci clavier)
     */
    #[On('trigger-clear-cart')]
    public function onTriggerClearCart(): void
    {
        $this->clearCart();
    }

    /**
     * Écoute la complétion d'un paiement pour vider le panier
     */
    #[On('payment-completed')]
    public function onPaymentCompleted(int $saleId = null): void
    {
        $this->lastSaleId = $saleId;
        $this->clearCart();
        $this->selectedClientId = $this->quickSaleMode ? $this->getDefaultClientId() : null;
    }

    /**
     * Demande l'aperçu du reçu au composant de paiement
     */
    public function requestReceiptPreview(): void
    {
        if ($this->lastSaleId) {
            $this->dispatch('show-receipt-preview', saleId: $this->lastSaleId);
        }
    }

    /**
     * Met à jour la remise
     */
    public function updatedDiscount(): void
    {
        $this->recalculateTotals();
        $this->dispatchCartState();
    }

    /**
     * Met à jour la taxe
     */
    public function updatedTax(): void
    {
        $this->recalculateTotals();
        $this->dispatchCartState();
    }

    /**
     * Sélectionne un client
     */
    public function selectClient(int $clientId): void
    {
        $this->selectedClientId = $clientId;
        $this->dispatch('client-selected', clientId: $clientId);
    }

    /**
     * Bascule le mode Quick Sale
     */
    public function toggleQuickSaleMode(): void
    {
        $this->quickSaleMode = !$this->quickSaleMode;
        $this->selectedClientId = $this->quickSaleMode ? $this->getDefaultClientId() : null;
    }

    /**
     * Recalcule les totaux
     */
    private function recalculateTotals(): void
    {
        $totals = $this->calculationService->calculateTotals(
            $this->cart,
            $this->discount,
            $this->tax
        );

        $this->subtotal = $totals['subtotal'];
        $this->tax = $totals['tax'];
        $this->total = $totals['total'];
    }

    /**
     * Dispatch l'état du panier au parent
     */
    private function dispatchCartState(): void
    {
        $this->dispatch('cart-state-changed', [
            'cart' => $this->cart,
            'subtotal' => $this->subtotal,
            'tax' => $this->tax,
            'total' => $this->total,
            'discount' => $this->discount,
            'clientId' => $this->selectedClientId,
            'itemCount' => count($this->cart),
        ]);
    }

    /**
     * Computed: Client sélectionné
     */
    public function getSelectedClientProperty()
    {
        if (!$this->selectedClientId) {
            return null;
        }

        static $cached = null;
        static $cachedId = null;

        if ($cachedId === $this->selectedClientId) {
            return $cached;
        }

        $cachedId = $this->selectedClientId;
        $cached = \App\Models\Client::select('id', 'name', 'phone')->find($this->selectedClientId);

        return $cached;
    }

    /**
     * Computed: Nombre d'articles
     */
    public function getItemCountProperty(): int
    {
        return count($this->cart);
    }

    /**
     * Computed: Quantité totale
     */
    public function getTotalQuantityProperty(): int
    {
        return array_sum(array_column($this->cart, 'quantity'));
    }

    public function render()
    {
        $clients = \Illuminate\Support\Facades\Cache::remember(
            'pos.active_clients',
            600,
            fn() => \App\Models\Client::select('id', 'name', 'phone')
                ->orderBy('name')
                ->get()
        );

        return view('livewire.pos.components.pos-cart', [
            'clients' => $clients,
        ]);
    }
}
