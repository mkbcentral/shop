<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\OrganizationTax;
use Illuminate\Database\Seeder;

class OrganizationTaxSeeder extends Seeder
{
    /**
     * Taxes par défaut selon le type/taille d'organisation
     */
    protected array $defaultTaxes = [
        // TVA standard (16%)
        [
            'name' => 'TVA',
            'code' => 'TVA',
            'description' => 'Taxe sur la Valeur Ajoutée - Taux normal',
            'rate' => 16.0000,
            'type' => 'percentage',
            'is_default' => true,
            'priority' => 1,
            'authority' => 'Direction Générale des Impôts',
        ],
    ];

    /**
     * Templates de taxes disponibles
     */
    protected array $taxTemplates = [
        'tva_16' => [
            'name' => 'TVA 16%',
            'code' => 'TVA16',
            'description' => 'Taxe sur la Valeur Ajoutée - Taux normal (16%)',
            'rate' => 16.0000,
            'type' => 'percentage',
            'authority' => 'Direction Générale des Impôts',
        ],
        'tva_8' => [
            'name' => 'TVA 8%',
            'code' => 'TVA8',
            'description' => 'Taxe sur la Valeur Ajoutée - Taux réduit (8%)',
            'rate' => 8.0000,
            'type' => 'percentage',
            'authority' => 'Direction Générale des Impôts',
        ],
        'tva_0' => [
            'name' => 'TVA 0%',
            'code' => 'TVA0',
            'description' => 'Exonération de TVA',
            'rate' => 0.0000,
            'type' => 'percentage',
            'authority' => 'Direction Générale des Impôts',
        ],
        'taxe_municipale' => [
            'name' => 'Taxe Municipale',
            'code' => 'TM',
            'description' => 'Taxe municipale sur les ventes',
            'rate' => 2.0000,
            'type' => 'percentage',
            'authority' => 'Mairie',
        ],
        'taxe_service' => [
            'name' => 'Taxe de Service',
            'code' => 'TS',
            'description' => 'Taxe sur les services',
            'rate' => 10.0000,
            'type' => 'percentage',
            'is_compound' => true,
            'priority' => 2,
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Création des taxes pour les organisations...');

        // Récupérer toutes les organisations actives
        $organizations = Organization::where('is_active', true)->get();

        foreach ($organizations as $organization) {
            $this->createDefaultTaxesForOrganization($organization);
        }

        $this->command->info('Taxes créées avec succès!');
    }

    /**
     * Créer les taxes par défaut pour une organisation
     */
    public function createDefaultTaxesForOrganization(Organization $organization): void
    {
        // Vérifier si l'organisation a déjà des taxes
        if ($organization->taxes()->exists()) {
            $this->command->warn("  - {$organization->name}: taxes existantes, ignoré");
            return;
        }

        // Déterminer le taux de TVA selon le type/plan d'organisation
        $vatRate = $this->determineVatRate($organization);

        // Créer la TVA par défaut
        OrganizationTax::create([
            'organization_id' => $organization->id,
            'name' => 'TVA',
            'code' => 'TVA',
            'description' => "Taxe sur la Valeur Ajoutée ({$vatRate}%)",
            'rate' => $vatRate,
            'type' => 'percentage',
            'is_default' => true,
            'is_active' => true,
            'priority' => 1,
            'authority' => 'Direction Générale des Impôts',
            'apply_to_all_products' => true,
        ]);

        $this->command->info("  - {$organization->name}: TVA à {$vatRate}% créée");
    }

    /**
     * Déterminer le taux de TVA selon l'organisation
     */
    protected function determineVatRate(Organization $organization): float
    {
        // Logique basée sur le plan d'abonnement ou la taille
        $plan = $organization->subscription_plan?->value ?? 'free';

        return match ($plan) {
            'free', 'starter' => 0.0000,      // Petites entreprises: exonérées
            'basic' => 8.0000,                 // PME: taux réduit
            'professional', 'enterprise' => 16.0000, // Grandes entreprises: taux normal
            default => 16.0000,
        };
    }

    /**
     * Ajouter une taxe à partir d'un template
     */
    public function addTaxFromTemplate(Organization $organization, string $templateKey, array $overrides = []): ?OrganizationTax
    {
        if (!isset($this->taxTemplates[$templateKey])) {
            return null;
        }

        $data = array_merge(
            $this->taxTemplates[$templateKey],
            [
                'organization_id' => $organization->id,
                'is_active' => true,
                'apply_to_all_products' => true,
            ],
            $overrides
        );

        return OrganizationTax::create($data);
    }
}
