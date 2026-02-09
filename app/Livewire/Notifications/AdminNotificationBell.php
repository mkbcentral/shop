<?php

declare(strict_types=1);

namespace App\Livewire\Notifications;

use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\Attributes\On;

/**
 * Composant de notifications pour les super-admins
 * Affiche les notifications de nouvelles organisations créées
 */
class AdminNotificationBell extends Component
{
    public Collection $notifications;
    public int $unreadCount = 0;
    public bool $isSuperAdmin = false;

    public function getListeners(): array
    {
        return [
            'refresh-admin-notifications' => 'refreshNotifications',
            'echo-private:admin-notifications,new.organization' => 'onNewOrganization',
        ];
    }

    public function mount(): void
    {
        $user = auth()->user();

        // Ce composant est réservé aux super-admins
        $this->isSuperAdmin = $user?->hasRole('super-admin') ?? false;

        if (!$this->isSuperAdmin) {
            $this->notifications = collect();
            $this->unreadCount = 0;
            return;
        }

        $this->loadNotifications();
    }

    public function loadNotifications(): void
    {
        if (!$this->isSuperAdmin) {
            $this->notifications = collect();
            $this->unreadCount = 0;
            return;
        }

        $user = auth()->user();

        if (!$user) {
            $this->notifications = collect();
            $this->unreadCount = 0;
            return;
        }

        // Récupérer les notifications de type new_organization
        $this->notifications = $user->notifications()
            ->whereJsonContains('data->type', 'new_organization')
            ->latest()
            ->take(20)
            ->get();

        $this->unreadCount = $user->unreadNotifications()
            ->whereJsonContains('data->type', 'new_organization')
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
            ->whereJsonContains('data->type', 'new_organization')
            ->update(['read_at' => now()]);

        $this->loadNotifications();
    }

    public function clearAll(): void
    {
        auth()->user()->notifications()
            ->whereJsonContains('data->type', 'new_organization')
            ->delete();

        $this->loadNotifications();
    }

    public function onNewOrganization(): void
    {
        $this->loadNotifications();
    }

    public function refreshNotifications(): void
    {
        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.notifications.admin-notification-bell');
    }
}
