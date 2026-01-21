<?php

namespace App\Livewire\Admin;

use App\Models\MenuItem;
use App\Models\Role;
use App\Services\MenuService;
use Livewire\Component;

class MenuPermissionManager extends Component
{
    public ?int $selectedRoleId = null;
    public array $selectedMenus = [];
    public bool $showSuccessMessage = false;

    protected MenuService $menuService;

    public function boot(MenuService $menuService): void
    {
        $this->menuService = $menuService;
    }

    public function mount(): void
    {
        // Sélectionner le premier rôle par défaut
        $firstRole = Role::first();
        if ($firstRole) {
            $this->selectedRoleId = $firstRole->id;
            $this->loadMenusForRole();
        }
    }

    public function updatedSelectedRoleId(): void
    {
        $this->loadMenusForRole();
        $this->showSuccessMessage = false;
    }

    public function loadMenusForRole(): void
    {
        if (!$this->selectedRoleId) {
            $this->selectedMenus = [];
            return;
        }

        // Récupérer les IDs des menus auxquels ce rôle a accès
        $this->selectedMenus = MenuItem::whereHas('roles', function ($query) {
            $query->where('roles.id', $this->selectedRoleId);
        })->pluck('id')->toArray();
    }

    public function toggleMenu(int $menuId): void
    {
        if (in_array($menuId, $this->selectedMenus)) {
            // Désélectionner le menu
            $this->selectedMenus = array_values(array_diff($this->selectedMenus, [$menuId]));

            // Si c'est un parent, retirer aussi tous les enfants
            $children = MenuItem::where('parent_id', $menuId)->pluck('id')->toArray();
            $this->selectedMenus = array_values(array_diff($this->selectedMenus, $children));
        } else {
            // Sélectionner le menu
            $this->selectedMenus[] = $menuId;

            // Si c'est un parent, ajouter aussi tous les enfants
            $children = MenuItem::where('parent_id', $menuId)->pluck('id')->toArray();
            if (!empty($children)) {
                $this->selectedMenus = array_values(array_unique(array_merge($this->selectedMenus, $children)));
            }

            // Si c'est un enfant, ajouter aussi le parent
            $menu = MenuItem::find($menuId);
            if ($menu && $menu->parent_id && !in_array($menu->parent_id, $this->selectedMenus)) {
                $this->selectedMenus[] = $menu->parent_id;
            }
        }
    }

    public function toggleSection(string $section): void
    {
        $sectionMenuIds = MenuItem::where('section', $section)
            ->with('children')
            ->get()
            ->flatMap(function ($menu) {
                return collect([$menu->id])->merge($menu->children->pluck('id'));
            })
            ->toArray();

        $allSelected = empty(array_diff($sectionMenuIds, $this->selectedMenus));

        if ($allSelected) {
            // Désélectionner tous les menus de la section
            $this->selectedMenus = array_values(array_diff($this->selectedMenus, $sectionMenuIds));
        } else {
            // Sélectionner tous les menus de la section
            $this->selectedMenus = array_values(array_unique(array_merge($this->selectedMenus, $sectionMenuIds)));
        }
    }

    public function selectAll(): void
    {
        $this->selectedMenus = MenuItem::pluck('id')->toArray();
    }

    public function deselectAll(): void
    {
        $this->selectedMenus = [];
    }

    public function save(): void
    {
        if (!$this->selectedRoleId) {
            return;
        }

        $this->menuService->updateMenuPermissionsForRole(
            $this->selectedRoleId,
            $this->selectedMenus
        );

        $this->showSuccessMessage = true;

        // Vider tout le cache pour forcer le rechargement immédiat
        $this->menuService->clearAllCache();

        $this->dispatch('menu-permissions-updated');
        
        // Afficher un message de succès avec notification
        $this->dispatch('show-toast', 
            message: 'Permissions de menu mises à jour avec succès. Les changements seront visibles immédiatement.', 
            type: 'success'
        );
    }

    public function render()
    {
        return view('livewire.admin.menu-permission-manager', [
            'roles' => Role::orderBy('name')->get(),
            'menusBySection' => $this->menuService->getAllMenusForAdmin(),
        ]);
    }
}
