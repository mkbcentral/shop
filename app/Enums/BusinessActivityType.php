<?php

namespace App\Enums;

/**
 * Types d'activité commerciale pour les organisations.
 * Détermine quels types de produits sont disponibles.
 */
enum BusinessActivityType: string
{
    case RETAIL = 'retail';           // Commerce de détail (vêtements, électronique, etc.)
    case FOOD = 'food';               // Alimentaire (restaurants, épiceries, etc.)
    case SERVICES = 'services';       // Services uniquement (coiffure, esthétique, etc.)
    case MIXED = 'mixed';             // Mixte (produits physiques + services)

    /**
     * Get the display label
     */
    public function label(): string
    {
        return match($this) {
            self::RETAIL => 'Commerce de détail',
            self::FOOD => 'Alimentaire',
            self::SERVICES => 'Services',
            self::MIXED => 'Mixte (Produits & Services)',
        };
    }

    /**
     * Get the description
     */
    public function description(): string
    {
        return match($this) {
            self::RETAIL => 'Vente de produits physiques (vêtements, électronique, accessoires...)',
            self::FOOD => 'Vente de produits alimentaires et boissons',
            self::SERVICES => 'Vente de services uniquement (coiffure, esthétique, photographie...)',
            self::MIXED => 'Vente de produits physiques et de services',
        };
    }

    /**
     * Get the icon for UI display
     */
    public function icon(): string
    {
        return match($this) {
            self::RETAIL => '🛍️',
            self::FOOD => '🍽️',
            self::SERVICES => '💼',
            self::MIXED => '🏪',
        };
    }

    /**
     * Check if this activity type can sell physical products
     */
    public function canSellPhysicalProducts(): bool
    {
        return match($this) {
            self::RETAIL, self::FOOD, self::MIXED => true,
            self::SERVICES => false,
        };
    }

    /**
     * Check if this activity type can sell services
     */
    public function canSellServices(): bool
    {
        return match($this) {
            self::SERVICES, self::MIXED => true,
            self::RETAIL, self::FOOD => false,
        };
    }

    /**
     * Get compatible product type slugs for this activity
     */
    public function getCompatibleProductTypeSlugs(): array
    {
        return match($this) {
            self::RETAIL => ['vetements', 'electronique', 'accessoires', 'general'],
            self::FOOD => ['alimentaire', 'boissons'],
            self::SERVICES => ['coiffure', 'esthetique', 'photographie', 'consultation', 'reparation', 'service'],
            self::MIXED => [], // Mixte peut utiliser tous les types
        };
    }

    /**
     * Get all cases as array for forms
     */
    public static function options(): array
    {
        return collect(self::cases())->map(fn($case) => [
            'value' => $case->value,
            'label' => $case->label(),
            'description' => $case->description(),
            'icon' => $case->icon(),
        ])->toArray();
    }
}
