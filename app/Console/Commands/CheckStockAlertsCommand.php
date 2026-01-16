<?php

namespace App\Console\Commands;

use App\Services\StockAlertService;
use Illuminate\Console\Command;

class CheckStockAlertsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:check-alerts
                            {--summary : Display summary only}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check stock levels and trigger alerts for low/out of stock items';

    /**
     * Execute the console command.
     */
    public function handle(StockAlertService $alertService): int
    {
        $this->info('Checking stock levels...');

        $results = $alertService->checkStockLevels();

        if ($this->option('summary')) {
            $this->displaySummary($results);
        } else {
            $this->displayDetailed($results);
        }

        if ($results['low_stock_count'] > 0 || $results['out_of_stock_count'] > 0) {
            $this->warn("Total alerts: " . ($results['low_stock_count'] + $results['out_of_stock_count']));
            return Command::FAILURE;
        }

        $this->info('All stock levels are normal.');
        return Command::SUCCESS;
    }

    private function displaySummary(array $results): void
    {
        $this->table(
            ['Alert Type', 'Count'],
            [
                ['Low Stock', $results['low_stock_count']],
                ['Out of Stock', $results['out_of_stock_count']],
            ]
        );
    }

    private function displayDetailed(array $results): void
    {
        if ($results['low_stock_count'] > 0) {
            $this->warn("\nLow Stock Items ({$results['low_stock_count']}):");
            $this->table(
                ['ID', 'Product', 'Variant', 'Current Stock', 'Threshold'],
                $results['low_stock_variants']->map(fn($v) => [
                    $v->id,
                    $v->product->name,
                    $v->full_name,
                    $v->stock_quantity,
                    $v->low_stock_threshold,
                ])->toArray()
            );
        }

        if ($results['out_of_stock_count'] > 0) {
            $this->error("\nOut of Stock Items ({$results['out_of_stock_count']}):");
            $this->table(
                ['ID', 'Product', 'Variant', 'Current Stock'],
                $results['out_of_stock_variants']->map(fn($v) => [
                    $v->id,
                    $v->product->name,
                    $v->full_name,
                    $v->stock_quantity,
                ])->toArray()
            );
        }
    }
}
