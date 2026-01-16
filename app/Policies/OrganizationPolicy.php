<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any organizations.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the organization.
     */
    public function view(User $user, Organization $organization): bool
    {
        return $organization->hasMember($user);
    }

    /**
     * Determine whether the user can create organizations.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the organization.
     */
    public function update(User $user, Organization $organization): bool
    {
        return $organization->isAdmin($user);
    }

    /**
     * Determine whether the user can delete the organization.
     */
    public function delete(User $user, Organization $organization): bool
    {
        return $organization->isOwner($user);
    }

    /**
     * Determine whether the user can restore the organization.
     */
    public function restore(User $user, Organization $organization): bool
    {
        return $organization->isOwner($user);
    }

    /**
     * Determine whether the user can permanently delete the organization.
     */
    public function forceDelete(User $user, Organization $organization): bool
    {
        return $organization->isOwner($user);
    }

    /**
     * Determine whether the user can manage members (invite, remove, change roles).
     */
    public function manageMembers(User $user, Organization $organization): bool
    {
        $role = $organization->getUserRole($user);
        return in_array($role, ['owner', 'admin', 'manager']);
    }

    /**
     * Determine whether the user can invite new members.
     */
    public function inviteMembers(User $user, Organization $organization): bool
    {
        return $this->manageMembers($user, $organization);
    }

    /**
     * Determine whether the user can remove members.
     */
    public function removeMembers(User $user, Organization $organization): bool
    {
        return $organization->isAdmin($user);
    }

    /**
     * Determine whether the user can update member roles.
     */
    public function updateMemberRoles(User $user, Organization $organization): bool
    {
        return $organization->isAdmin($user);
    }

    /**
     * Determine whether the user can manage subscription.
     */
    public function manageSubscription(User $user, Organization $organization): bool
    {
        return $organization->isOwner($user);
    }

    /**
     * Determine whether the user can transfer ownership.
     */
    public function transferOwnership(User $user, Organization $organization): bool
    {
        return $organization->isOwner($user);
    }

    /**
     * Determine whether the user can create stores in this organization.
     */
    public function createStore(User $user, Organization $organization): bool
    {
        return $organization->isAdmin($user) && $organization->canAddStore();
    }

    /**
     * Determine whether the user can view organization reports.
     */
    public function viewReports(User $user, Organization $organization): bool
    {
        $role = $organization->getUserRole($user);
        return in_array($role, ['owner', 'admin', 'manager', 'accountant']);
    }

    /**
     * Determine whether the user can view organization settings.
     */
    public function viewSettings(User $user, Organization $organization): bool
    {
        return $organization->isAdmin($user);
    }

    /**
     * Determine whether the user can verify the organization (admin only).
     */
    public function verify(User $user, Organization $organization): bool
    {
        // Only super-admin can verify organizations
        return $user->hasRole('super-admin');
    }
}
