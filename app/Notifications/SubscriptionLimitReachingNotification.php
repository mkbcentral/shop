<?php

namespace App\Notifications;

use App\Models\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionLimitReachingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Les types de limites qui sont atteintes
     */
    private array $reachingLimits;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Organization $organization,
        array $reachingLimits = []
    ) {
        $this->reachingLimits = $reachingLimits;
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
        $limitLines = $this->buildLimitLines();

        $mail = (new MailMessage)
            ->subject("⚠️ Votre abonnement {$planLabel} atteint ses limites")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Votre organisation **{$this->organization->name}** avec le plan **{$planLabel}** atteint certaines de ses limites d'utilisation :");

        foreach ($limitLines as $line) {
            $mail->line($line);
        }

        $mail->line('')
            ->line('**Que se passe-t-il si vous atteignez la limite ?**')
            ->line('- Vous ne pourrez plus ajouter de nouveaux éléments dans les catégories concernées')
            ->line('- Vos données existantes restent accessibles')
            ->line('')
            ->line('**Solution :** Passez à un plan supérieur pour augmenter vos limites et débloquer plus de fonctionnalités.')
            ->action('Mettre à niveau maintenant', url('/organizations/' . $this->organization->slug . '/subscription'))
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
            'type' => 'subscription_limit_reaching',
            'organization_id' => $this->organization->id,
            'organization_name' => $this->organization->name,
            'plan' => $this->organization->subscription_plan,
            'reaching_limits' => $this->reachingLimits,
            'message' => $this->buildSummaryMessage(),
        ];
    }

    /**
     * Build limit lines for email
     */
    private function buildLimitLines(): array
    {
        $lines = [];
        $labels = [
            'products' => 'Produits',
            'stores' => 'Magasins',
            'users' => 'Utilisateurs',
        ];

        foreach ($this->reachingLimits as $type => $data) {
            $label = $labels[$type] ?? $type;
            $percentage = $data['percentage'];
            $current = $data['current'];
            $max = $data['max'];
            
            $lines[] = "- **{$label}** : {$current}/{$max} utilisés ({$percentage}%)";
        }

        return $lines;
    }

    /**
     * Build summary message for database notification
     */
    private function buildSummaryMessage(): string
    {
        $limitTypes = array_keys($this->reachingLimits);
        $labels = [
            'products' => 'produits',
            'stores' => 'magasins',
            'users' => 'utilisateurs',
        ];

        $limitLabels = array_map(fn($type) => $labels[$type] ?? $type, $limitTypes);

        return "L'organisation {$this->organization->name} atteint ses limites de : " . implode(', ', $limitLabels);
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
        if (is_object($plan) && method_exists($plan, 'value')) {
            $plan = $plan->value;
        }

        return $labels[$plan] ?? (string) $plan;
    }
}
