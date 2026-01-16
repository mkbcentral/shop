<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'icon',
        'route',
        'url',
        'parent_id',
        'order',
        'section',
        'is_active',
        'badge_type',
        'badge_color',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Relation vers le parent
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    /**
     * Relation vers les enfants
     */
    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    /**
     * Relation vers les rôles qui ont accès à ce menu
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'menu_item_role')
            ->withTimestamps();
    }

    /**
     * Scope pour les menus actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les menus racines (sans parent)
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope pour une section spécifique
     */
    public function scopeInSection($query, string $section)
    {
        return $query->where('section', $section);
    }

    /**
     * Vérifie si un utilisateur a accès à ce menu
     */
    public function isAccessibleBy(User $user): bool
    {
        // Super-admin a accès à tout
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Vérifier si un des rôles de l'utilisateur a accès
        $userRoleIds = $user->roles->pluck('id')->toArray();

        return $this->roles()->whereIn('roles.id', $userRoleIds)->exists();
    }

    /**
     * Récupère tous les menus accessibles pour un utilisateur (avec cache)
     */
    public static function getAccessibleMenusFor(User $user): Collection
    {
        $cacheKey = 'user_menus_' . $user->id;

        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($user) {
            // Super-admin a accès à tout
            if ($user->hasRole('super-admin')) {
                return static::active()
                    ->root()
                    ->with(['children' => fn($q) => $q->active()->orderBy('order')])
                    ->orderBy('section')
                    ->orderBy('order')
                    ->get();
            }

            $userRoleIds = $user->roles->pluck('id')->toArray();

            // Récupérer les menus parents accessibles
            $accessibleMenuIds = \DB::table('menu_item_role')
                ->whereIn('role_id', $userRoleIds)
                ->pluck('menu_item_id')
                ->toArray();

            return static::active()
                ->root()
                ->where(function ($query) use ($accessibleMenuIds) {
                    $query->whereIn('id', $accessibleMenuIds)
                        ->orWhereHas('children', fn($q) => $q->whereIn('id', $accessibleMenuIds));
                })
                ->with(['children' => function ($query) use ($accessibleMenuIds) {
                    $query->active()
                        ->whereIn('id', $accessibleMenuIds)
                        ->orderBy('order');
                }])
                ->orderBy('section')
                ->orderBy('order')
                ->get();
        });
    }

    /**
     * Récupère les menus groupés par section pour un utilisateur
     */
    public static function getMenusBySection(User $user): Collection
    {
        $menus = static::getAccessibleMenusFor($user);

        return $menus->groupBy('section');
    }

    /**
     * Invalide le cache des menus pour un utilisateur
     */
    public static function clearCacheFor(User $user): void
    {
        Cache::forget('user_menus_' . $user->id);
    }

    /**
     * Invalide le cache des menus pour tous les utilisateurs d'un rôle
     */
    public static function clearCacheForRole(Role $role): void
    {
        $role->users->each(function ($user) {
            static::clearCacheFor($user);
        });
    }

    /**
     * Génère l'URL du menu
     */
    public function getUrl(): ?string
    {
        if ($this->route) {
            try {
                return route($this->route);
            } catch (\Exception $e) {
                return null;
            }
        }

        return $this->url;
    }

    /**
     * Vérifie si le menu est actif (route courante)
     */
    public function isCurrentRoute(): bool
    {
        if (!$this->route) {
            return false;
        }

        return request()->routeIs($this->route) || request()->routeIs($this->route . '.*');
    }

    /**
     * Vérifie si un enfant est actif
     */
    public function hasActiveChild(): bool
    {
        return $this->children->contains(fn($child) => $child->isCurrentRoute());
    }
}
