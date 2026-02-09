<?php

namespace Database\Seeders;

use App\Models\AvailableFeature;
use Illuminate\Database\Seeder;

class AvailableFeaturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            // Fonctionnalités de base
            [
                'key' => 'basic_pos',
                'label' => 'Point de vente',
                'description' => 'Fonctionnalités de base du point de vente',
                'category' => 'core',
                'sort_order' => 1,
            ],
            [
                'key' => 'basic_inventory',
                'label' => 'Gestion de stock de base',
                'description' => 'Suivi des stocks et alertes de rupture',
                'category' => 'core',
                'sort_order' => 2,
            ],

            // Modules optionnels
            [
                'key' => 'module_clients',
                'label' => 'Module Clients',
                'description' => 'Gestion des clients et historique d\'achats',
                'category' => 'modules',
                'sort_order' => 10,
            ],
            [
                'key' => 'module_suppliers',
                'label' => 'Module Fournisseurs',
                'description' => 'Gestion des fournisseurs et commandes',
                'category' => 'modules',
                'sort_order' => 11,
            ],
            [
                'key' => 'module_purchases',
                'label' => 'Module Achats',
                'description' => 'Gestion des achats et approvisionnements',
                'category' => 'modules',
                'sort_order' => 12,
            ],
            [
                'key' => 'module_invoices',
                'label' => 'Module Factures',
                'description' => 'Création et gestion des factures',
                'category' => 'modules',
                'sort_order' => 13,
            ],
            [
                'key' => 'module_stock',
                'label' => 'Module Stock',
                'description' => 'Gestion avancée des stocks et inventaires',
                'category' => 'modules',
                'sort_order' => 14,
            ],
            [
                'key' => 'module_transfers',
                'label' => 'Module Transferts',
                'description' => 'Transferts de produits entre magasins',
                'category' => 'modules',
                'sort_order' => 15,
            ],
            [
                'key' => 'module_proformas',
                'label' => 'Module Proformas',
                'description' => 'Création et gestion des factures proforma',
                'category' => 'modules',
                'sort_order' => 16,
            ],
            [
                'key' => 'module_sales',
                'label' => 'Module Ventes',
                'description' => 'Gestion des ventes et transactions',
                'category' => 'modules',
                'sort_order' => 17,
            ],

            // Rapports
            [
                'key' => 'basic_reports',
                'label' => 'Rapports de base',
                'description' => 'Rapports de ventes et de stock simples',
                'category' => 'reports',
                'sort_order' => 20,
            ],
            [
                'key' => 'advanced_reports',
                'label' => 'Rapports avancés',
                'description' => 'Rapports détaillés avec graphiques et analyses',
                'category' => 'reports',
                'sort_order' => 21,
            ],
            [
                'key' => 'custom_reports',
                'label' => 'Rapports personnalisés',
                'description' => 'Création de rapports sur mesure',
                'category' => 'reports',
                'sort_order' => 22,
            ],

            // Magasins
            [
                'key' => 'multi_store',
                'label' => 'Multi-magasins',
                'description' => 'Gestion de plusieurs magasins',
                'category' => 'stores',
                'sort_order' => 30,
            ],

            // Exports
            [
                'key' => 'export_excel',
                'label' => 'Export Excel',
                'description' => 'Exportation des données au format Excel',
                'category' => 'export',
                'sort_order' => 40,
            ],
            [
                'key' => 'export_pdf',
                'label' => 'Export PDF',
                'description' => 'Exportation des rapports au format PDF',
                'category' => 'export',
                'sort_order' => 41,
            ],

            // Intégrations
            [
                'key' => 'api_access',
                'label' => 'Accès API',
                'description' => 'Accès à l\'API REST pour intégrations',
                'category' => 'integrations',
                'sort_order' => 50,
            ],
            [
                'key' => 'integrations',
                'label' => 'Intégrations tierces',
                'description' => 'Connexion avec des services externes',
                'category' => 'integrations',
                'sort_order' => 51,
            ],

            // Limites
            [
                'key' => 'unlimited',
                'label' => 'Ressources illimitées',
                'description' => 'Pas de limites sur les ressources',
                'category' => 'limits',
                'sort_order' => 60,
            ],

            // Support
            [
                'key' => 'dedicated_support',
                'label' => 'Support dédié',
                'description' => 'Support client prioritaire et dédié',
                'category' => 'support',
                'sort_order' => 70,
            ],

            // Entreprise
            [
                'key' => 'custom_development',
                'label' => 'Développement sur mesure',
                'description' => 'Fonctionnalités personnalisées sur demande',
                'category' => 'enterprise',
                'sort_order' => 80,
            ],
            [
                'key' => 'sla',
                'label' => 'SLA garanti',
                'description' => 'Accord de niveau de service garanti',
                'category' => 'enterprise',
                'sort_order' => 81,
            ],
        ];

        foreach ($features as $feature) {
            AvailableFeature::updateOrCreate(
                ['key' => $feature['key']],
                array_merge($feature, ['is_active' => true])
            );
        }

        $this->command->info('✅ ' . count($features) . ' fonctionnalités créées/mises à jour.');
    }
}
