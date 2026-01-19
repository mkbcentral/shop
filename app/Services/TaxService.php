<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Organization;
use App\Models\OrganizationTax;
use Illuminate\Support\Collection;

/**
 * Service de gestion des taxes pour le POS
 *
 * Permet de calculer les taxes sur les ventes en fonction de l'organisation
 */
class TaxService
{
    /**
     * Récupérer toutes les taxes actives d'une organisation
     */
    public function getActiveTaxes(Organization $organization): Collection
    {
        return $organization->taxes()
            ->active()
            ->validAt()
            ->ordered()
            ->get();
    }

    /**
     * Récupérer la taxe par défaut d'une organisation
     */
    public function getDefaultTax(Organization $organization): ?OrganizationTax
    {
        return $organization->taxes()
            ->active()
            ->validAt()
            ->where('is_default', true)
            ->first();
    }

    /**
     * Récupérer les taxes applicables à un produit
     */
    public function getTaxesForProduct(
        Organization $organization,
        ?int $productId = null,
        ?int $categoryId = null
    ): Collection {
        return $this->getActiveTaxes($organization)->filter(function ($tax) use ($productId, $categoryId) {
            return $tax->appliesToProduct($productId, $categoryId);
        });
    }

    /**
     * Calculer les taxes pour un montant donné
     *
     * @return array{
     *     subtotal: float,
     *     taxes: array<array{tax_id: int, tax_name: string, tax_code: string, rate: float, amount: float}>,
     *     total_tax: float,
     *     total: float
     * }
     */
    public function calculateTaxes(
        Organization $organization,
        float $subtotal,
        ?int $productId = null,
        ?int $categoryId = null,
        ?array $taxIds = null
    ): array {
        $taxes = [];
        $totalTax = 0;
        $previousTaxes = 0;

        // Récupérer les taxes à appliquer
        if ($taxIds !== null) {
            // Utiliser les taxes spécifiées
            $applicableTaxes = $organization->taxes()
                ->whereIn('id', $taxIds)
                ->active()
                ->validAt()
                ->ordered()
                ->get();
        } else {
            // Utiliser les taxes applicables au produit
            $applicableTaxes = $this->getTaxesForProduct($organization, $productId, $categoryId);
        }

        // Calculer chaque taxe
        foreach ($applicableTaxes as $tax) {
            $taxAmount = $tax->calculateTax($subtotal, $previousTaxes);

            $taxes[] = [
                'tax_id' => $tax->id,
                'tax_name' => $tax->name,
                'tax_code' => $tax->code,
                'rate' => (float) $tax->rate,
                'type' => $tax->type,
                'is_compound' => $tax->is_compound,
                'amount' => $taxAmount,
            ];

            $totalTax += $taxAmount;
            $previousTaxes += $taxAmount;
        }

        return [
            'subtotal' => round($subtotal, 2),
            'taxes' => $taxes,
            'total_tax' => round($totalTax, 2),
            'total' => round($subtotal + $totalTax, 2),
        ];
    }

    /**
     * Calculer les taxes pour plusieurs lignes de vente
     *
     * @param array<array{amount: float, product_id?: int, category_id?: int, tax_ids?: array}> $lines
     * @return array{
     *     subtotal: float,
     *     lines: array,
     *     taxes_summary: array,
     *     total_tax: float,
     *     total: float
     * }
     */
    public function calculateTaxesForLines(Organization $organization, array $lines): array
    {
        $subtotal = 0;
        $processedLines = [];
        $taxesSummary = [];
        $totalTax = 0;

        foreach ($lines as $line) {
            $amount = $line['amount'] ?? 0;
            $productId = $line['product_id'] ?? null;
            $categoryId = $line['category_id'] ?? null;
            $taxIds = $line['tax_ids'] ?? null;

            $lineCalculation = $this->calculateTaxes(
                $organization,
                $amount,
                $productId,
                $categoryId,
                $taxIds
            );

            $subtotal += $lineCalculation['subtotal'];
            $totalTax += $lineCalculation['total_tax'];

            // Agréger les taxes pour le résumé
            foreach ($lineCalculation['taxes'] as $tax) {
                $taxId = $tax['tax_id'];
                if (!isset($taxesSummary[$taxId])) {
                    $taxesSummary[$taxId] = [
                        'tax_id' => $tax['tax_id'],
                        'tax_name' => $tax['tax_name'],
                        'tax_code' => $tax['tax_code'],
                        'rate' => $tax['rate'],
                        'type' => $tax['type'],
                        'amount' => 0,
                    ];
                }
                $taxesSummary[$taxId]['amount'] += $tax['amount'];
            }

            $processedLines[] = array_merge($line, [
                'tax_calculation' => $lineCalculation,
            ]);
        }

        // Arrondir les montants du résumé
        foreach ($taxesSummary as &$tax) {
            $tax['amount'] = round($tax['amount'], 2);
        }

        return [
            'subtotal' => round($subtotal, 2),
            'lines' => $processedLines,
            'taxes_summary' => array_values($taxesSummary),
            'total_tax' => round($totalTax, 2),
            'total' => round($subtotal + $totalTax, 2),
        ];
    }

