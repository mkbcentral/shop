<?php

if (!function_exists('current_currency')) {
    /**
     * Get the current organization's currency
     *
     * @return string The currency code (default: 'CDF')
     */
    function current_currency(): string
    {
        try {
            $organization = app('current_organization');
            if ($organization && !empty($organization->currency)) {
                return $organization->currency;
            }
        } catch (\Exception $e) {
            // Fallback silently
        }

        // Try from session
        $orgId = session('current_organization_id');
        if ($orgId) {
            $currency = \Illuminate\Support\Facades\Cache::remember(
                "org_{$orgId}_currency",
                3600,
                function () use ($orgId) {
                    $org = \App\Models\Organization::find($orgId);
                    return $org?->currency;
                }
            );
            if ($currency) {
                return $currency;
            }
        }

        // Try from authenticated user
        $user = auth()->user();
        if ($user) {
            // D'abord essayer defaultOrganization
            if ($user->default_organization_id) {
                $defaultOrg = $user->defaultOrganization;
                if ($defaultOrg && !empty($defaultOrg->currency)) {
                    return $defaultOrg->currency;
                }
            }

            // Sinon, essayer la première organisation active de l'utilisateur
            $userOrg = $user->organizations()
                ->wherePivot('is_active', true)
                ->first();
            if ($userOrg && !empty($userOrg->currency)) {
                return $userOrg->currency;
            }
        }

        // Default fallback
        return config('app.default_currency', 'CDF');
    }
}

if (!function_exists('format_currency')) {
    /**
     * Format a number as currency with the organization's currency
     *
     * @param float|int|null $amount The amount to format
     * @param int $decimals Number of decimal places (ignored for CDF)
     * @param bool $showSymbol Whether to show the currency symbol
     * @return string Formatted currency string
     */
    function format_currency(float|int|null $amount, int $decimals = 0, bool $showSymbol = true): string
    {
        $amount = $amount ?? 0;
        $currency = current_currency();

        // CDF n'utilise pas de décimales
        if ($currency === 'CDF') {
            $decimals = 0;
            $amount = round($amount);
        }

        $formatted = number_format($amount, $decimals, ',', ' ');

        if ($showSymbol) {
            return $formatted . ' ' . $currency;
        }

        return $formatted;
    }
}

if (!function_exists('format_money')) {
    /**
     * Alias for format_currency
     */
    function format_money(float|int|null $amount, int $decimals = 0, bool $showSymbol = true): string
    {
        return format_currency($amount, $decimals, $showSymbol);
    }
}

if (!function_exists('currency_symbol')) {
    /**
     * Get the currency symbol for display
     * Maps common currency codes to their symbols
     */
    function currency_symbol(?string $currency = null): string
    {
        $currency = $currency ?? current_currency();

        $symbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'JPY' => '¥',
            'CNY' => '¥',
            'XOF' => 'FCFA',
            'XAF' => 'FCFA',
            'FCFA' => 'FCFA',
            'CDF' => 'FC',
            'NGN' => '₦',
            'GHS' => 'GH₵',
            'KES' => 'KSh',
            'ZAR' => 'R',
            'MAD' => 'DH',
            'TND' => 'DT',
            'EGP' => 'E£',
        ];

        return $symbols[strtoupper($currency)] ?? $currency;
    }
}
