<?php

namespace App\Enums;

enum SubscriptionPlan: string
{
    case FREE = 'free';
    case STARTER = 'starter';
    case PROFESSIONAL = 'professional';
    case ENTERPRISE = 'enterprise';

    /**
     * Get the display label for the plan
     */
    public function label(): string
    {
        return match($this) {
            self::FREE => 'Gratuit',
            self::STARTER => 'Starter',
            self::PROFESSIONAL => 'Professionnel',
            self::ENTERPRISE => 'Entreprise',
        };
    }

    /**
     * Get the monthly price for the plan
     */
    public function price(): int
    {
        return match($this) {
            self::FREE => 0,
            self::STARTER => 2900, // 29€
            self::PROFESSIONAL => 7900, // 79€
            self::ENTERPRISE => 19900, // 199€
        };
    }

    /**
     * Get formatted price with currency
     */
    public function formattedPrice(): string
    {
        $price = $this->price() / 100;
        return number_format($price, 2, ',', ' ') . ' €';
    }

    /**
     * Check if the plan is free
     */
    public function isFree(): bool
    {
        return $this === self::FREE;
    }

    /**
     * Check if the plan requires payment
     */
    public function requiresPayment(): bool
    {
        return !$this->isFree();
    }

    /**
     * Get max stores allowed for the plan
     */
    public function maxStores(): int
    {
        return match($this) {
            self::FREE => 1,
            self::STARTER => 3,
            self::PROFESSIONAL => 10,
            self::ENTERPRISE => 999,
        };
    }

    /**
     * Get max users allowed for the plan
     */
    public function maxUsers(): int
    {
        return match($this) {
            self::FREE => 2,
            self::STARTER => 5,
            self::PROFESSIONAL => 20,
            self::ENTERPRISE => 999,
        };
    }

    /**
     * Get max products allowed for the plan
     */
    public function maxProducts(): int
    {
        return match($this) {
            self::FREE => 100,
            self::STARTER => 1000,
            self::PROFESSIONAL => 10000,
            self::ENTERPRISE => 999999,
        };
    }

    /**
     * Get all plan features
     */
    public function features(): array
    {
        return match($this) {
            self::FREE => [
                '1 point de vente',
                '2 utilisateurs',
                '100 produits maximum',
                'Support email',
                'Fonctionnalités de base',
            ],
            self::STARTER => [
                '3 points de vente',
                '5 utilisateurs',
                '1 000 produits maximum',
                'Support prioritaire',
                'Rapports avancés',
                'Gestion multi-magasins',
            ],
            self::PROFESSIONAL => [
                '10 points de vente',
                '20 utilisateurs',
                '10 000 produits maximum',
                'Support 24/7',
                'Rapports personnalisés',
                'API complète',
                'Intégrations avancées',
            ],
            self::ENTERPRISE => [
                'Points de vente illimités',
                'Utilisateurs illimités',
                'Produits illimités',
                'Support dédié',
                'Personnalisation complète',
                'API sans limite',
                'Formation sur mesure',
                'SLA garanti',
            ],
        };
    }

    /**
     * Get all plans as array
     */
    public static function toArray(): array
    {
        return array_map(fn($case) => [
            'value' => $case->value,
            'label' => $case->label(),
            'price' => $case->price(),
            'formatted_price' => $case->formattedPrice(),
            'max_stores' => $case->maxStores(),
            'max_users' => $case->maxUsers(),
            'max_products' => $case->maxProducts(),
            'features' => $case->features(),
            'is_free' => $case->isFree(),
        ], self::cases());
    }
}
