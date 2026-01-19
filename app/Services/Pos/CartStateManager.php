<?php

declare(strict_types=1);

namespace App\Services\Pos;

/**
 * Gestionnaire d'état du panier avec synchronisation automatique
 * Élimine le besoin d'appeler cartService->initialize() manuellement
 */
class CartStateManager
{
    private array $cart = [];

    public function __construct(
        private readonly CartService $cartService
    ) {}

    /**
     * Initialise le panier avec des données existantes
     */
    public function initialize(array $cart): void
    {
        $this->cart = $cart;
        $this->sync();
    }

    /**
     * Obtient l'état actuel du panier
     */
    public function getCart(): array
    {
        return $this->cart;
    }

    /**
     * Ajoute un article au panier avec synchronisation automatique
     */
    public function addItem(int $variantId): array
    {
        $this->sync();
        $result = $this->cartService->addItem($variantId);
        $this->cart = $result['cart'];
        return $result;
    }

    /**
     * Met à jour la quantité avec synchronisation automatique
     */
    public function updateQuantity(string $key, int $quantity): array
    {
        $this->sync();
        $result = $this->cartService->updateQuantity($key, $quantity);
        $this->cart = $result['cart'];
        return $result;
    }

    /**
     * Met à jour le prix négocié avec synchronisation automatique
     */
    public function updatePrice(string $key, float $price): array
    {
        $this->sync();
        $result = $this->cartService->updatePrice($key, $price);
        $this->cart = $result['cart'];
        return $result;
    }

    /**
     * Retire un article avec synchronisation automatique
     */
    public function removeItem(string $key): array
    {
        $this->sync();
        $result = $this->cartService->removeItem($key);
        $this->cart = $result['cart'];
        return $result;
    }

    /**
     * Vide le panier avec synchronisation automatique
     */
    public function clear(): array
    {
        $this->sync();
        $result = $this->cartService->clear();
        $this->cart = $result['cart'];
        return $result;
    }

    /**
     * Vérifie si le panier est vide
     */
    public function isEmpty(): bool
    {
        $this->sync();
        return $this->cartService->isEmpty();
    }

    /**
     * Obtient le nombre d'articles
     */
    public function count(): int
    {
        return count($this->cart);
    }

    /**
     * Obtient les items formatés pour la vente
     */
    public function getItemsForSale(): array
    {
        $this->sync();
        return $this->cartService->getItemsForSale();
    }

    /**
     * Valide le stock avant le paiement
     */
    public function validateStock(): array
    {
        $this->sync();
        return $this->cartService->validateStock();
    }

    /**
     * Recherche un produit par code-barres
     */
    public function findByBarcode(string $barcode): ?int
    {
        return $this->cartService->findByBarcode($barcode);
    }

    /**
     * Synchronise l'état avec le service sous-jacent
     */
    private function sync(): void
    {
        $this->cartService->initialize($this->cart);
    }
}
