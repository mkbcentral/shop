<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Shwary Mobile Money Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration pour l'intégration de Shwary Mobile Money
    | Documentation: https://api.shwary.com
    |
    */

    // Identifiant du marchand (UUID)
    'merchant_id' => env('SHWARY_MERCHANT_ID', ''),

    // Clé secrète du marchand
    'merchant_key' => env('SHWARY_MERCHANT_KEY', ''),

    // URL de base de l'API
    'base_url' => env('SHWARY_BASE_URL', 'https://api.shwary.com/api/v1'),

    // Mode sandbox (test)
    'sandbox' => env('SHWARY_SANDBOX', true),

    // URL de callback pour les notifications de paiement
    'callback_url' => env('SHWARY_CALLBACK_URL', null),

    // Timeout des requêtes HTTP (en secondes)
    'timeout' => env('SHWARY_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Countries Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration des pays supportés avec leurs codes et devises
    |
    */
    'countries' => [
        'CD' => [
            'name' => 'RDC (Congo)',
            'code' => 'CD',
            'phone_prefix' => '+243',
            'currency' => 'CDF',
            'min_amount' => 100,
            'operators' => ['Airtel', 'Orange', 'Vodacom', 'Africell'],
        ],
        'KE' => [
            'name' => 'Kenya',
            'code' => 'KE',
            'phone_prefix' => '+254',
            'currency' => 'KES',
            'min_amount' => 100,
            'operators' => ['M-Pesa', 'Airtel'],
        ],
        'UG' => [
            'name' => 'Uganda',
            'code' => 'UG',
            'phone_prefix' => '+256',
            'currency' => 'UGX',
            'min_amount' => 100,
            'operators' => ['MTN', 'Airtel'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Country
    |--------------------------------------------------------------------------
    |
    | Pays par défaut pour les paiements
    |
    */
    'default_country' => env('SHWARY_DEFAULT_COUNTRY', 'CD'),
];
