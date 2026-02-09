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
    public bool $isSuperAdmin = false;

    public function getListeners(): array
    {
        $listeners = [
            'refresh-sales-notifications' => 'refreshNotifications',
        ];

        // Ajouter le listener Echo seulement si organizationId est défini
        if ($this->organizationId) {
            $listeners["echo-private:organization.{$this->organizationId},sale.completed"] = 'onSaleCompleted';
        }

        return $listeners;
    }

    public function mount(): void
    {
        $user = auth()->user();

        // Vérifier si c'est un super-admin (ils n'ont pas besoin des notifications de ventes)
        $this->isSuperAdmin = $user?->hasRole('super-admin') ?? false;

        if ($this->isSuperAdmin) {
            $this->notifications = collect();
            $this->unreadCount = 0;
            return;
        }

        // Get current organization ID from the app container (avec gestion d'erreur pour super-admin)
        try {
            $organization = app()->bound('current_organization') ? app('current_organization') : null;
        } catch (\Exception $e) {
            $organization = null;
        }
        $this->organizationId = $organization?->id;

        $this->loadNotifications();
    }

    public function loadNotifications(): void
    {
        // Super-admin n'a pas besoin des notifications de ventes
        if ($this->isSuperAdmin) {
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

    public function onSaleCompleted(): void
    {
        $this->loadNotifications();
    }

    public function refreshNotifications(): void
    {
        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.notifications.sales-notification-bell');
    }
}
