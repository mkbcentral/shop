<?php

namespace App\Services;

use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class MenuService
{
    /**
     * Durée du cache en secondes (1 heure)
     */
    private const CACHE_TTL = 3600;

    /**
     * Récupère tous les menus accessibles pour un utilisateur, groupés par section
     */
    public function getAccessibleMenusForUser(User $user): Collection
    {
        $cacheKey = "user_menus_{$user->id}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($user) {
            return $this->buildMenuStructure($user);
        });
    }

    /**
     * Récupère les menus d'une section spécifique pour un utilisateur
     */
    public function getMenusBySection(User $user, ?string $section): Collection
    {
        $menus = $this->getAccessibleMenusForUser($user);

        return $menus->get($section ?? 'no_section', collect());
    }

    /**
     * Construit la structure complète des menus
     */
    private function buildMenuStructure(User $user): Collection
    {
        $userRoleIds = $user->roles->pluck('id')->toArray();

        // Récupérer tous les menus racines actifs accessibles par l'utilisateur via ses rôles
        $menuItems = MenuItem::with(['children' => function ($query) use ($userRoleIds) {
            $query->active()
                  ->whereHas('roles', function ($q) use ($userRoleIds) {
                      $q->whereIn('roles.id', $userRoleIds);
                  })
                  ->orderBy('order');
        }])
        ->active()
        ->whereNull('parent_id')
        ->whereHas('roles', function ($query) use ($userRoleIds) {
            $query->whereIn('roles.id', $userRoleIds);
        })
        ->orderBy('order')
        ->get();

        // Grouper par section
        return $menuItems->groupBy(function ($item) {
            return $item->section ?? 'no_section';
        });
    }

    /**
     * Vérifie si un utilisateur a accès à un menu spécifique
     */
    public function hasAccessToMenu(User $user, string $menuCode): bool
    {
        $cacheKey = "user_menu_access_{$user->id}_{$menuCode}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($user, $menuCode) {
            $userRoleIds = $user->roles->pluck('id')->toArray();

            return MenuItem::where('code', $menuCode)
                ->active()
                ->whereHas('roles', function ($query) use ($userRoleIds) {
                    $query->whereIn('roles.id', $userRoleIds);
                })
                ->exists();
        });
    }

    /**
     * Récupère les permissions de menus pour un rôle
     */
    public function getMenuPermissionsForRole(int $roleId): Collection
    {
        return MenuItem::active()
            ->with('parent')
            ->get()
            ->map(function ($menu) use ($roleId) {
                return [
                    'id' => $menu->id,
                    'name' => $menu->name,
                    'code' => $menu->code,
                    'section' => $menu->section,
                    'parent_name' => $menu->parent?->name,
                    'has_access' => $menu->roles()->where('roles.id', $roleId)->exists(),
                ];
            });
    }

    /**
     * Met à jour les permissions de menus pour un rôle
     */
    public function updateMenuPermissionsForRole(int $roleId, array $menuIds): void
    {
        // Récupérer tous les menus et détacher le rôle
        MenuItem::all()->each(function ($menu) use ($roleId) {
            $menu->roles()->detach($roleId);
        });

        // Attacher le rôle aux menus sélectionnés
        MenuItem::whereIn('id', $menuIds)->get()->each(function ($menu) use ($roleId) {
            $menu->roles()->attach($roleId);
        });

        // Vider le cache pour tous les utilisateurs de ce rôle
        $this->clearCacheForRole($roleId);
    }

    /**
     * Vide le cache des menus pour un utilisateur
     */
    public function clearCacheForUser(int $userId): void
    {
        // Supprimer le cache des menus groupés
        Cache::forget("user_menus_{$userId}");

        // Supprimer les caches d'accès individuels
        $menuCodes = MenuItem::pluck('code');
        foreach ($menuCodes as $code) {
            Cache::forget("user_menu_access_{$userId}_{$code}");
        }
    }

    /**
     * Vide le cache pour tous les utilisateurs d'un rôle
     */
    public function clearCacheForRole(int $roleId): void
    {
        $users = User::whereHas('roles', function ($query) use ($roleId) {
            $query->where('roles.id', $roleId);
        })->get();

        foreach ($users as $user) {
            $this->clearCacheForUser($user->id);
        }
    }

    /**
     * Vide tout le cache des menus
     */
    public function clearAllCache(): void
    {
        $users = User::all();
        foreach ($users as $user) {
            $this->clearCacheForUser($user->id);
        }
    }

    /**
     * Récupère tous les menus avec leur hiérarchie pour l'administration
     */
    public function getAllMenusForAdmin(): Collection
    {
        return MenuItem::with(['children' => function ($query) {
            $query->orderBy('order');
        }, 'roles'])
        ->whereNull('parent_id')
        ->orderBy('section')
        ->orderBy('order')
        ->get()
        ->groupBy('section');
    }

    /**
     * Récupère tous les menus plats pour un formulaire
     */
    public function getAllMenusFlat(): Collection
    {
        return MenuItem::with('parent', 'roles')
            ->orderBy('section')
            ->orderBy('order')
            ->get();
    }
}
