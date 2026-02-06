<?php

namespace App\Livewire\Organization;

use App\Models\Organization;
use App\Models\Role;
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

    // Transfert de propriété
    public ?int $newOwnerId = null;

    protected $rules = [
        'inviteEmail' => 'required|email',
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

        $validRoleSlugs = Role::where('is_active', true)
            ->where('slug', '!=', 'super-admin')
            ->pluck('slug')
            ->implode(',');

        $this->validate([
            'inviteEmail' => 'required|email',
            'inviteRole' => "required|in:{$validRoleSlugs}",
        ]);

        try {
            $service->inviteMember(
                $this->organization,
                $this->inviteEmail,
                $this->inviteRole,
                auth()->user()
            );

            $this->closeInviteModal();
            $this->dispatch('show-toast', message: "Invitation envoyée à {$this->inviteEmail} !", type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: $e->getMessage(), type: 'error');
        }
    }

    public function cancelInvitation(int $invitationId, OrganizationService $service): void
    {
        $this->authorize('inviteMembers', $this->organization);

        $invitation = $this->organization->invitations()->findOrFail($invitationId);

        try {
            $service->cancelInvitation($invitation);
            $this->dispatch('show-toast', message: 'Invitation annulée.', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: $e->getMessage(), type: 'error');
        }
    }

    public function resendInvitation(int $invitationId, OrganizationService $service): void
    {
        $this->authorize('inviteMembers', $this->organization);

        $invitation = $this->organization->invitations()->findOrFail($invitationId);

        try {
            $service->resendInvitation($invitation);
            $this->dispatch('show-toast', message: 'Invitation renvoyée !', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: $e->getMessage(), type: 'error');
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
            $this->dispatch('show-toast', message: 'Membre non trouvé.', type: 'error');
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

    public function updateRole(int $memberId, string $newRole, OrganizationService $service): void
    {
        $this->authorize('updateMemberRoles', $this->organization);

        if (!$memberId || !$newRole) {
            return;
        }

        try {
            $user = User::findOrFail($memberId);
            $service->updateMemberRole($this->organization, $user, $newRole);

            $this->dispatch('show-toast', message: 'Rôle mis à jour avec succès.', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: $e->getMessage(), type: 'error');
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

            $this->closeConfirmModal();
            $this->dispatch('show-toast', message: 'Membre retiré de l\'organisation.', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: $e->getMessage(), type: 'error');
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
            $this->dispatch('show-toast', message: "Membre {$statusText}.", type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: $e->getMessage(), type: 'error');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Transfert de propriété
    |--------------------------------------------------------------------------
    */

    public function transferOwnership(OrganizationService $service): void
    {
        $this->authorize('transferOwnership', $this->organization);

        if (!$this->newOwnerId) {
            $this->dispatch('show-toast', message: 'Veuillez sélectionner un nouveau propriétaire.', type: 'error');
            return;
        }

        try {
            $newOwner = User::findOrFail($this->newOwnerId);
            $service->transferOwnership($this->organization, $newOwner);

            $this->newOwnerId = null;
            $this->dispatch('close-transfer-modal');
            $this->dispatch('show-toast', message: "La propriété a été transférée à {$newOwner->name}.", type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: $e->getMessage(), type: 'error');
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

        // Fetch roles from database (excluding super-admin)
        $roles = Role::where('is_active', true)
            ->where('slug', '!=', 'super-admin')
            ->orderBy('name')
            ->pluck('name', 'slug')
            ->toArray();

        $currentUser = auth()->user();
        $canManage = $this->organization->isManagerOrHigher($currentUser);
        $canAdmin = $this->organization->isAdmin($currentUser);

        return view('livewire.organization.organization-members', [
            'members' => $members,
            'pendingInvitations' => $pendingInvitations,
            'roles' => $roles,
            'roleLabels' => $roles,
            'canManage' => $canManage,
            'canAdmin' => $canAdmin,
            'usage' => $this->organization->getUsersUsage(),
        ]);
    }
}
