<?php

namespace App\Console\Commands;

use App\Services\SubscriptionService;
use Illuminate\Console\Command;

class CheckExpiringSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:check-expiring 
                            {--days=7 : Nombre de jours avant expiration}
                            {--notify : Envoyer les notifications}
                            {--process-expired : Traiter les abonnements expirés}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifie les abonnements expirants et expirés';

    /**
     * Execute the console command.
     */
    public function handle(SubscriptionService $subscriptionService): int
    {
        $days = (int) $this->option('days');
        $shouldNotify = $this->option('notify');
        $processExpired = $this->option('process-expired');

        $this->info('🔍 Vérification des abonnements...');
        $this->newLine();

        // Afficher les abonnements expirants
        $expiring = $subscriptionService->getExpiringSubscriptions($days);
        
        $this->info("📅 Abonnements expirant dans les {$days} prochains jours : {$expiring->count()}");
        
        if ($expiring->isNotEmpty()) {
            $this->table(
                ['Organisation', 'Plan', 'Expire le', 'Jours restants'],
                $expiring->map(fn ($org) => [
                    $org->name,
                    $org->plan_label ?? $org->subscription_plan->value ?? $org->subscription_plan,
                    $org->subscription_ends_at->format('d/m/Y'),
                    $org->remaining_days,
                ])->toArray()
            );

            if ($shouldNotify) {
                $this->newLine();
                $this->info('📧 Envoi des notifications...');
                $count = $subscriptionService->sendExpiringNotifications($days);
                $this->info("✅ {$count} notification(s) envoyée(s)");
            }
        }

        $this->newLine();

        // Afficher les abonnements expirés
        $expired = $subscriptionService->getExpiredSubscriptions();
        
        $this->info("❌ Abonnements expirés (non traités) : {$expired->count()}");
        
        if ($expired->isNotEmpty()) {
            $this->table(
                ['Organisation', 'Plan', 'Expiré le'],
                $expired->map(fn ($org) => [
                    $org->name,
                    $org->plan_label ?? $org->subscription_plan->value ?? $org->subscription_plan,
                    $org->subscription_ends_at->format('d/m/Y'),
                ])->toArray()
            );

            if ($processExpired) {
                $this->newLine();
                $this->info('⚙️ Traitement des abonnements expirés...');
                $count = $subscriptionService->processExpiredSubscriptions();
                $this->info("✅ {$count} abonnement(s) passé(s) au plan gratuit");
            }
        }

        $this->newLine();
        $this->info('✅ Vérification terminée !');

        return Command::SUCCESS;
    }
}
