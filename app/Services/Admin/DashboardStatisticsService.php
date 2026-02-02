<?php

namespace App\Services\Admin;

use App\Models\Organization;
use App\Models\Product;
use App\Models\Role;
use App\Models\Sale;
use App\Models\Store;
use App\Models\SubscriptionPayment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardStatisticsService
{
    public function getOverviewStats(int $periodDays = 30): array
    {
        $startDate = now()->subDays($periodDays);

        return [
            // Users statistics
            'total_users' => $this->getTotalUsers(),
            'active_users' => $this->getActiveUsers(),
            'inactive_users' => $this->getInactiveUsers(),
            'new_users_period' => $this->getNewUsers($startDate),
            'verified_users' => $this->getVerifiedUsers(),

            // Organizations statistics
            'total_organizations' => $this->getTotalOrganizations(),
            'active_organizations' => $this->getActiveOrganizations(),
            'paid_organizations' => $this->getPaidOrganizations(),
            'trial_organizations' => $this->getTrialOrganizations(),
            'new_organizations_period' => $this->getNewOrganizations($startDate),

            // Stores statistics
            'total_stores' => $this->getTotalStores(),
            'active_stores' => $this->getActiveStores(),

            // Sales statistics
            'total_sales_amount' => $this->getTotalSalesAmount(),
            'period_sales_amount' => $this->getPeriodSalesAmount($startDate),
            'total_sales_count' => $this->getTotalSalesCount(),

            // Products statistics
            'total_products' => $this->getTotalProducts(),

            // Roles statistics
            'total_roles' => $this->getTotalRoles(),

            // Subscription revenue
            'subscription_revenue' => $this->getTotalSubscriptionRevenue(),
            'period_subscription_revenue' => $this->getPeriodSubscriptionRevenue($startDate),
        ];
    }

    public function getSubscriptionStats(): array
    {
        return [
            'by_plan' => $this->getSubscriptionsByPlan(),
            'by_status' => $this->getSubscriptionsByStatus(),
            'expiring_soon' => $this->getExpiringSoonSubscriptions(),
            'expired' => $this->getExpiredSubscriptions(),
        ];
    }

    public function getUsersGrowthData(int $days = 30): array
    {
        $data = User::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $data->pluck('date')
                ->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))
                ->toArray(),
            'values' => $data->pluck('count')->toArray(),
        ];
    }

    // Users methods
    private function getTotalUsers(): int
    {
        return User::count();
    }

    private function getActiveUsers(): int
    {
        return User::where('is_active', true)->count();
    }

    private function getInactiveUsers(): int
    {
        return User::where('is_active', false)->count();
    }

    private function getNewUsers(\Carbon\Carbon $startDate): int
    {
        return User::where('created_at', '>=', $startDate)->count();
    }

    private function getVerifiedUsers(): int
    {
        return User::whereNotNull('email_verified_at')->count();
    }

    // Organizations methods
    private function getTotalOrganizations(): int
    {
        return Organization::count();
    }

    private function getActiveOrganizations(): int
    {
        return Organization::where('is_active', true)->count();
    }

    private function getPaidOrganizations(): int
    {
        return Organization::where('payment_status', 'completed')->count();
    }

    private function getTrialOrganizations(): int
    {
        return Organization::where('is_trial', true)->count();
    }

    private function getNewOrganizations(\Carbon\Carbon $startDate): int
    {
        return Organization::where('created_at', '>=', $startDate)->count();
    }

    private function getSubscriptionsByPlan(): array
    {
        return Organization::select('subscription_plan', DB::raw('count(*) as count'))
            ->groupBy('subscription_plan')
            ->pluck('count', 'subscription_plan')
            ->toArray();
    }

    private function getSubscriptionsByStatus(): array
    {
        return Organization::select('payment_status', DB::raw('count(*) as count'))
            ->groupBy('payment_status')
            ->pluck('count', 'payment_status')
            ->toArray();
    }

    private function getExpiringSoonSubscriptions(): int
    {
        return Organization::where('subscription_ends_at', '<=', now()->addDays(7))
            ->where('subscription_ends_at', '>', now())
            ->count();
    }

    private function getExpiredSubscriptions(): int
    {
        return Organization::where('subscription_ends_at', '<', now())->count();
    }

    // Stores methods
    private function getTotalStores(): int
    {
        return Store::count();
    }

    private function getActiveStores(): int
    {
        return Store::where('is_active', true)->count();
    }

    // Sales methods
    private function getTotalSalesAmount(): float
    {
        return Sale::sum('total');
    }

    private function getPeriodSalesAmount(\Carbon\Carbon $startDate): float
    {
        return Sale::where('created_at', '>=', $startDate)->sum('total');
    }

    private function getTotalSalesCount(): int
    {
        return Sale::count();
    }

    // Products methods
    private function getTotalProducts(): int
    {
        return Product::count();
    }

    // Roles methods
    private function getTotalRoles(): int
    {
        return Role::count();
    }

    // Subscription revenue methods
    private function getTotalSubscriptionRevenue(): float
    {
        return SubscriptionPayment::where('status', 'completed')->sum('amount');
    }

    private function getPeriodSubscriptionRevenue(\Carbon\Carbon $startDate): float
    {
        return SubscriptionPayment::where('status', 'completed')
            ->where('created_at', '>=', $startDate)
            ->sum('amount');
    }
}
