<?php

declare(strict_types=1);

namespace App\Livewire\Pos\Traits;

use App\Services\Pos\CartStateManager;
use App\Services\Pos\CalculationService;

/**
 * Trait partagé pour la gestion du panier POS
 * Centralise la logique du panier pour éviter la duplication
 */
trait WithPosCart
{
    public array $cart = [];
    public float $subtotal = 0;
    public float $total = 0;
    public float $discount = 0;
    public float $tax = 0;

    private CartStateManager $cartManager;
    private CalculationService $calculationService;

    /**
     * Initialise les services du panier
     */
    protected function bootWithPosCart(
        CartStateManager $cartManager,
        CalculationService $calculationService
    ): void {
        $this->cartManager = $cartManager;
        $this->calculationService = $calculationService;
    }

    /**
     * Synchronise le panier depuis la session
     */
    protected function syncCartFromSession(): void
    {
        $this->cart = session()->get('pos_cart', []);
        $this->cartManager->initialize($this->cart);
    }

    /**
     * Persiste le panier dans la session
     */
    protected function persistCart(): void
    {
        session()->put('pos_cart', $this->cart);
    }

    /**
     * Ajoute un article au panier
     */
    public function addToCart(int $variantId): void
    {
        $result = $this->cartManager->addItem($variantId);
        $this->cart = $result['cart'];
        $this->persistCart();

        $this->dispatch(
            $result['success'] ? 'cart-updated' : 'cart-error',
            message: $result['message'],
            cart: $this->cart
        );

        $this->recalculateTotals();
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
            $this->dispatch('cart-error', message: $result['message']);
        } else {
            $this->dispatch('cart-updated', cart: $this->cart);
        }

        $this->recalculateTotals();
    }

    /**
     * Retire un article du panier
     */
    public function removeFromCart(string $key): void
    {
        $result = $this->cartManager->removeItem($key);
        $this->cart = $result['cart'];
        $this->persistCart();
        $this->dispatch('cart-updated', cart: $this->cart);
        $this->recalculateTotals();
    }

    /**
     * Vide le panier
     */
    public function clearCart(): void
    {
        $result = $this->cartManager->clear();
        $this->cart = $result['cart'];
        session()->forget('pos_cart');
        $this->dispatch('cart-cleared');
        $this->recalculateTotals();
    }

    /**
     * Recalcule les totaux du panier
     */
    protected function recalculateTotals(): void
    {
        $totals = $this->calculationService->calculateTotals(
            $this->cart,
            $this->discount,
            $this->tax
        );

        $this->subtotal = $totals['subtotal'];
        $this->tax = $totals['tax'];
        $this->total = $totals['total'];

        $this->dispatch('totals-updated', [
            'subtotal' => $this->subtotal,
            'tax' => $this->tax,
            'total' => $this->total,
            'itemCount' => count($this->cart),
        ]);
    }

    /**
     * Vérifie si le panier est vide
     */
    public function isCartEmpty(): bool
    {
        return empty($this->cart);
    }

    /**
     * Obtient le nombre d'articles dans le panier
     */
    public function getCartCountProperty(): int
    {
        return count($this->cart);
    }

    /**
     * Obtient le total d'articles (quantités)
     */
    public function getCartItemsCountProperty(): int
    {
        return array_sum(array_column($this->cart, 'quantity'));
    }
}
