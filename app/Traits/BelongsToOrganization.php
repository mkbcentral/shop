<?php

namespace App\Traits;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait pour les modèles qui appartiennent à une organisation (via Store)
 *
 * Ce trait ajoute un scope global pour filtrer automatiquement par organisation
 * et assigne automatiquement l'organization_id lors de la création.
 */
trait BelongsToOrganization
{
    /**
     * Get the current organization ID from various sources
     */
    protected static function getCurrentOrganizationId(): ?int
    {
        // 1. Try from app container
        try {
            $organization = app('current_organization');
            if ($organization) {
                return $organization->id;
            }
        } catch (\Exception $e) {
            // Continue to fallbacks
        }

        // 2. Try from session
        $orgId = session('current_organization_id');
        if ($orgId) {
            return (int) $orgId;
        }

        // 3. Try from authenticated user's current store
        $user = auth()->user();
        if ($user) {
            if ($user->current_store_id && $user->currentStore) {
                return $user->currentStore->organization_id;
            }

            // 4. Try user's default organization
            if ($user->default_organization_id) {
                return $user->default_organization_id;
            }

            // 5. Try user's first organization
            $userOrg = $user->organizations()->first();
            if ($userOrg) {
                return $userOrg->id;
            }
        }

        return null;
    }

    /**
     * Boot the trait.
     */
    protected static function bootBelongsToOrganization(): void
    {
        // Ajouter un scope global pour filtrer par organisation
        static::addGlobalScope('organization', function (Builder $query) {
            $organizationId = static::getCurrentOrganizationId();

            if ($organizationId) {
                // Si le modèle a directement organization_id
                if (in_array('organization_id', (new static)->getFillable())) {
                    $query->where((new static)->getTable() . '.organization_id', $organizationId);
                }
                // Sinon, filtrer via la relation store
                elseif (method_exists(new static, 'store')) {
                    $query->whereHas('store', function ($q) use ($organizationId) {
                        $q->where('organization_id', $organizationId);
                    });
                }
            }
        });

        // Auto-assigner l'organization_id lors de la création
        static::creating(function ($model) {
            if (in_array('organization_id', $model->getFillable())) {
                if (!$model->organization_id) {
                    $organizationId = static::getCurrentOrganizationId();
                    if ($organizationId) {
                        $model->organization_id = $organizationId;
                    }
                }
            }
        });
    }

    /**
     * Relation vers l'organisation.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Scope pour désactiver le filtre d'organisation.
     */
    public function scopeWithoutOrganizationScope(Builder $query): Builder
    {
        return $query->withoutGlobalScope('organization');
    }

    /**
     * Scope pour filtrer par une organisation spécifique.
     */
    public function scopeForOrganization(Builder $query, int $organizationId): Builder
    {
        return $query->withoutGlobalScope('organization')
                     ->where('organization_id', $organizationId);
    }

    /**
     * Scope pour filtrer par les organisations de l'utilisateur.
     */
    public function scopeForUserOrganizations(Builder $query, $user = null): Builder
    {
        $user = $user ?? auth()->user();

        if (!$user) {
            return $query;
        }

        $organizationIds = $user->organizations()->pluck('organizations.id');

        return $query->withoutGlobalScope('organization')
                     ->whereIn('organization_id', $organizationIds);
    }
}
