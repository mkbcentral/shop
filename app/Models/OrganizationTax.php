<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle OrganizationTax - Taxes rattachées à une organisation
 *
 * Chaque organisation peut avoir plusieurs taxes (TVA, taxes municipales, etc.)
 * avec des taux différents selon la taille ou le type d'organisation.
 */
class OrganizationTax extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'organization_id',
        'name',
        'code',
        'description',
        'rate',
        'type',
        'fixed_amount',
        'is_compound',
        'is_included_in_price',
        'priority',
        'apply_to_all_products',
        'product_categories',
        'excluded_product_ids',
        'min_amount',
        'max_amount',
        'is_active',
        'is_default',
        'valid_from',
        'valid_until',
        'tax_number',
        'authority',
    ];

    protected $casts = [
        'rate' => 'decimal:4',
        'fixed_amount' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'is_compound' => 'boolean',
        'is_included_in_price' => 'boolean',
        'apply_to_all_products' => 'boolean',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'product_categories' => 'array',
        'excluded_product_ids' => 'array',
        'valid_from' => 'date',
        'valid_until' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    /**
     * Organisation propriétaire de la taxe
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope pour les taxes actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour la taxe par défaut
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope pour les taxes valides à une date donnée
     */
    public function scopeValidAt($query, $date = null)
    {
        $date = $date ?? now();

        return $query->where(function ($q) use ($date) {
            $q->whereNull('valid_from')
                ->orWhere('valid_from', '<=', $date);
        })->where(function ($q) use ($date) {
            $q->whereNull('valid_until')
                ->orWhere('valid_until', '>=', $date);
        });
    }

    /**
     * Scope pour ordonner par priorité
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('priority')->orderBy('name');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Obtenir le taux formaté (ex: "16%")
     */
    public function getFormattedRateAttribute(): string
    {
        if ($this->type === 'fixed') {
            return number_format((float) $this->fixed_amount, 2) . ' (fixe)';
        }

        return number_format((float) $this->rate, 2) . '%';
    }

    /**
     * Vérifier si la taxe est actuellement valide
     */
    public function isValid(): bool
    {
        $now = now();

        if ($this->valid_from && $this->valid_from > $now) {
            return false;
        }

        if ($this->valid_until && $this->valid_until < $now) {
            return false;
        }

        return $this->is_active;
    }

    /**
     * Calculer le montant de la taxe pour un montant donné
     */
    public function calculateTax(float $amount, float $previousTaxes = 0): float
    {
        if (!$this->isValid()) {
            return 0;
        }

        // Pour les taxes composées, on calcule sur le montant + taxes précédentes
        $baseAmount = $this->is_compound ? ($amount + $previousTaxes) : $amount;

        if ($this->type === 'fixed') {
            $taxAmount = (float) $this->fixed_amount;
        } else {
            $taxAmount = $baseAmount * ($this->rate / 100);
        }

        // Appliquer le montant maximum si défini
        if ($this->max_amount !== null && $taxAmount > $this->max_amount) {
            $taxAmount = (float) $this->max_amount;
        }

        return round($taxAmount, 2);
    }

    /**
     * Vérifier si la taxe s'applique à un produit donné
     */
    public function appliesToProduct(?int $productId = null, ?int $categoryId = null): bool
    {
        // Si la taxe s'applique à tous les produits
        if ($this->apply_to_all_products) {
            // Vérifier si le produit est exclu
            if ($productId && $this->excluded_product_ids) {
                return !in_array($productId, $this->excluded_product_ids);
            }
            return true;
        }

        // Vérifier si la catégorie est dans la liste des catégories concernées
        if ($categoryId && $this->product_categories) {
            return in_array($categoryId, $this->product_categories);
        }

        return false;
    }

    /**
     * Extraire le montant HT d'un prix TTC (si taxe incluse)
     */
    public function extractPriceWithoutTax(float $priceWithTax): float
    {
        if (!$this->is_included_in_price || $this->type === 'fixed') {
            return $priceWithTax;
        }

        return round($priceWithTax / (1 + ($this->rate / 100)), 2);
    }

    /**
     * Définir comme taxe par défaut (et désactiver les autres par défaut)
     */
    public function setAsDefault(): bool
    {
        // Retirer le statut par défaut des autres taxes de l'organisation
        static::where('organization_id', $this->organization_id)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);

        return $this->update(['is_default' => true]);
    }
}
