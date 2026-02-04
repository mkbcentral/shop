<?php

namespace App\Console\Commands;

use App\Services\SubscriptionService;
use Illuminate\Console\Command;

class CheckSubscriptionLimits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:check-limits 
                            {--threshold=80 : Pourcentage seuil pour déclencher l\'alerte (défaut: 80%)}
                            {--notify : Envoyer les notifications aux propriétaires}
                            {--dry-run : Afficher ce qui serait notifié sans envoyer}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifie les organisations qui atteignent les limites de leur abonnement';

    /**
     * Execute the console command.
     */
    public function handle(SubscriptionService $subscriptionService): int
    {
        $threshold = (int) $this->option('threshold');
        $shouldNotify = $this->option('notify');
        $isDryRun = $this->option('dry-run');

        $this->info("🔍 Vérification des limites d'abonnement (seuil: {$threshold}%)...");
        $this->newLine();

        // Récupérer les organisations approchant leurs limites
        $organizationsNearLimit = $subscriptionService->getOrganizationsNearLimits($threshold);

        if ($organizationsNearLimit->isEmpty()) {
            $this->info('✅ Aucune organisation n\'approche ses limites d\'abonnement.');
            return Command::SUCCESS;
        }

        $this->info("⚠️ Organisations approchant leurs limites : {$organizationsNearLimit->count()}");
        $this->newLine();

        // Afficher le tableau des organisations
        $tableData = [];
        foreach ($organizationsNearLimit as $orgData) {
            $org = $orgData['organization'];
            $limits = $orgData['reaching_limits'];
            
            $limitSummary = [];
            foreach ($limits as $type => $data) {
                $limitSummary[] = "{$type}: {$data['current']}/{$data['max']} ({$data['percentage']}%)";
            }

            $tableData[] = [
                $org->name,
                $org->subscription_plan instanceof \App\Enums\SubscriptionPlan 
                    ? $org->subscription_plan->label() 
                    : $org->subscription_plan,
                $org->owner?->email ?? 'N/A',
                implode("\n", $limitSummary),
            ];
        }

        $this->table(
            ['Organisation', 'Plan', 'Email propriétaire', 'Limites atteintes'],
            $tableData
        );

        // Envoi des notifications
        if ($shouldNotify || $isDryRun) {
            $this->newLine();
            
            if ($isDryRun) {
                $this->info('🔄 Mode dry-run : Notifications qui seraient envoyées :');
                foreach ($organizationsNearLimit as $orgData) {
                    $org = $orgData['organization'];
                    $this->line("  → {$org->name} ({$org->owner?->email})");
                }
            } else {
                $this->info('📧 Envoi des notifications...');
                $count = $subscriptionService->sendLimitReachingNotifications($threshold);
                $this->info("✅ {$count} notification(s) envoyée(s)");
            }
        } else {
            $this->newLine();
            $this->comment('💡 Utilisez --notify pour envoyer les notifications ou --dry-run pour prévisualiser.');
        }

        $this->newLine();
        $this->info('✅ Vérification terminée !');

        return Command::SUCCESS;
    }
}
