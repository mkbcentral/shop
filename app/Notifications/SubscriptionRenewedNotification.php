<?php

namespace App\Notifications;

use App\Models\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionRenewedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Organization $organization,
        public int $durationMonths
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
        $planLabel = $this->getPlanLabel();
        $newEndDate = $this->organization->subscription_ends_at->format('d/m/Y');
        $duration = $this->durationMonths > 1 ? "{$this->durationMonths} mois" : "1 mois";

        return (new MailMessage)
            ->subject("✅ Abonnement {$planLabel} renouvelé avec succès")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Votre abonnement **{$planLabel}** pour l'organisation **{$this->organization->name}** a été renouvelé avec succès !")
            ->line("**Détails du renouvellement :**")
            ->line("- Durée : {$duration}")
            ->line("- Nouvelle date d'expiration : {$newEndDate}")
            ->line('Vous pouvez continuer à profiter de toutes les fonctionnalités de votre plan.')
            ->action('Voir mon abonnement', url('/organizations/' . $this->organization->slug . '/subscription'))
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
            'type' => 'subscription_renewed',
            'organization_id' => $this->organization->id,
            'organization_name' => $this->organization->name,
            'plan' => $this->organization->subscription_plan,
            'duration_months' => $this->durationMonths,
            'expires_at' => $this->organization->subscription_ends_at?->toISOString(),
            'message' => "L'abonnement {$this->getPlanLabel()} de {$this->organization->name} a été renouvelé pour {$this->durationMonths} mois.",
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
