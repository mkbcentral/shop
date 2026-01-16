<?php

namespace App\Livewire\Role;

use App\Models\Role;
use Livewire\Component;

class Edit extends Component
{
    public $roleId;
    public $role;

    public $name = '';
    public $slug = '';
    public $description = '';
    public $is_active = true;
    public $selectedPermissions = [];

    // Available permissions organized by category
    public $permissionCategories = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|regex:/^[a-z0-9-]+$/',
        'description' => 'nullable|string|max:500',
        'is_active' => 'boolean',
        'selectedPermissions' => 'array',
    ];

    protected $messages = [
        'name.required' => 'Le nom du rôle est obligatoire.',
        'slug.required' => 'Le slug est obligatoire.',
        'slug.regex' => 'Le slug ne peut contenir que des lettres minuscules, chiffres et tirets.',
    ];

    public function mount($id)
    {
        $this->roleId = $id;
        $this->role = Role::findOrFail($id);

        // Populate form fields
        $this->name = $this->role->name;
        $this->slug = $this->role->slug;
        $this->description = $this->role->description;
        $this->is_active = $this->role->is_active;
        $this->selectedPermissions = $this->role->permissions ?? [];

        $this->permissionCategories = $this->getPermissionCategories();
    }

    public function updatedName($value)
    {
        // Only auto-generate slug if it's not super-admin
        if ($this->role->slug !== 'super-admin') {
            $this->slug = \Str::slug($value);
        }
    }

    public function togglePermissionCategory($category)
    {
        $categoryPermissions = collect($this->permissionCategories[$category]['permissions'])
            ->pluck('value')
            ->toArray();

        $allSelected = collect($categoryPermissions)->every(fn($perm) => in_array($perm, $this->selectedPermissions));

        if ($allSelected) {
            // Deselect all in category
            $this->selectedPermissions = array_values(array_diff($this->selectedPermissions, $categoryPermissions));
        } else {
            // Select all in category
            $this->selectedPermissions = array_values(array_unique(array_merge($this->selectedPermissions, $categoryPermissions)));
        }
    }

    public function selectAllPermissions()
    {
        $allPermissions = [];
        foreach ($this->permissionCategories as $category) {
            foreach ($category['permissions'] as $permission) {
                $allPermissions[] = $permission['value'];
            }
        }
        $this->selectedPermissions = $allPermissions;
    }

    public function deselectAllPermissions()
    {
        $this->selectedPermissions = [];
    }

    public function update()
    {
        // Prevent editing super-admin slug
        if ($this->role->slug === 'super-admin' && $this->slug !== 'super-admin') {
            $this->slug = 'super-admin';
        }

        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|regex:/^[a-z0-9-]+$/|unique:roles,slug,' . $this->roleId,
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'selectedPermissions' => 'array',
        ]);

        try {
            $this->role->update([
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description,
                'is_active' => $this->is_active,
                'permissions' => $this->selectedPermissions,
            ]);

            session()->flash('success', 'Le rôle a été modifié avec succès.');
            return redirect()->route('roles.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la modification du rôle: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('roles.index');
    }

    private function getPermissionCategories()
    {
        return [
            'system' => [
                'label' => 'Système',
                'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
                'permissions' => [
                    ['value' => 'system.settings', 'label' => 'Paramètres système'],
                    ['value' => 'system.backup', 'label' => 'Sauvegarde'],
                    ['value' => 'system.logs', 'label' => 'Journaux'],
                ],
            ],
            'users' => [
                'label' => 'Utilisateurs',
                'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                'permissions' => [
                    ['value' => 'users.view', 'label' => 'Voir'],
                    ['value' => 'users.create', 'label' => 'Créer'],
                    ['value' => 'users.edit', 'label' => 'Modifier'],
                    ['value' => 'users.delete', 'label' => 'Supprimer'],
                    ['value' => 'users.assign-role', 'label' => 'Assigner rôle'],
                    ['value' => 'users.assign-store', 'label' => 'Assigner magasin'],
                ],
            ],
            'stores' => [
                'label' => 'Magasins',
                'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                'permissions' => [
                    ['value' => 'stores.view', 'label' => 'Voir'],
                    ['value' => 'stores.create', 'label' => 'Créer'],
                    ['value' => 'stores.edit', 'label' => 'Modifier'],
                    ['value' => 'stores.delete', 'label' => 'Supprimer'],
                    ['value' => 'stores.manage-users', 'label' => 'Gérer utilisateurs'],
                    ['value' => 'stores.view-statistics', 'label' => 'Voir statistiques'],
                ],
            ],
            'roles' => [
                'label' => 'Rôles',
                'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                'permissions' => [
                    ['value' => 'roles.view', 'label' => 'Voir'],
                    ['value' => 'roles.create', 'label' => 'Créer'],
                    ['value' => 'roles.edit', 'label' => 'Modifier'],
                    ['value' => 'roles.delete', 'label' => 'Supprimer'],
                ],
            ],
            'products' => [
                'label' => 'Produits',
                'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
                'permissions' => [
                    ['value' => 'products.view', 'label' => 'Voir'],
                    ['value' => 'products.create', 'label' => 'Créer'],
                    ['value' => 'products.edit', 'label' => 'Modifier'],
                    ['value' => 'products.delete', 'label' => 'Supprimer'],
                    ['value' => 'products.import', 'label' => 'Importer'],
                ],
            ],
            'categories' => [
                'label' => 'Catégories',
                'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z',
                'permissions' => [
                    ['value' => 'categories.view', 'label' => 'Voir'],
                    ['value' => 'categories.create', 'label' => 'Créer'],
                    ['value' => 'categories.edit', 'label' => 'Modifier'],
                    ['value' => 'categories.delete', 'label' => 'Supprimer'],
                ],
            ],
            'sales' => [
                'label' => 'Ventes',
                'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z',
                'permissions' => [
                    ['value' => 'sales.view', 'label' => 'Voir'],
                    ['value' => 'sales.create', 'label' => 'Créer'],
                    ['value' => 'sales.edit', 'label' => 'Modifier'],
                    ['value' => 'sales.delete', 'label' => 'Supprimer'],
                    ['value' => 'sales.refund', 'label' => 'Rembourser'],
                ],
            ],
            'purchases' => [
                'label' => 'Achats',
                'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',
                'permissions' => [
                    ['value' => 'purchases.view', 'label' => 'Voir'],
                    ['value' => 'purchases.create', 'label' => 'Créer'],
                    ['value' => 'purchases.edit', 'label' => 'Modifier'],
                    ['value' => 'purchases.delete', 'label' => 'Supprimer'],
                ],
            ],
            'clients' => [
                'label' => 'Clients',
                'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                'permissions' => [
                    ['value' => 'clients.view', 'label' => 'Voir'],
                    ['value' => 'clients.create', 'label' => 'Créer'],
                    ['value' => 'clients.edit', 'label' => 'Modifier'],
                    ['value' => 'clients.delete', 'label' => 'Supprimer'],
                ],
            ],
            'suppliers' => [
                'label' => 'Fournisseurs',
                'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                'permissions' => [
                    ['value' => 'suppliers.view', 'label' => 'Voir'],
                    ['value' => 'suppliers.create', 'label' => 'Créer'],
                    ['value' => 'suppliers.edit', 'label' => 'Modifier'],
                    ['value' => 'suppliers.delete', 'label' => 'Supprimer'],
                ],
            ],
            'transfers' => [
                'label' => 'Transferts',
                'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4',
                'permissions' => [
                    ['value' => 'transfers.view', 'label' => 'Voir'],
                    ['value' => 'transfers.create', 'label' => 'Créer'],
                    ['value' => 'transfers.edit', 'label' => 'Modifier'],
                    ['value' => 'transfers.delete', 'label' => 'Supprimer'],
                    ['value' => 'transfers.approve', 'label' => 'Approuver'],
                ],
            ],
            'reports' => [
                'label' => 'Rapports',
                'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                'permissions' => [
                    ['value' => 'reports.sales', 'label' => 'Ventes'],
                    ['value' => 'reports.purchases', 'label' => 'Achats'],
                    ['value' => 'reports.stock', 'label' => 'Stock'],
                    ['value' => 'reports.financial', 'label' => 'Financiers'],
                ],
            ],
        ];
    }

    public function render()
    {
        return view('livewire.role.edit');
    }
}
