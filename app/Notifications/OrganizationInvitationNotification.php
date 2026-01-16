<?php

namespace App\Notifications;

use App\Models\Organization;
use App\Models\OrganizationInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrganizationInvitationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public OrganizationInvitation $invitation,
        public Organization $organization
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $showUrl = route('organization.invitation.show', $this->invitation->token);

        return (new MailMessage)
            ->subject("Invitation à rejoindre {$this->organization->name}")
            ->greeting("Bonjour !")
            ->line("Vous avez été invité(e) à rejoindre {$this->organization->name} en tant que {$this->invitation->role}.")
            ->line("Cette invitation expire le {$this->invitation->expires_at->format('d/m/Y')}.")
            ->action('Voir l\'invitation', $showUrl)
            ->line('Si vous n\'attendiez pas cette invitation, vous pouvez ignorer cet email.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'organization_id' => $this->organization->id,
            'organization_name' => $this->organization->name,
            'role' => $this->invitation->role,
            'invitation_token' => $this->invitation->token,
        ];
    }
}
