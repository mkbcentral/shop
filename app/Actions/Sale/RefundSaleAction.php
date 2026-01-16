<?php

namespace App\Actions\Sale;

use App\Services\SaleService;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;

class RefundSaleAction
{
    public function __construct(
        private SaleService $saleService,
        private StockService $stockService
    ) {}

    /**
     * Refund a sale and restore stock.
     */
    public function execute(int $saleId, array $data): array
    {
        return DB::transaction(function () use ($saleId, $data) {
            $sale = $this->saleService->findById($saleId);

            if (!$sale) {
                throw new \Exception("Sale not found");
            }

            if ($sale->status !== 'completed') {
                throw new \Exception("Only completed sales can be refunded");
            }

            // Restore stock for each item
            foreach ($sale->items as $item) {
                $this->stockService->returnStock(
                    $item->product_variant_id,
                    $item->quantity,
                    $data['user_id'] ?? auth()->id(),
                    $data['reason'] ?? 'Sale refund',
                    $sale->id
                );
            }

            // Update sale status
            $sale->payment_status = 'refunded';
            $sale->status = 'cancelled';
            $sale->notes = ($sale->notes ? $sale->notes . "\n" : '') .
                          "Refunded on " . now()->format('Y-m-d H:i:s') .
                          "\nReason: " . ($data['reason'] ?? 'No reason provided');
            $sale->save();

            // Update invoice if exists
            if ($sale->invoice) {
                $sale->invoice->status = 'cancelled';
                $sale->invoice->save();
            }

            return [
                'sale' => $sale->fresh(),
                'message' => 'Sale refunded successfully',
            ];
        });
    }
}
