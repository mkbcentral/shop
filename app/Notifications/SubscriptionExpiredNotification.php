<?php

namespace App\Notifications;

use App\Models\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionExpiredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Organization $organization,
        public string $previousPlan
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
        $planLabel = $this->getPlanLabel($this->previousPlan);

        return (new MailMessage)
            ->subject("❌ Votre abonnement {$planLabel} a expiré")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Votre abonnement **{$planLabel}** pour l'organisation **{$this->organization->name}** a expiré.")
            ->line('Votre organisation est maintenant sur le **plan Gratuit** avec les limitations suivantes :')
            ->line('- Maximum 1 magasin')
            ->line('- Maximum 3 utilisateurs')
            ->line('- Maximum 100 produits')
            ->line('**Vos données ont été conservées.** Réactivez votre abonnement pour récupérer toutes les fonctionnalités.')
            ->action('Réactiver mon abonnement', url('/organizations/' . $this->organization->slug . '/subscription'))
            ->line('Besoin d\'aide ? Contactez notre support.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'subscription_expired',
            'organization_id' => $this->organization->id,
            'organization_name' => $this->organization->name,
            'previous_plan' => $this->previousPlan,
            'new_plan' => 'free',
            'message' => "L'abonnement {$this->getPlanLabel($this->previousPlan)} de {$this->organization->name} a expiré. L'organisation est passée au plan Gratuit.",
        ];
    }

    /**
     * Get plan label
     */
    private function getPlanLabel(string $plan): string
    {
        $labels = [
            'free' => 'Gratuit',
            'starter' => 'Starter',
            'professional' => 'Professionnel',
            'enterprise' => 'Entreprise',
        ];

        return $labels[$plan] ?? $plan;
    }
}
