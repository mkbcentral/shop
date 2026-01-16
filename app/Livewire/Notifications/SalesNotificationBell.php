<?php

declare(strict_types=1);

namespace App\Livewire\Notifications;

use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\Attributes\On;

class SalesNotificationBell extends Component
{
    public Collection $notifications;
    public int $unreadCount = 0;
    public ?int $organizationId = null;

    public function mount(): void
    {
        // Get current organization ID from the app container
        $organization = app('current_organization');
        $this->organizationId = $organization?->id;

        $this->loadNotifications();
    }

    public function loadNotifications(): void
    {
        $user = auth()->user();

        if (!$user) {
            $this->notifications = collect();
            $this->unreadCount = 0;
            return;
        }

        $this->notifications = $user->notifications()
            ->whereJsonContains('data->type', 'sales_report')
            ->latest()
            ->take(20)
            ->get();

        $this->unreadCount = $user->unreadNotifications()
            ->whereJsonContains('data->type', 'sales_report')
            ->count();
    }

    public function markAsRead(string $notificationId): void
    {
        $notification = auth()->user()->notifications()->find($notificationId);

        if ($notification) {
            $notification->markAsRead();
            $this->loadNotifications();
        }
    }

    public function markAllAsRead(): void
    {
        auth()->user()->unreadNotifications()
            ->whereJsonContains('data->type', 'sales_report')
            ->update(['read_at' => now()]);

        $this->loadNotifications();
    }

    public function clearAll(): void
    {
        auth()->user()->notifications()
            ->whereJsonContains('data->type', 'sales_report')
            ->delete();

        $this->loadNotifications();
    }

    #[On('echo-private:organization.{organizationId},sale.completed')]
    public function onSaleCompleted(): void
    {
        $this->loadNotifications();
    }

    #[On('refresh-sales-notifications')]
    public function refreshNotifications(): void
    {
        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.notifications.sales-notification-bell');
    }
}
