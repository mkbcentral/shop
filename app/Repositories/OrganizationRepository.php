<?php

namespace App\Repositories;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class OrganizationRepository
{
    public function __construct(
        private Organization $model
    ) {}

    /**
     * Find organization by ID
     */
    public function find(int $id): ?Organization
    {
        return $this->model->find($id);
    }

    /**
     * Find organization by ID or fail
     */
    public function findOrFail(int $id): Organization
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Find organization by slug
     */
    public function findBySlug(string $slug): ?Organization
    {
        return $this->model->where('slug', $slug)->first();
    }

    /**
     * Create a new organization
     */
    public function create(array $data): Organization
    {
        return $this->model->create($data);
    }

    /**
     * Update an organization
     */
    public function update(Organization $organization, array $data): Organization
    {
        $organization->update($data);
        return $organization->fresh();
    }

    /**
     * Delete an organization
     */
    public function delete(Organization $organization): bool
    {
        return $organization->delete();
    }

    /**
     * Force delete an organization
     */
    public function forceDelete(Organization $organization): bool
    {
        return $organization->forceDelete();
    }

    /**
     * Restore a soft-deleted organization
     */
    public function restore(Organization $organization): bool
    {
        return $organization->restore();
    }

    /**
     * Get all organizations for a user
     */
    public function getForUser(User $user): Collection
    {
        return $user->organizations()
            ->with(['stores', 'owner'])
            ->orderBy('name')
            ->get();
    }

    /**
     * Get paginated list of organizations
     */
    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->query()->with(['owner', 'stores']);

        // Filter by type
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        // Filter by status
        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        // Filter by subscription plan
        if (!empty($filters['subscription_plan'])) {
            $query->where('subscription_plan', $filters['subscription_plan']);
        }

        // Filter by verified status
        if (isset($filters['is_verified'])) {
            $query->where('is_verified', $filters['is_verified']);
        }

        // Search
        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        // Filter by owner
        if (!empty($filters['owner_id'])) {
            $query->where('owner_id', $filters['owner_id']);
        }

        // Only active subscriptions
        if (!empty($filters['active_subscription'])) {
            $query->withActiveSubscription();
        }

        // Sort
        $sortField = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortField, $sortDirection);

        return $query->paginate($perPage);
    }

    /**
     * Get organizations by type
     */
    public function getByType(string $type): Collection
    {
        return $this->model->where('type', $type)->get();
    }

    /**
     * Get active organizations
     */
    public function getActive(): Collection
    {
        return $this->model->active()->get();
    }

    /**
     * Get organizations with expiring subscriptions
     */
    public function getExpiringSubscriptions(int $days = 7): Collection
    {
        return $this->model
            ->where('subscription_plan', '!=', 'free')
            ->whereBetween('subscription_ends_at', [now(), now()->addDays($days)])
            ->get();
    }

    /**
     * Get organizations statistics
     */
    public function getStatistics(Organization $organization): array
    {
        $storesCount = $organization->stores()->count();
        $activeStoresCount = $organization->activeStores()->count();
        $membersCount = $organization->members()->count();
        $activeMembersCount = $organization->activeMembers()->count();

        // Count products across all stores
        $productsCount = $organization->stores()
            ->withCount('products')
            ->get()
            ->sum('products_count');

        // Count sales for current month
        $salesThisMonth = $organization->stores()
            ->withSum(['sales as sales_total' => function ($query) {
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
            }], 'total')
            ->get()
            ->sum('sales_total');

        return [
            'stores' => [
                'total' => $storesCount,
                'active' => $activeStoresCount,
                'inactive' => $storesCount - $activeStoresCount,
            ],
            'members' => [
                'total' => $membersCount,
                'active' => $activeMembersCount,
                'inactive' => $membersCount - $activeMembersCount,
            ],
            'products_count' => $productsCount,
            'sales_this_month' => $salesThisMonth ?? 0,
            'limits' => [
                'max_stores' => $organization->max_stores,
                'max_users' => $organization->max_users,
                'max_products' => $organization->max_products,
            ],
            'usage' => [
                'stores' => $organization->getStoresUsage(),
                'users' => $organization->getUsersUsage(),
            ],
            'subscription' => [
                'plan' => $organization->subscription_plan,
                'plan_label' => $organization->plan_label,
                'is_trial' => $organization->is_trial,
                'ends_at' => $organization->subscription_ends_at,
                'remaining_days' => $organization->remaining_days,
                'is_active' => $organization->hasActiveSubscription(),
            ],
        ];
    }

    /**
     * Count organizations by type
     */
    public function countByType(): array
    {
        return $this->model
            ->selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();
    }

    /**
     * Count organizations by subscription plan
     */
    public function countByPlan(): array
    {
        return $this->model
            ->selectRaw('subscription_plan, count(*) as count')
            ->groupBy('subscription_plan')
            ->pluck('count', 'subscription_plan')
            ->toArray();
    }

    /**
     * Check if slug exists
     */
    public function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $query = $this->model->where('slug', $slug);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
