<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\SalesNotificationService;
use Illuminate\Console\Command;

class SendDailySalesReport extends Command
{
    protected $signature = 'sales:notify-daily';
    protected $description = 'Envoie un rapport journalier des ventes aux managers';

    public function __construct(
        private SalesNotificationService $notificationService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Envoi des rapports journaliers de ventes...');

        $this->notificationService->notifyAllStoresDailySales();

        $this->info('Rapports journaliers envoyés avec succès!');

        return Command::SUCCESS;
    }
}
