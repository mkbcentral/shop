<?php

namespace App\Console\Commands;

use App\Services\SubscriptionService;
use Illuminate\Console\Command;

class SyncOrganizationLimits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'organizations:sync-limits
                            {--dry-run : Afficher les modifications sans les appliquer}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronise les limites des organisations avec leurs plans d\'abonnement';

    /**
     * Execute the console command.
     */
    public function handle(SubscriptionService $subscriptionService): int
    {
        $this->info('🔄 Synchronisation des limites des organisations...');
        $this->newLine();

        $organizations = \App\Models\Organization::all();

        if ($organizations->isEmpty()) {
            $this->warn('Aucune organisation trouvée.');
            return self::SUCCESS;
        }

        $this->table(
            ['ID', 'Organisation', 'Plan', 'Avant (S/U/P)', 'Après (S/U/P)', 'Statut'],
            $organizations->map(function ($org) use ($subscriptionService) {
                $planSlug = $org->subscription_plan->value;
                $newLimits = SubscriptionService::getPlanLimitsFromDatabase($planSlug);

                $before = "{$org->max_stores}/{$org->max_users}/{$org->max_products}";
                $after = "{$newLimits['max_stores']}/{$newLimits['max_users']}/{$newLimits['max_products']}";

                $changed = $before !== $after;
                $status = $changed ? '🔄 À mettre à jour' : '✅ OK';

                return [
                    $org->id,
                    $org->name,
                    $planSlug,
                    $before,
                    $after,
                    $status,
                ];
            })
        );

        if ($this->option('dry-run')) {
            $this->newLine();
            $this->warn('Mode dry-run : aucune modification appliquée.');
            $this->info('Relancez sans --dry-run pour appliquer les modifications.');
            return self::SUCCESS;
        }

        if (!$this->confirm('Voulez-vous appliquer ces modifications ?')) {
            $this->info('Opération annulée.');
            return self::SUCCESS;
        }

        $count = $subscriptionService->syncAllOrganizationsLimits();

        $this->newLine();
        $this->info("✅ {$count} organisation(s) synchronisée(s) avec succès !");

        return self::SUCCESS;
    }
}
