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
     * Nombre de jours restants
     */
    private int $daysRemaining;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Organization $organization,
        ?int $daysRemaining = null
    ) {
        $this->daysRemaining = $daysRemaining ?? $this->organization->remaining_days ?? 0;
    }

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
        $urgencyEmoji = $this->getUrgencyEmoji();
        $subject = $this->getSubject($planLabel, $urgencyEmoji);

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting("Bonjour {$notifiable->name},");

        if ($this->daysRemaining === 0) {
            // Expire aujourd'hui
            $mail->line("**⚠️ ATTENTION : Votre abonnement {$planLabel} pour l'organisation \"{$this->organization->name}\" expire AUJOURD'HUI !**")
                ->line('')
                ->line('**Que se passe-t-il si vous ne renouvelez pas ?**')
                ->line('- Votre accès sera **suspendu dès demain**')
                ->line('- Vous ne pourrez plus gérer vos produits, ventes et stocks')
                ->line('- Vos données seront conservées pendant 30 jours');
        } elseif ($this->daysRemaining === 1) {
            // Expire demain
            $mail->line("**Votre abonnement {$planLabel} pour l'organisation \"{$this->organization->name}\" expire DEMAIN !**")
                ->line('')
                ->line('**Que se passe-t-il après expiration ?**')
                ->line('- Votre accès sera suspendu')
                ->line('- Vous ne pourrez plus gérer vos produits, ventes et stocks')
                ->line('- Vos données seront conservées pendant 30 jours');
        } elseif ($this->daysRemaining <= 3) {
            // Expire dans 2-3 jours
            $mail->line("Votre abonnement {$planLabel} pour l'organisation \"{$this->organization->name}\" expire dans **{$this->daysRemaining} jours**.")
                ->line('')
                ->line('**N\'attendez pas le dernier moment !**')
                ->line('Renouvelez maintenant pour éviter toute interruption de service.');
        } else {
            // Expire dans 4-7 jours
            $mail->line("Votre abonnement {$planLabel} pour l'organisation \"{$this->organization->name}\" expire dans **{$this->daysRemaining} jours** (le {$this->organization->subscription_ends_at->format('d/m/Y')}).")
                ->line('')
                ->line('Pensez à renouveler votre abonnement pour continuer à profiter de toutes les fonctionnalités.');
        }

        $mail->line('')
            ->line('**Détails de votre abonnement actuel :**')
            ->line("- Plan : {$planLabel}")
            ->line("- Date d'expiration : {$this->organization->subscription_ends_at->format('d/m/Y à H:i')}")
            ->line("- Magasins : {$this->organization->stores()->count()}/{$this->organization->max_stores}")
            ->line("- Utilisateurs : {$this->organization->members()->count()}/{$this->organization->max_users}")
            ->line('')
            ->action('Renouveler maintenant', url('/organizations/' . $this->organization->slug . '/subscription'))
            ->line('Merci de votre confiance !');

        return $mail;
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
            'days_remaining' => $this->daysRemaining,
            'expires_at' => $this->organization->subscription_ends_at->toIso8601String(),
            'message' => $this->buildSummaryMessage(),
            'urgency' => $this->getUrgencyLevel(),
        ];
    }

    /**
     * Build summary message for database notification
     */
    private function buildSummaryMessage(): string
    {
        if ($this->daysRemaining === 0) {
            return "⚠️ L'abonnement de {$this->organization->name} expire AUJOURD'HUI !";
        } elseif ($this->daysRemaining === 1) {
            return "L'abonnement de {$this->organization->name} expire DEMAIN !";
        } else {
            return "L'abonnement de {$this->organization->name} expire dans {$this->daysRemaining} jours.";
        }
    }

    /**
     * Get urgency level
     */
    private function getUrgencyLevel(): string
    {
        if ($this->daysRemaining === 0) {
            return 'critical';
        } elseif ($this->daysRemaining <= 1) {
            return 'high';
        } elseif ($this->daysRemaining <= 3) {
            return 'medium';
        }
        return 'low';
    }

    /**
     * Get urgency emoji
     */
    private function getUrgencyEmoji(): string
    {
        if ($this->daysRemaining === 0) {
            return '🚨';
        } elseif ($this->daysRemaining <= 1) {
            return '⚠️';
        } elseif ($this->daysRemaining <= 3) {
            return '⏰';
        }
        return '📅';
    }

    /**
     * Get email subject
     */
    private function getSubject(string $planLabel, string $emoji): string
    {
        if ($this->daysRemaining === 0) {
            return "{$emoji} URGENT : Votre abonnement {$planLabel} expire AUJOURD'HUI";
        } elseif ($this->daysRemaining === 1) {
            return "{$emoji} Votre abonnement {$planLabel} expire DEMAIN";
        } elseif ($this->daysRemaining <= 3) {
            return "{$emoji} Votre abonnement {$planLabel} expire dans {$this->daysRemaining} jours";
        }
        return "{$emoji} Rappel : Votre abonnement {$planLabel} expire bientôt";
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

        $plan = $this->organization->subscription_plan;
        
        // Si c'est un enum, obtenir la valeur
        if ($plan instanceof \BackedEnum) {
            $planValue = $plan->value;
        } elseif (is_object($plan) && property_exists($plan, 'value')) {
            $planValue = $plan->value;
        } else {
            $planValue = (string) $plan;
        }

        return $labels[$planValue] ?? $planValue;
    }
}
