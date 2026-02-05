<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'max_stores',
        'max_users',
        'max_products',
        'features',
        'technical_features',
        'is_popular',
        'color',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'max_stores' => 'integer',
            'max_users' => 'integer',
            'max_products' => 'integer',
            'features' => 'array',
            'technical_features' => 'array',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Scope to get only active plans
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Check if the plan is free
     */
    public function isFree(): bool
    {
        return $this->price === 0;
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', ' ');
    }

    /**
     * Check if plan requires payment
     */
    public function requiresPayment(): bool
    {
        return $this->price > 0;
    }

    /**
     * Check if plan has a specific technical feature
     */
    public function hasFeature(string $feature): bool
    {
        $features = $this->technical_features ?? [];
        return in_array($feature, $features);
    }

    /**
     * Get all available technical features with labels
     */
    public static function getAvailableFeatures(): array
    {
        return [
            // Fonctionnalités de base
            'basic_pos' => [
                'key' => 'basic_pos',
                'label' => 'Point de vente',
                'description' => 'Fonctionnalités de base du point de vente',
                'category' => 'core',
            ],
            'basic_inventory' => [
                'key' => 'basic_inventory',
                'label' => 'Gestion de stock de base',
                'description' => 'Suivi des stocks et alertes de rupture',
                'category' => 'core',
            ],

            // Modules optionnels
            'module_clients' => [
                'key' => 'module_clients',
                'label' => 'Module Clients',
                'description' => 'Gestion des clients et historique d\'achats',
                'category' => 'modules',
            ],
            'module_suppliers' => [
                'key' => 'module_suppliers',
                'label' => 'Module Fournisseurs',
                'description' => 'Gestion des fournisseurs et commandes',
                'category' => 'modules',
            ],
            'module_purchases' => [
                'key' => 'module_purchases',
                'label' => 'Module Achats',
                'description' => 'Gestion des achats et approvisionnements',
                'category' => 'modules',
            ],
            'module_invoices' => [
                'key' => 'module_invoices',
                'label' => 'Module Factures',
                'description' => 'Création et gestion des factures',
                'category' => 'modules',
            ],
            'module_stock' => [
                'key' => 'module_stock',
                'label' => 'Module Stock',
                'description' => 'Gestion avancée des stocks et inventaires',
                'category' => 'modules',
            ],
            'module_transfers' => [
                'key' => 'module_transfers',
                'label' => 'Module Transferts',
                'description' => 'Transferts de produits entre magasins',
                'category' => 'modules',
            ],
            'module_proformas' => [
                'key' => 'module_proformas',
                'label' => 'Module Proformas',
                'description' => 'Création et gestion des factures proforma',
                'category' => 'modules',
            ],

            // Rapports
            'basic_reports' => [
                'key' => 'basic_reports',
                'label' => 'Rapports de base',
                'description' => 'Rapports de ventes et de stock simples',
                'category' => 'reports',
            ],
            'advanced_reports' => [
                'key' => 'advanced_reports',
                'label' => 'Rapports avancés',
                'description' => 'Rapports détaillés avec graphiques et analyses',
                'category' => 'reports',
            ],
            'custom_reports' => [
                'key' => 'custom_reports',
                'label' => 'Rapports personnalisés',
                'description' => 'Création de rapports sur mesure',
                'category' => 'reports',
            ],

            // Magasins
            'multi_store' => [
                'key' => 'multi_store',
                'label' => 'Multi-magasins',
                'description' => 'Gestion de plusieurs magasins',
                'category' => 'stores',
            ],

            // Exports
            'export_excel' => [
                'key' => 'export_excel',
                'label' => 'Export Excel',
                'description' => 'Exportation des données au format Excel',
                'category' => 'export',
            ],
            'export_pdf' => [
                'key' => 'export_pdf',
                'label' => 'Export PDF',
                'description' => 'Exportation des rapports au format PDF',
                'category' => 'export',
            ],

            // Intégrations
            'api_access' => [
                'key' => 'api_access',
                'label' => 'Accès API',
                'description' => 'Accès à l\'API REST pour intégrations',
                'category' => 'integration',
            ],
            'integrations' => [
                'key' => 'integrations',
                'label' => 'Intégrations tierces',
                'description' => 'Connexion avec des services externes',
                'category' => 'integration',
            ],

            // Limites
            'unlimited' => [
                'key' => 'unlimited',
                'label' => 'Ressources illimitées',
                'description' => 'Pas de limites sur les ressources',
                'category' => 'limits',
            ],

            // Support
            'dedicated_support' => [
                'key' => 'dedicated_support',
                'label' => 'Support dédié',
                'description' => 'Support client prioritaire et dédié',
                'category' => 'support',
            ],

            // Entreprise
            'custom_development' => [
                'key' => 'custom_development',
                'label' => 'Développement sur mesure',
                'description' => 'Fonctionnalités personnalisées sur demande',
                'category' => 'enterprise',
            ],
            'sla' => [
                'key' => 'sla',
                'label' => 'SLA garanti',
                'description' => 'Accord de niveau de service garanti',
                'category' => 'enterprise',
            ],
        ];
    }

    /**
     * Get feature categories
     */
    public static function getFeatureCategories(): array
    {
        return [
            'core' => 'Fonctionnalités de base',
            'modules' => 'Modules',
            'reports' => 'Rapports',
            'stores' => 'Magasins',
            'export' => 'Exports',
            'integration' => 'Intégrations',
            'limits' => 'Limites',
            'support' => 'Support',
            'enterprise' => 'Entreprise',
        ];
    }
}
