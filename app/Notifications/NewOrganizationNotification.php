<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notification envoyée aux super-admins lors de la création d'une nouvelle organisation
 */
class NewOrganizationNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Organization $organization,
        public User $owner
    ) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification for database.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_organization',
            'organization_id' => $this->organization->id,
            'organization_name' => $this->organization->name,
            'organization_plan' => $this->organization->subscription_plan->value,
            'organization_plan_label' => $this->organization->subscription_plan->label(),
            'owner_id' => $this->owner->id,
            'owner_name' => $this->owner->name,
            'owner_email' => $this->owner->email,
            'business_activity' => $this->organization->business_activity,
            'payment_status' => $this->organization->payment_status->value,
            'message' => $this->getMessage(),
            'icon' => 'building-office',
            'color' => 'indigo',
            'created_at' => now()->toIso8601String(),
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $planLabel = $this->organization->subscription_plan->label();

        return (new MailMessage)
            ->subject("Nouvelle organisation créée: {$this->organization->name}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Une nouvelle organisation vient d'être créée sur la plateforme.")
            ->line("**Organisation:** {$this->organization->name}")
            ->line("**Propriétaire:** {$this->owner->name} ({$this->owner->email})")
            ->line("**Plan souscrit:** {$planLabel}")
            ->line("**Activité:** " . ucfirst($this->organization->business_activity ?? 'Non spécifiée'))
            ->action('Voir les organisations', route('organizations.index'))
            ->salutation('L\'équipe STK');
    }

    /**
     * Generate the notification message
     */
    private function getMessage(): string
    {
        $planLabel = $this->organization->subscription_plan->label();
        return "Nouvelle organisation \"{$this->organization->name}\" créée par {$this->owner->name} (Plan: {$planLabel})";
    }
}
