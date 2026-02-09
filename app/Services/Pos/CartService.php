<?php

namespace App\Services\Pos;

use App\Repositories\ProductVariantRepository;

class CartService
{
    protected array $cart = [];
    protected ProductVariantRepository $variantRepository;

    public function __construct()
    {
        $this->variantRepository = app(ProductVariantRepository::class);
    }

    /**
     * Initialise le panier avec des données existantes
     */
    public function initialize(array $cart): void
    {
        $this->cart = $cart;
    }

    /**
     * Obtient le panier
     */
    public function getCart(): array
    {
        return $this->cart;
    }

    /**
     * Ajoute un produit au panier
     */
    public function addItem(int $variantId): array
    {
        $variant = $this->variantRepository->query()
            ->with('product.productType')
            ->find($variantId);

        if (!$variant) {
            return [
                'success' => false,
                'message' => 'Produit introuvable.',
                'cart' => $this->cart
            ];
        }

        // Services don't have stock limits
        $isService = $variant->product->productType?->is_service ?? false;

        if (!$isService && $variant->stock_quantity <= 0) {
            return [
                'success' => false,
                'message' => 'Produit en rupture de stock.',
                'cart' => $this->cart
            ];
        }

        $key = 'variant_' . $variantId;

        if (isset($this->cart[$key])) {
            // Services have unlimited quantity
            if ($isService || $this->cart[$key]['quantity'] < $variant->stock_quantity) {
                $this->cart[$key]['quantity']++;
                return [
                    'success' => true,
                    'message' => 'Quantité mise à jour.',
                    'cart' => $this->cart
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Stock insuffisant.',
                    'cart' => $this->cart
                ];
            }
        }

        $this->cart[$key] = [
            'variant_id' => $variantId,
            'product_id' => $variant->product->id,
            'product_name' => $variant->product->name,
            'variant_size' => $variant->size,
            'variant_color' => $variant->color,
            'price' => $variant->product->price,
            'original_price' => $variant->product->price,
            'max_discount_amount' => $variant->product->max_discount_amount,
            'quantity' => 1,
            'stock' => $isService ? 999999 : $variant->stock_quantity,
            'is_service' => $isService,
        ];

        return [
            'success' => true,
            'message' => 'Produit ajouté au panier.',
            'cart' => $this->cart
        ];
    }

    /**
     * Met à jour la quantité d'un article
     */
    public function updateQuantity(string $key, int $quantity): array
    {
        if (!isset($this->cart[$key])) {
            return [
                'success' => false,
                'message' => 'Article introuvable.',
                'cart' => $this->cart
            ];
        }

        $quantity = max(0, $quantity);

        if ($quantity === 0) {
            unset($this->cart[$key]);
            return [
                'success' => true,
                'message' => 'Article retiré du panier.',
                'cart' => $this->cart
            ];
        }

        if ($quantity > $this->cart[$key]['stock']) {
            // Services have unlimited stock (stock = 999999)
            $isService = $this->cart[$key]['is_service'] ?? false;
            if (!$isService) {
                return [
                    'success' => false,
                    'message' => 'Stock insuffisant.',
                    'cart' => $this->cart
                ];
            }
        }

        $this->cart[$key]['quantity'] = $quantity;

        return [
            'success' => true,
            'message' => 'Quantité mise à jour.',
            'cart' => $this->cart
        ];
    }

    /**
     * Met à jour le prix négocié d'un article
     */
    public function updatePrice(string $key, float $price): array
    {
        if (!isset($this->cart[$key])) {
            return [
                'success' => false,
                'message' => 'Article introuvable.',
                'cart' => $this->cart
            ];
        }

        $price = max(0, $price);
        $originalPrice = $this->cart[$key]['original_price'] ?? $this->cart[$key]['price'];
        $maxDiscountAmount = $this->cart[$key]['max_discount_amount'] ?? null;

        // Ne pas permettre un prix supérieur au prix original
        if ($price > $originalPrice) {
            $price = $originalPrice;
        }

        // Vérifier si le produit a une limite de remise
        if ($maxDiscountAmount !== null && $maxDiscountAmount > 0) {
            // Calculer le prix minimum autorisé (prix original - remise max)
            $minAllowedPrice = $originalPrice - $maxDiscountAmount;

            // Ne pas permettre un prix inférieur au prix minimum
            if ($price < $minAllowedPrice) {
                return [
                    'success' => false,
                    'message' => "Le prix ne peut pas être inférieur à " . number_format($minAllowedPrice, 0, ',', ' ') . " " . current_currency() . " (remise max: " . number_format($maxDiscountAmount, 0, ',', ' ') . " " . current_currency() . ")",
                    'cart' => $this->cart
                ];
            }
        }

        $this->cart[$key]['price'] = $price;

        return [
            'success' => true,
            'message' => 'Prix négocié appliqué.',
            'cart' => $this->cart
        ];
    }

    /**
     * Retire un article du panier
     */
    public function removeItem(string $key): array
    {
        if (isset($this->cart[$key])) {
            unset($this->cart[$key]);
        }

        return [
            'success' => true,
            'message' => 'Article retiré.',
            'cart' => $this->cart
        ];
    }

    /**
     * Vide le panier
     */
    public function clear(): array
    {
        $this->cart = [];

        return [
            'success' => true,
            'message' => 'Panier vidé.',
            'cart' => $this->cart
        ];
    }

    /**
     * Vérifie si le panier est vide
     */
    public function isEmpty(): bool
    {
        return empty($this->cart);
    }

    /**
     * Obtient le nombre d'articles
     */
    public function count(): int
    {
        return count($this->cart);
    }

    /**
     * Obtient les items du panier pour la vente
     */
    public function getItemsForSale(): array
    {
        $items = [];
        foreach ($this->cart as $item) {
            $items[] = [
                'product_variant_id' => $item['variant_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
            ];
        }
        return $items;
    }

    /**
     * Valide le stock avant le paiement
     */
    public function validateStock(): array
    {
        foreach ($this->cart as $item) {
            // Skip stock validation for services
            if ($item['is_service'] ?? false) {
                continue;
            }

            $variant = $this->variantRepository->find($item['variant_id']);

            if (!$variant || $variant->stock_quantity < $item['quantity']) {
                return [
                    'valid' => false,
                    'message' => "Stock insuffisant pour {$item['product_name']}. Disponible: " .
                                ($variant ? $variant->stock_quantity : 0)
                ];
            }
        }

        return ['valid' => true];
    }

    /**
     * Recherche un produit par code-barres
     */
    public function findByBarcode(string $barcode): ?int
    {
        $variant = $this->variantRepository
            ->query()
            ->whereHas('product', function($query) use ($barcode) {
                $query->where('reference', $barcode)
                    ->orWhere('barcode', $barcode);
            })
            ->first();

        return $variant?->id;
    }
}
