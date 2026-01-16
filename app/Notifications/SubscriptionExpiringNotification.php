<?php

namespace App\Notifications;

use App\Models\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionExpiringNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Organization $organization
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $daysRemaining = $this->organization->remaining_days;
        $planLabel = $this->getPlanLabel();
        $expiryDate = $this->organization->subscription_ends_at->format('d/m/Y');

        return (new MailMessage)
            ->subject("⚠️ Votre abonnement {$planLabel} expire bientôt")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Votre abonnement **{$planLabel}** pour l'organisation **{$this->organization->name}** expire dans **{$daysRemaining} jour(s)** (le {$expiryDate}).")
            ->line('Pour continuer à bénéficier de toutes les fonctionnalités, pensez à renouveler votre abonnement.')
            ->line('**Ce qui se passera après l\'expiration :**')
            ->line('- Votre organisation passera automatiquement au plan Gratuit')
            ->line('- Certaines fonctionnalités seront limitées')
            ->line('- Vos données seront conservées')
            ->action('Renouveler maintenant', url('/organizations/' . $this->organization->slug . '/subscription'))
            ->line('Merci de votre confiance !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'subscription_expiring',
            'organization_id' => $this->organization->id,
            'organization_name' => $this->organization->name,
            'plan' => $this->organization->subscription_plan,
            'expires_at' => $this->organization->subscription_ends_at?->toISOString(),
            'days_remaining' => $this->organization->remaining_days,
            'message' => "L'abonnement {$this->getPlanLabel()} de {$this->organization->name} expire dans {$this->organization->remaining_days} jour(s).",
        ];
    }

    /**
     * Get plan label
     */
    private function getPlanLabel(): string
    {
        $labels = [
            'free' => 'Gratuit',
            'starter' => 'Starter',
            'professional' => 'Professionnel',
            'enterprise' => 'Entreprise',
        ];

        return $labels[$this->organization->subscription_plan] ?? $this->organization->subscription_plan;
    }
}
