<?php

namespace App\Services;

use App\Models\MenuItem;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class MenuService
{
    /**
     * Durée du cache en secondes (30 secondes - très court pour refléter rapidement les changements)
     */
    private const CACHE_TTL = 30;

    protected PlanLimitService $planLimitService;

    public function __construct(PlanLimitService $planLimitService)
    {
        $this->planLimitService = $planLimitService;
    }

    /**
     * Récupère tous les menus accessibles pour un utilisateur, groupés par section
     */
    public function getAccessibleMenusForUser(User $user): Collection
    {
        $organization = $this->planLimitService->getCurrentOrganization();
        $orgId = $organization?->id ?? 0;
        $planSlug = $organization?->subscription_plan?->value ?? 'none';

        // Vérifier si le cache global a été invalidé
        $globalVersion = Cache::get('menu_cache_version', 1);
        $cacheKey = "user_menus_{$user->id}_{$orgId}_{$planSlug}_v{$globalVersion}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($user, $organization) {
            return $this->buildMenuStructure($user, $organization);
        });
    }

    /**
     * Invalide tous les caches de menus en incrémentant la version
     */
    public static function invalidateAllMenuCaches(): void
    {
        $currentVersion = Cache::get('menu_cache_version', 1);
        Cache::put('menu_cache_version', $currentVersion + 1, 86400); // 24h
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
    private function buildMenuStructure(User $user, ?Organization $organization): Collection
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

        // Filtrer les menus selon les fonctionnalités du plan
        $filteredMenus = $this->filterMenusByPlanFeatures($menuItems, $organization);

        // Réordonner le menu "organizations" pour les non super-admin
        // Super-admin: organizations en 2ème position (order=2)
        // Autres: organizations en avant-dernière position
        if (!$user->hasRole('super-admin')) {
            $filteredMenus = $this->reorderOrganizationsMenu($filteredMenus);
        }

        // Grouper par section
        return $filteredMenus->groupBy(function ($item) {
            return $item->section ?? 'no_section';
        });
    }

    /**
     * Réordonne le menu organisations en avant-dernière position pour les non super-admin
     * En déplaçant le menu vers la section "Administration"
     */
    private function reorderOrganizationsMenu(Collection $menuItems): Collection
    {
        return $menuItems->map(function ($menu) {
            // Pour les non super-admin, déplacer organizations vers la section Administration
            if ($menu->code === 'organizations') {
                // Cloner pour ne pas modifier l'original en cache
                $menu = clone $menu;
                $menu->section = 'Administration';
                $menu->order = 0; // Avant les autres menus de cette section
            }
            return $menu;
        });
    }

    /**
     * Filtre les menus selon les fonctionnalités disponibles dans le plan
     */
    private function filterMenusByPlanFeatures(Collection $menuItems, ?Organization $organization): Collection
    {
        return $menuItems->filter(function (MenuItem $menu) use ($organization) {
            // Si pas de feature requise, le menu est accessible
            if (empty($menu->required_feature)) {
                return true;
            }

            // Vérifier si l'organisation a la fonctionnalité
            return $this->planLimitService->hasFeature($menu->required_feature, $organization);
        })->map(function (MenuItem $menu) use ($organization) {
            // Filtrer aussi les enfants
            if ($menu->children->isNotEmpty()) {
                $menu->setRelation(
                    'children',
                    $menu->children->filter(function (MenuItem $child) use ($organization) {
                        if (empty($child->required_feature)) {
                            return true;
                        }
                        return $this->planLimitService->hasFeature($child->required_feature, $organization);
                    })
                );
            }
            return $menu;
        });
    }

    /**
     * Vérifie si un utilisateur a accès à un menu spécifique
     */
    public function hasAccessToMenu(User $user, string $menuCode): bool
    {
        $organization = $this->planLimitService->getCurrentOrganization();
        $orgId = $organization?->id ?? 0;
        $cacheKey = "user_menu_access_{$user->id}_{$menuCode}_{$orgId}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($user, $menuCode, $organization) {
            $userRoleIds = $user->roles->pluck('id')->toArray();

            $menu = MenuItem::where('code', $menuCode)
                ->active()
                ->whereHas('roles', function ($query) use ($userRoleIds) {
                    $query->whereIn('roles.id', $userRoleIds);
                })
                ->first();

            if (!$menu) {
                return false;
            }

            // Vérifier si une fonctionnalité est requise
            if (!empty($menu->required_feature)) {
                return $this->planLimitService->hasFeature($menu->required_feature, $organization);
            }

            return true;
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
