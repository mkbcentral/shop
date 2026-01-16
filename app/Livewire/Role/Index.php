<?php

namespace App\Livewire\Role;

use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $sortBy = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;

    public $showDeleteModal = false;
    public $selectedRoleId;
    public $selectedRole;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function openDeleteModal($roleId)
    {
        $this->selectedRoleId = $roleId;
        $this->selectedRole = Role::find($roleId);
        
        // Check if role can be deleted (not super-admin and has no users)
        if ($this->selectedRole && $this->selectedRole->slug === 'super-admin') {
            session()->flash('error', 'Le rôle Super Admin ne peut pas être supprimé.');
            return;
        }
        
        if ($this->selectedRole && $this->selectedRole->users()->count() > 0) {
            session()->flash('error', 'Ce rôle ne peut pas être supprimé car il est assigné à ' . $this->selectedRole->users()->count() . ' utilisateur(s).');
            return;
        }
        
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->selectedRoleId = null;
        $this->selectedRole = null;
    }

    public function deleteRole()
    {
        if (!$this->selectedRole) {
            session()->flash('error', 'Rôle introuvable.');
            $this->closeDeleteModal();
            return;
        }

        try {
            $roleName = $this->selectedRole->name;
            $this->selectedRole->delete();

            session()->flash('success', "Le rôle {$roleName} a été supprimé avec succès.");
            $this->closeDeleteModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la suppression du rôle: ' . $e->getMessage());
            $this->closeDeleteModal();
        }
    }

    public function toggleStatus($roleId)
    {
        $role = Role::find($roleId);
        
        if (!$role) {
            session()->flash('error', 'Rôle introuvable.');
            return;
        }

        if ($role->slug === 'super-admin') {
            session()->flash('error', 'Le statut du rôle Super Admin ne peut pas être modifié.');
            return;
        }

        $role->is_active = !$role->is_active;
        $role->save();

        $status = $role->is_active ? 'activé' : 'désactivé';
        session()->flash('success', "Le rôle {$role->name} a été {$status} avec succès.");
    }

    public function render()
    {
        $query = Role::query();

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('slug', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Status filter
        if ($this->statusFilter !== '') {
            $query->where('is_active', $this->statusFilter == '1');
        }

        // Sort
        $query->orderBy($this->sortBy, $this->sortDirection);

        $roles = $query->withCount('users')->paginate($this->perPage);

        return view('livewire.role.index', [
            'roles' => $roles,
        ]);
    }
}