    /**
     * Extraire le montant HT d'un prix TTC
     */
    public function extractPriceWithoutTax(
        Organization $organization,
        float $priceWithTax,
        ?int $taxId = null
    ): array {
        $tax = $taxId
            ? $organization->taxes()->find($taxId)
            : $this->getDefaultTax($organization);

        if (!$tax || !$tax->is_included_in_price) {
            return [
                'price_without_tax' => $priceWithTax,
                'price_with_tax' => $priceWithTax,
                'tax_amount' => 0,
                'tax' => $tax,
            ];
        }

        $priceWithoutTax = $tax->extractPriceWithoutTax($priceWithTax);
        $taxAmount = $priceWithTax - $priceWithoutTax;

        return [
            'price_without_tax' => $priceWithoutTax,
            'price_with_tax' => $priceWithTax,
            'tax_amount' => round($taxAmount, 2),
            'tax' => $tax,
        ];
    }

    /**
     * Créer une taxe pour une organisation
     */
    public function createTax(Organization $organization, array $data): OrganizationTax
    {
        $data['organization_id'] = $organization->id;

        // Si c'est la première taxe ou si is_default est true
        if ($data['is_default'] ?? false) {
            // Désactiver les autres taxes par défaut
            $organization->taxes()->update(['is_default' => false]);
        } elseif ($organization->taxes()->count() === 0) {
            // Première taxe = taxe par défaut
            $data['is_default'] = true;
        }

        return OrganizationTax::create($data);
    }

    /**
     * Mettre à jour une taxe
     */
    public function updateTax(OrganizationTax $tax, array $data): OrganizationTax
    {
        // Si on définit comme taxe par défaut
        if ($data['is_default'] ?? false) {
            OrganizationTax::where('organization_id', $tax->organization_id)
                ->where('id', '!=', $tax->id)
                ->update(['is_default' => false]);
        }

        $tax->update($data);
        return $tax->fresh();
    }

    /**
     * Supprimer une taxe (soft delete)
     */
    public function deleteTax(OrganizationTax $tax): bool
    {
        // Si c'était la taxe par défaut, assigner une autre taxe par défaut
        if ($tax->is_default) {
            $nextDefault = OrganizationTax::where('organization_id', $tax->organization_id)
                ->where('id', '!=', $tax->id)
                ->active()
                ->first();

            if ($nextDefault) {
                $nextDefault->update(['is_default' => true]);
            }
        }

        return $tax->delete();
    }

    /**
     * Formater les taxes pour l'API
     */
    public function formatTaxesForApi(Collection $taxes): array
    {
        return $taxes->map(fn($tax) => [
            'id' => $tax->id,
            'name' => $tax->name,
            'code' => $tax->code,
            'description' => $tax->description,
            'rate' => (float) $tax->rate,
            'formatted_rate' => $tax->formatted_rate,
            'type' => $tax->type,
            'is_compound' => $tax->is_compound,
            'is_included_in_price' => $tax->is_included_in_price,
            'is_default' => $tax->is_default,
            'is_active' => $tax->is_active,
            'authority' => $tax->authority,
        ])->toArray();
    }
}
