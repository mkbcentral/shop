<?php

namespace App\Livewire\Organization;

use App\Models\Organization;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class OrganizationIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $type = '';
    public string $sortBy = 'created_at';
    public string $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'type' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingType(): void
    {
        $this->resetPage();
    }

    /**
     * Switch to another organization
     */
    public function switchTo(int $organizationId): mixed
    {
        $user = auth()->user();

        if (!$user->belongsToOrganization($organizationId)) {
            session()->flash('error', 'Accès non autorisé à cette organisation.');
            return null;
        }

        $organization = Organization::find($organizationId);

        session(['current_organization_id' => $organization->id]);
        $user->update(['default_organization_id' => $organization->id]);

        session()->flash('success', "Vous êtes maintenant dans l'organisation \"{$organization->name}\".");

        return $this->redirect(route('dashboard'));
    }

    /**
     * Sort by column
     */
    public function sortBy(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Listen for organization created event
     */
    #[On('organization-created')]
    public function refreshOnCreate(): void
    {
        $this->resetPage();
        $this->dispatch('\$refresh');
    }

    /**
     * Listen for organization updated event
     */
    #[On('organization-updated')]
    public function refreshOnUpdate(): void
    {
        $this->dispatch('\$refresh');
    }

    /**
     * Listen for organization deleted event
     */
    #[On('organization-deleted')]
    public function refreshOnDelete(): void
    {
        $this->resetPage();
        $this->dispatch('\$refresh');
    }

    public function render()
    {
        $organizations = auth()->user()
            ->organizations()
            ->with(['stores', 'owner', 'members'])
            ->when($this->search, fn($q) => $q->where('organizations.name', 'like', "%{$this->search}%"))
            ->when($this->type, fn($q) => $q->where('organizations.type', $this->type))
            ->orderBy("organizations.{$this->sortBy}", $this->sortDirection)
            ->paginate(10);

        $types = [
            'individual' => 'Entrepreneur individuel',
            'company' => 'Entreprise',
            'franchise' => 'Franchise',
            'cooperative' => 'Coopérative',
            'group' => 'Groupe commercial',
        ];

        return view('livewire.organization.organization-index', [
            'organizations' => $organizations,
            'types' => $types,
        ]);
    }
}
