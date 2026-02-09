<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * Fonctionnalité disponible dans le système
 *
 * Gère le catalogue des fonctionnalités qui peuvent être activées
 * ou désactivées pour chaque plan d'abonnement.
 *
 * @property int $id
 * @property string $key Identifiant technique unique (ex: module_clients)
 * @property string $label Nom affiché (ex: Module Clients)
 * @property string|null $description Description de la fonctionnalité
 * @property string $category Catégorie: core, modules, reports, stores, export, integrations
 * @property string|null $icon Icône optionnelle
 * @property bool $is_active Fonctionnalité disponible dans le système
 * @property int $sort_order Ordre d'affichage
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class AvailableFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'label',
        'description',
        'category',
        'icon',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Catégories disponibles avec leurs labels
     */
    public const CATEGORIES = [
        'core' => 'Fonctionnalités de base',
        'modules' => 'Modules',
        'reports' => 'Rapports',
        'stores' => 'Magasins',
        'export' => 'Exports',
        'integrations' => 'Intégrations',
        'limits' => 'Limites',
        'support' => 'Support',
        'enterprise' => 'Entreprise',
    ];

    /**
     * Scope pour les fonctionnalités actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour filtrer par catégorie
     */
    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope pour trier par ordre
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('label');
    }

    /**
     * Récupérer toutes les fonctionnalités actives formatées pour l'affichage
     *
     * @return array<string, array>
     */
    public static function getAvailableFeaturesFormatted(): array
    {
        return static::active()
            ->ordered()
            ->get()
            ->keyBy('key')
            ->map(fn ($feature) => [
                'key' => $feature->key,
                'label' => $feature->label,
                'description' => $feature->description,
                'category' => $feature->category,
                'icon' => $feature->icon,
            ])
            ->toArray();
    }

    /**
     * Récupérer les fonctionnalités groupées par catégorie
     *
     * @return Collection
     */
    public static function getGroupedByCategory(): Collection
    {
        return static::active()
            ->ordered()
            ->get()
            ->groupBy('category');
    }

    /**
     * Récupérer les catégories avec leurs labels
     *
     * @return array<string, string>
     */
    public static function getCategoryLabels(): array
    {
        return self::CATEGORIES;
    }

    /**
     * Vérifier si une clé de fonctionnalité existe et est active
     */
    public static function isFeatureAvailable(string $key): bool
    {
        return static::where('key', $key)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Créer une fonctionnalité si elle n'existe pas
     */
    public static function findOrCreateByKey(string $key, array $attributes = []): self
    {
        return static::firstOrCreate(
            ['key' => $key],
            array_merge([
                'label' => $attributes['label'] ?? ucwords(str_replace('_', ' ', $key)),
                'description' => $attributes['description'] ?? null,
                'category' => $attributes['category'] ?? 'modules',
                'icon' => $attributes['icon'] ?? null,
                'is_active' => true,
                'sort_order' => $attributes['sort_order'] ?? 0,
            ], $attributes)
        );
    }
}
