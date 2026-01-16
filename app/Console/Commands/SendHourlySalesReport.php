<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\SalesNotificationService;
use Illuminate\Console\Command;

class SendHourlySalesReport extends Command
{
    protected $signature = 'sales:notify-hourly';
    protected $description = 'Envoie un rapport horaire des ventes aux managers';

    public function __construct(
        private SalesNotificationService $notificationService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Envoi des rapports horaires de ventes...');

        $this->notificationService->notifyAllStoresHourlySales();

        $this->info('Rapports horaires envoyés avec succès!');

        return Command::SUCCESS;
    }
}
