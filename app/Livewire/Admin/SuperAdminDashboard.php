<?php

namespace App\Livewire\Admin;

use App\Actions\Admin\ToggleOrganizationStatusAction;
use App\Actions\Admin\ToggleUserStatusAction;
use App\Models\Organization;
use App\Models\User;
use App\Services\Admin\ActivityService;
use App\Services\Admin\DashboardStatisticsService;
use Livewire\Component;
use Livewire\WithPagination;

class SuperAdminDashboard extends Component
{
    use WithPagination;

    public string $activeTab = 'overview';
    public string $searchUsers = '';
    public string $searchOrganizations = '';
    public string $userStatusFilter = 'all';
    public string $orgStatusFilter = 'all';
    public string $periodFilter = '30';

    protected $queryString = [
        'activeTab' => ['except' => 'overview'],
        'searchUsers' => ['except' => ''],
        'searchOrganizations' => ['except' => ''],
    ];

    protected $listeners = ['tabChanged' => 'handleTabChange', 'periodChanged' => 'handlePeriodChange'];

    public function handleTabChange(string $tab): void
    {
        $this->setTab($tab);
    }

    public function handlePeriodChange(int $period): void
    {
        $this->periodFilter = (string) $period;
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function updatedSearchUsers(): void
    {
        $this->resetPage();
    }

    public function updatedSearchOrganizations(): void
    {
        $this->resetPage();
    }

    public function toggleUserStatus(int $userId, ToggleUserStatusAction $action): void
    {
        $success = $action->execute($userId);
        
        if ($success) {
            $this->dispatch('notify', type: 'success', message: 'Statut utilisateur mis à jour');
        } else {
            $this->dispatch('notify', type: 'error', message: 'Impossible de modifier le statut');
        }
    }

    public function toggleOrganizationStatus(int $orgId, ToggleOrganizationStatusAction $action): void
    {
        $success = $action->execute($orgId);
        
        if ($success) {
            $this->dispatch('notify', type: 'success', message: 'Statut organisation mis à jour');
        } else {
            $this->dispatch('notify', type: 'error', message: 'Impossible de modifier le statut');
        }
    }

    public function render(
        DashboardStatisticsService $statisticsService,
        ActivityService $activityService
    ) {
        $periodDays = (int) $this->periodFilter;

        return view('livewire.admin.super-admin-dashboard', [
            'stats' => $statisticsService->getOverviewStats($periodDays),
            'subscriptionStats' => $statisticsService->getSubscriptionStats(),
            'usersGrowth' => $statisticsService->getUsersGrowthData($periodDays),
            'recentActivities' => $activityService->getRecentActivities()->toArray(),
            'users' => $this->getUsersQuery()->paginate(10, ['*'], 'usersPage'),
            'organizations' => $this->getOrganizationsQuery()->paginate(10, ['*'], 'orgsPage'),
        ]);
    }

    private function getUsersQuery()
    {
        return User::with(['roles', 'defaultOrganization'])
            ->when($this->searchUsers, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->searchUsers}%")
                        ->orWhere('email', 'like', "%{$this->searchUsers}%");
                });
            })
            ->when($this->userStatusFilter !== 'all', function ($query) {
                $query->where('is_active', $this->userStatusFilter === 'active');
            })
            ->latest();
    }

    private function getOrganizationsQuery()
    {
        return Organization::with(['owner', 'stores'])
            ->withCount(['members', 'stores'])
            ->when($this->searchOrganizations, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->searchOrganizations}%")
                        ->orWhere('slug', 'like', "%{$this->searchOrganizations}%");
                });
            })
            ->when($this->orgStatusFilter !== 'all', function ($query) {
                if ($this->orgStatusFilter === 'paid') {
                    $query->where('payment_status', 'completed');
                } elseif ($this->orgStatusFilter === 'trial') {
                    $query->where('is_trial', true);
                } elseif ($this->orgStatusFilter === 'expired') {
                    $query->where('subscription_ends_at', '<', now());
                }
            })
            ->latest();
    }
}
