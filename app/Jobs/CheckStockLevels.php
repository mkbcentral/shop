<?php

namespace App\Jobs;

use App\Services\StockAlertService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckStockLevels implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(StockAlertService $alertService): void
    {
        $results = $alertService->checkStockLevels();

        Log::info('Stock Levels Check Completed', [
            'low_stock_count' => $results['low_stock_count'],
            'out_of_stock_count' => $results['out_of_stock_count'],
            'timestamp' => now(),
        ]);
    }
}
