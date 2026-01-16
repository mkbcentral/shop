<?php

namespace App\Notifications;

use App\Models\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionUpgradedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Organization $organization,
        public string $previousPlan,
        public string $newPlan
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
        $oldPlanLabel = $this->getPlanLabel($this->previousPlan);
        $newPlanLabel = $this->getPlanLabel($this->newPlan);
        $limits = $this->getPlanLimits($this->newPlan);

        return (new MailMessage)
            ->subject("🚀 Félicitations ! Vous êtes passé au plan {$newPlanLabel}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Votre organisation **{$this->organization->name}** est maintenant sur le plan **{$newPlanLabel}** !")
            ->line("Vous êtes passé de **{$oldPlanLabel}** à **{$newPlanLabel}**.")
            ->line("**Vos nouvelles limites :**")
            ->line("- Magasins : jusqu'à {$limits['max_stores']}")
            ->line("- Utilisateurs : jusqu'à {$limits['max_users']}")
            ->line("- Produits : jusqu'à " . number_format($limits['max_products'], 0, ',', ' '))
            ->action('Découvrir les fonctionnalités', url('/organizations/' . $this->organization->slug))
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
            'type' => 'subscription_upgraded',
            'organization_id' => $this->organization->id,
            'organization_name' => $this->organization->name,
            'previous_plan' => $this->previousPlan,
            'new_plan' => $this->newPlan,
            'message' => "{$this->organization->name} est passé du plan {$this->getPlanLabel($this->previousPlan)} au plan {$this->getPlanLabel($this->newPlan)}.",
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

    /**
     * Get plan limits
     */
    private function getPlanLimits(string $plan): array
    {
        $limits = [
            'free' => ['max_stores' => 1, 'max_users' => 3, 'max_products' => 100],
            'starter' => ['max_stores' => 3, 'max_users' => 10, 'max_products' => 1000],
            'professional' => ['max_stores' => 10, 'max_users' => 50, 'max_products' => 10000],
            'enterprise' => ['max_stores' => 100, 'max_users' => 500, 'max_products' => 100000],
        ];

        return $limits[$plan] ?? $limits['free'];
    }
}
