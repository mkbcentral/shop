<?php

use App\Enums\BusinessActivityType;
use App\Models\Organization;

if (!function_exists('current_organization')) {
    /**
     * Get the current organization
     */
    function current_organization(): ?Organization
    {
        if (app()->bound('current_organization')) {
            return app('current_organization');
        }

        $user = auth()->user();
        if (!$user) {
            return null;
        }

        return $user->currentOrganization ?? $user->defaultOrganization ?? null;
    }
}

if (!function_exists('is_service_organization')) {
    /**
     * Check if the current organization is a service-only organization
     */
    function is_service_organization(?Organization $organization = null): bool
    {
        $org = $organization ?? current_organization();

        if (!$org) {
            return false;
        }

        $activity = $org->business_activity;

        if ($activity instanceof BusinessActivityType) {
            return $activity === BusinessActivityType::SERVICES;
        }

        return $activity === 'services';
    }
}

if (!function_exists('can_sell_products')) {
    /**
     * Check if the current organization can sell physical products
     */
    function can_sell_products(?Organization $organization = null): bool
    {
        $org = $organization ?? current_organization();

        if (!$org) {
            return true; // Default to true for backward compatibility
        }

        if (method_exists($org, 'canSellPhysicalProducts')) {
            return $org->canSellPhysicalProducts();
        }

        $activity = $org->business_activity;

        if ($activity instanceof BusinessActivityType) {
            return $activity->canSellPhysicalProducts();
        }

        return in_array($activity, ['retail', 'food', 'mixed']);
    }
}

if (!function_exists('can_sell_services')) {
    /**
     * Check if the current organization can sell services
     */
    function can_sell_services(?Organization $organization = null): bool
    {
        $org = $organization ?? current_organization();

        if (!$org) {
            return true; // Default to true for backward compatibility
        }

        if (method_exists($org, 'canSellServices')) {
            return $org->canSellServices();
        }

        $activity = $org->business_activity;

        if ($activity instanceof BusinessActivityType) {
            return $activity->canSellServices();
        }

        return in_array($activity, ['services', 'mixed']);
    }
}

if (!function_exists('product_label')) {
    /**
     * Get the appropriate label for "Produit" based on organization type
     *
     * @param bool $plural Whether to return plural form
     * @param bool $capitalize Whether to capitalize the first letter
     * @return string
     */
    function product_label(bool $plural = false, bool $capitalize = true, ?Organization $organization = null): string
    {
        $isService = is_service_organization($organization);

        if ($isService) {
            $label = $plural ? 'services' : 'service';
        } else {
            $label = $plural ? 'produits' : 'produit';
        }

        return $capitalize ? ucfirst($label) : $label;
    }
}

if (!function_exists('products_label')) {
    /**
     * Shortcut for product_label(true) - returns plural form
     */
    function products_label(bool $capitalize = true, ?Organization $organization = null): string
    {
        return product_label(true, $capitalize, $organization);
    }
}

if (!function_exists('product_type_label')) {
    /**
     * Get the appropriate label for "Type de produit" based on organization type
     *
     * @param bool $plural Whether to return plural form
     * @return string
     */
    function product_type_label(bool $plural = false, ?Organization $organization = null): string
    {
        $isService = is_service_organization($organization);

        if ($isService) {
            return $plural ? 'Types de services' : 'Type de service';
        }

        return $plural ? 'Types de produits' : 'Type de produit';
    }
}

if (!function_exists('inventory_section_label')) {
    /**
     * Get the appropriate label for the inventory section based on organization type
     */
    function inventory_section_label(?Organization $organization = null): string
    {
        $isService = is_service_organization($organization);

        return $isService ? 'Services' : 'Inventaire';
    }
}

if (!function_exists('has_stock_management')) {
    /**
     * Check if stock management is available for the organization.
     *
     * Stock management is NOT available for service-only organizations,
     * regardless of their subscription plan.
     *
     * @param Organization|null $organization
     * @return bool
     */
    function has_stock_management(?Organization $organization = null): bool
    {
        $org = $organization ?? current_organization();

        if (!$org) {
            return true; // Default to true for backward compatibility
        }

        // Service-only organizations don't need stock management
        if (is_service_organization($org)) {
            return false;
        }

        return true;
    }
}

if (!function_exists('needs_inventory_tracking')) {
    /**
     * Check if the organization needs inventory/stock tracking.
     *
     * Service-only organizations don't need inventory tracking since they sell services.
     *
     * @param Organization|null $organization
     * @return bool
     */
    function needs_inventory_tracking(?Organization $organization = null): bool
    {
        return has_stock_management($organization);
    }
}
