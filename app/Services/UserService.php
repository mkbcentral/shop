<?php

namespace App\Services;

use App\Models\Role;
use App\Models\Store;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    /**
     * Get all users with filters
     */
    public function getAllUsers(
        ?string $search = null,
        ?string $role = null,
        ?int $storeId = null,
        string $sortBy = 'name',
        string $sortDirection = 'asc',
        int $perPage = 10
    ): LengthAwarePaginator {
        return $this->userRepository->getAllWithFilters($search, $role, $storeId, $sortBy, $sortDirection, $perPage);
    }

    /**
     * Get active users
     */
    public function getActiveUsers(): Collection
    {
        return $this->userRepository->getActive();
    }

    /**
     * Find user by ID
     */
    public function findUser(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    /**
     * Find user by email
     */
    public function findUserByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    /**
     * Create a new user
     */
    public function createUser(array $data): User
    {
        // Vérifier la limite d'utilisateurs du plan
        $this->checkUserLimit();

        DB::beginTransaction();

        try {
            // Extract relations from data
            $roles = $data['roles'] ?? [];
            $stores = $data['stores'] ?? [];
            unset($data['roles'], $data['stores']);

            // Hash password if provided
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            // Set default_organization_id if not provided
            if (!isset($data['default_organization_id']) || empty($data['default_organization_id'])) {
                // Use authenticated user's organization or get the first active organization
                $organizationId = auth()->user()?->default_organization_id;

                if (!$organizationId) {
                    $organization = \App\Models\Organization::where('is_active', true)->first();
                    if ($organization) {
                        $organizationId = $organization->id;
                    }
                }

                if ($organizationId) {
                    $data['default_organization_id'] = $organizationId;
                }
            }

            // Create the user
            $user = $this->userRepository->create($data);

            // Ajouter l'utilisateur à l'organisation via la table pivot organization_user
            if (isset($data['default_organization_id']) && $data['default_organization_id']) {
                $organization = \App\Models\Organization::find($data['default_organization_id']);
                if ($organization && !$organization->hasMember($user)) {
                    $organization->members()->attach($user->id, [
                        'role' => 'member', // Rôle par défaut dans l'organisation
                        'invited_at' => now(),
                        'accepted_at' => now(),
                        'invited_by' => auth()->id(),
                        'is_active' => true,
                    ]);
                }
            }

            // Assign roles if provided
            if (!empty($roles) && is_array($roles)) {
                $user->assignRoles($roles);
            }

            // Assign stores if provided
            if (!empty($stores) && is_array($stores)) {
                foreach ($stores as $storeId => $storeData) {
                    $role = $storeData['role'] ?? 'staff';
                    $isDefault = $storeData['is_default'] ?? false;

                    $user->stores()->attach($storeId, [
                        'role' => $role,
                        'is_default' => $isDefault,
                    ]);

                    // Set as current store if default
                    if ($isDefault && !$user->current_store_id) {
                        $user->update(['current_store_id' => $storeId]);
                    }
                }
            }

            DB::commit();

            return $user->fresh(['roles', 'stores', 'currentStore']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update a user
     */
    public function updateUser(int $id, array $data): User
    {
        DB::beginTransaction();

        try {
            $user = $this->findUser($id);

            if (!$user) {
                throw new \Exception('User not found');
            }

            // Hash password if provided
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            // Update basic user data
            $userData = collect($data)->except(['roles', 'stores'])->toArray();
            $this->userRepository->update($user, $userData);

            // Update roles if provided
            if (isset($data['roles'])) {
                if (is_array($data['roles'])) {
                    $user->syncRoles($data['roles']);
                }
            }

            // Update stores if provided
            if (isset($data['stores']) && is_array($data['stores'])) {
                $storesData = [];
                foreach ($data['stores'] as $storeId => $storeData) {
                    $storesData[$storeId] = [
                        'role' => $storeData['role'] ?? 'staff',
                        'is_default' => $storeData['is_default'] ?? false,
                    ];
                }
                $user->stores()->sync($storesData);

                // Update current store if needed
                $defaultStore = collect($data['stores'])->first(fn($s) => $s['is_default'] ?? false);
                if ($defaultStore) {
                    $user->update(['current_store_id' => array_search($defaultStore, $data['stores'])]);
                }
            }

            DB::commit();

            return $user->fresh(['roles', 'stores', 'currentStore']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete a user
     */
    public function deleteUser(int $id): bool
    {
        $user = $this->findUser($id);

        if (!$user) {
            throw new \Exception('User not found');
        }

        // Prevent deleting yourself or super admin
        if ($user->hasRole('super-admin')) {
            throw new \Exception('Cannot delete super admin user');
        }

        return $this->userRepository->delete($user);
    }

    /**
     * Assign role to user
     */
    public function assignRole(int $userId, string|int|Role $role): User
    {
        $user = $this->findUser($userId);

        if (!$user) {
            throw new \Exception('User not found');
        }

        $user->assignRole($role);

        return $user->fresh(['roles']);
    }

    /**
     * Remove role from user
     */
    public function removeRole(int $userId, string|int|Role $role): User
    {
        $user = $this->findUser($userId);

        if (!$user) {
            throw new \Exception('User not found');
        }

        $user->removeRole($role);

        return $user->fresh(['roles']);
    }

    /**
     * Assign user to store
     */
    public function assignToStore(int $userId, int $storeId, string $role = 'staff', bool $isDefault = false): User
    {
        $user = $this->findUser($userId);
        $store = Store::find($storeId);

        if (!$user) {
            throw new \Exception('User not found');
        }

        if (!$store) {
            throw new \Exception('Store not found');
        }

        // Check if already assigned
        $existing = $user->stores()->where('stores.id', $storeId)->exists();

        if ($existing) {
            // Update existing assignment
            $user->stores()->updateExistingPivot($storeId, [
                'role' => $role,
                'is_default' => $isDefault,
            ]);
        } else {
            // Create new assignment
            $user->stores()->attach($storeId, [
                'role' => $role,
                'is_default' => $isDefault,
            ]);
        }

        // Update current store if this is default
        if ($isDefault) {
            $user->update(['current_store_id' => $storeId]);
        }

        return $user->fresh(['stores', 'currentStore']);
    }

    /**
     * Remove user from store
     */
    public function removeFromStore(int $userId, int $storeId): User
    {
        $user = $this->findUser($userId);

        if (!$user) {
            throw new \Exception('User not found');
        }

        $user->stores()->detach($storeId);

        // If this was the current store, set to another store or null
        if ($user->current_store_id === $storeId) {
            $otherStore = $user->stores()->first();
            $user->update(['current_store_id' => $otherStore?->id]);
        }

        return $user->fresh(['stores', 'currentStore']);
    }

    /**
     * Update user's store role
     */
    public function updateStoreRole(int $userId, int $storeId, string $role): User
    {
        $user = $this->findUser($userId);

        if (!$user) {
            throw new \Exception('User not found');
        }

        $user->stores()->updateExistingPivot($storeId, [
            'role' => $role,
        ]);

        return $user->fresh(['stores']);
    }

    /**
     * Set user's default store
     */
    public function setDefaultStore(int $userId, int $storeId): User
    {
        $user = $this->findUser($userId);

        if (!$user) {
            throw new \Exception('User not found');
        }

        // Verify user has access to this store
        if (!$user->hasAccessToStore($storeId)) {
            throw new \Exception('User does not have access to this store');
        }

        // Remove default from all stores
        DB::table('store_user')
            ->where('user_id', $userId)
            ->update(['is_default' => false]);

        // Set new default
        $user->stores()->updateExistingPivot($storeId, [
            'is_default' => true,
        ]);

        // Update current store
        $user->update(['current_store_id' => $storeId]);

        return $user->fresh(['stores', 'currentStore']);
    }

    /**
     * Get users by role
     */
    public function getUsersByRole(string $roleSlug): Collection
    {
        return $this->userRepository->getUsersByRole($roleSlug);
    }

    /**
     * Get users by store
     */
    public function getUsersByStore(int $storeId): Collection
    {
        return $this->userRepository->getUsersByStore($storeId);
    }

    /**
     * Search users
     */
    public function searchUsers(string $query, int $limit = 10): Collection
    {
        return $this->userRepository->search($query, $limit);
    }

    /**
     * Get user statistics
    /**
     * Get statistics for a user
     */
    public function getUserStatistics(int $userId): array
    {
        $user = $this->findUser($userId);

        if (!$user) {
            return [
                'total_stores' => 0,
                'total_roles' => 0,
                'last_login' => null,
                'account_status' => 'inactive',
            ];
        }

        return [
            'total_stores' => $user->stores()->count(),
            'total_roles' => $user->roles()->count(),
            'managed_stores' => $user->managedStores()->count(),
            'last_login' => $user->last_login_at,
            'account_status' => $user->email_verified_at ? 'active' : 'pending',
        ];
    }

    /**
     * Vérifie si l'organisation peut ajouter un utilisateur selon son plan
     * @throws \Exception si la limite est atteinte
     */
    protected function checkUserLimit(): void
    {
        $user = auth()->user();

        if (!$user) {
            return; // Pas d'utilisateur connecté
        }

        $organizationId = session('current_organization_id') ?? $user->default_organization_id;
        $organization = \App\Models\Organization::find($organizationId);

        if (!$organization) {
            return; // Pas d'organisation
        }

        if (!$organization->canAddUser()) {
            $usage = $organization->getUsersUsage();
            $planLimitService = app(\App\Services\PlanLimitService::class);
            throw new \Exception(
                $planLimitService->getLimitReachedMessage('users', $usage['current'], $usage['max'])
            );
        }
    }
}
