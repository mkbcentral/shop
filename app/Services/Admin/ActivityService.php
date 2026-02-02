<?php

namespace App\Services\Admin;

use App\Models\Organization;
use App\Models\SubscriptionPayment;
use App\Models\User;
use Illuminate\Support\Collection;

class ActivityService
{
    public function getRecentActivities(int $limit = 10): Collection
    {
        $activities = collect();

        // Add recent users
        $this->addRecentUsers($activities);

        // Add recent organizations
        $this->addRecentOrganizations($activities);

        // Add recent payments
        $this->addRecentPayments($activities);

        return $activities->sortByDesc('date')->take($limit)->values();
    }

    private function addRecentUsers(Collection $activities, int $limit = 5): void
    {
        User::latest()
            ->take($limit)
            ->get()
            ->each(function ($user) use ($activities) {
                $activities->push([
                    'type' => 'user',
                    'icon' => 'user-plus',
                    'color' => 'blue',
                    'message' => "Nouvel utilisateur: {$user->name}",
                    'detail' => $user->email,
                    'date' => $user->created_at,
                ]);
            });
    }

    private function addRecentOrganizations(Collection $activities, int $limit = 5): void
    {
        Organization::latest()
            ->take($limit)
            ->get()
            ->each(function ($org) use ($activities) {
                $activities->push([
                    'type' => 'organization',
                    'icon' => 'building',
                    'color' => 'purple',
                    'message' => "Nouvelle organisation: {$org->name}",
                    'detail' => $org->subscription_plan ?? 'trial',
                    'date' => $org->created_at,
                ]);
            });
    }

    private function addRecentPayments(Collection $activities, int $limit = 5): void
    {
        SubscriptionPayment::with('organization')
            ->where('status', 'completed')
            ->latest()
            ->take($limit)
            ->get()
            ->each(function ($payment) use ($activities) {
                $currency = $payment->organization?->currency ?? current_currency();
                $amount = number_format($payment->amount, 0, ',', ' ');
                
                $activities->push([
                    'type' => 'payment',
                    'icon' => 'credit-card',
                    'color' => 'green',
                    'message' => "Paiement reçu: {$amount} {$currency}",
                    'detail' => $payment->organization?->name ?? 'N/A',
                    'date' => $payment->created_at,
                ]);
            });
    }
}
