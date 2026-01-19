<?php

namespace App\Livewire\Organization;

use App\Models\Organization;
use App\Models\User;
use App\Services\OrganizationService;
use Livewire\Component;
use Livewire\WithPagination;

class OrganizationMembers extends Component
{
    use WithPagination;

    public Organization $organization;
    public string $search = '';

    // Invitation
    public string $inviteEmail = '';
    public string $inviteRole = 'member';

    // Modal de changement de rôle
    public bool $showRoleModal = false;
    public ?int $editingMemberId = null;
    public string $newRole = '';

    // Modal de confirmation
    public bool $showConfirmModal = false;
    public ?int $memberToRemove = null;

    protected $rules = [
        'inviteEmail' => 'required|email',
        'inviteRole' => 'required|in:admin,manager,accountant,member',
    ];

    protected $messages = [
        'inviteEmail.required' => 'L\'adresse email est obligatoire.',
        'inviteEmail.email' => 'L\'adresse email n\'est pas valide.',
        'inviteRole.required' => 'Le rôle est obligatoire.',
    ];

    public function mount(Organization $organization): void
    {
        $this->authorize('view', $organization);
        $this->organization = $organization;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /*
    |--------------------------------------------------------------------------
    | Invitation
    |--------------------------------------------------------------------------
    */

    public function openInviteModal(): void
    {
        $this->authorize('inviteMembers', $this->organization);

        $this->reset(['inviteEmail', 'inviteRole']);
        $this->inviteRole = 'member';
        
        $this->dispatch('open-invite-modal');
    }

    public function closeInviteModal(): void
    {
        $this->dispatch('close-invite-modal');
        $this->reset(['inviteEmail', 'inviteRole']);
    }

    public function invite(OrganizationService $service): void
    {
        $this->authorize('inviteMembers', $this->organization);

        $this->validate([
            'inviteEmail' => 'required|email',
            'inviteRole' => 'required|in:admin,manager,accountant,member',
        ]);

        try {
            $service->inviteMember(
                $this->organization,
                $this->inviteEmail,
                $this->inviteRole,
                auth()->user()
            );

            session()->flash('success', "Invitation envoyée à {$this->inviteEmail} !");
            $this->closeInviteModal();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function cancelInvitation(int $invitationId, OrganizationService $service): void
    {
        $this->authorize('inviteMembers', $this->organization);

        $invitation = $this->organization->invitations()->findOrFail($invitationId);

        try {
            $service->cancelInvitation($invitation);
            session()->flash('success', 'Invitation annulée.');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function resendInvitation(int $invitationId, OrganizationService $service): void
    {
        $this->authorize('inviteMembers', $this->organization);

        $invitation = $this->organization->invitations()->findOrFail($invitationId);

        try {
            $service->resendInvitation($invitation);
            session()->flash('success', 'Invitation renvoyée !');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Changement de rôle
    |--------------------------------------------------------------------------
    */

    public function openRoleModal(int $memberId): void
    {
        $this->authorize('updateMemberRoles', $this->organization);

        $member = $this->organization->members()->where('user_id', $memberId)->first();

        if (!$member) {
            session()->flash('error', 'Membre non trouvé.');
            return;
        }

        $this->editingMemberId = $memberId;
        $this->newRole = $member->pivot->role;
        $this->showRoleModal = true;
    }

    public function closeRoleModal(): void
    {
        $this->showRoleModal = false;
        $this->reset(['editingMemberId', 'newRole']);
    }

    public function updateRole(OrganizationService $service): void
    {
        $this->authorize('updateMemberRoles', $this->organization);

        if (!$this->editingMemberId || !$this->newRole) {
            return;
        }

        try {
            $user = User::findOrFail($this->editingMemberId);
            $service->updateMemberRole($this->organization, $user, $this->newRole);

            session()->flash('success', 'Rôle mis à jour avec succès.');
            $this->closeRoleModal();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Suppression de membre
    |--------------------------------------------------------------------------
    */

    public function confirmRemove(int $memberId): void
    {
        $this->authorize('removeMembers', $this->organization);

        $this->memberToRemove = $memberId;
        $this->showConfirmModal = true;
    }

    public function closeConfirmModal(): void
    {
        $this->showConfirmModal = false;
        $this->memberToRemove = null;
    }

    public function removeMember(OrganizationService $service): void
    {
        $this->authorize('removeMembers', $this->organization);

        if (!$this->memberToRemove) {
            return;
        }

        try {
            $user = User::findOrFail($this->memberToRemove);
            $service->removeMember($this->organization, $user);

            session()->flash('success', 'Membre retiré de l\'organisation.');
            $this->closeConfirmModal();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Toggle status
    |--------------------------------------------------------------------------
    */

    public function toggleStatus(int $memberId, OrganizationService $service): void
    {
        $this->authorize('updateMemberRoles', $this->organization);

        try {
            $user = User::findOrFail($memberId);
            $newStatus = $service->toggleMemberStatus($this->organization, $user);

            $statusText = $newStatus ? 'activé' : 'désactivé';
            session()->flash('success', "Membre {$statusText}.");
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        $members = $this->organization
            ->members()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%"))
            ->orderByRaw("FIELD(organization_user.role, 'owner', 'admin', 'manager', 'accountant', 'member')")
            ->paginate(10);

        $pendingInvitations = $this->organization
            ->pendingInvitations()
            ->with('inviter')
            ->latest()
            ->get();

        $roles = [
            'admin' => 'Administrateur',
            'manager' => 'Manager',
            'accountant' => 'Comptable',
            'member' => 'Membre',
        ];

        $roleLabels = [
            'owner' => 'Propriétaire',
            'admin' => 'Administrateur',
            'manager' => 'Manager',
            'accountant' => 'Comptable',
            'member' => 'Membre',
        ];

        $currentUser = auth()->user();
        $canManage = $this->organization->isManagerOrHigher($currentUser);
        $canAdmin = $this->organization->isAdmin($currentUser);

        return view('livewire.organization.organization-members', [
            'members' => $members,
            'pendingInvitations' => $pendingInvitations,
            'roles' => $roles,
            'roleLabels' => $roleLabels,
            'canManage' => $canManage,
            'canAdmin' => $canAdmin,
            'usage' => $this->organization->getUsersUsage(),
        ]);
    }
}
