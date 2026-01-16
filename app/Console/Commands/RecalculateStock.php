<?php

namespace App\Console\Commands;

use App\Models\ProductVariant;
use App\Models\StockMovement;
use App\Models\StoreStock;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RecalculateStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:recalculate {--variant= : ID du variant spécifique à recalculer}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalcule le stock de tous les variants basé sur les mouvements de stock';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $variantId = $this->option('variant');

        if ($variantId) {
            $this->recalculateVariant($variantId);
        } else {
            $this->recalculateAll();
        }

        $this->info('Stock recalculé avec succès!');
        return Command::SUCCESS;
    }

    private function recalculateVariant(int $variantId): void
    {
        $variant = ProductVariant::find($variantId);
        if (!$variant) {
            $this->error("Variant {$variantId} non trouvé");
            return;
        }

        $this->info("Recalcul du stock pour le variant {$variantId}...");

        // Calculer le stock par store
        $stockByStore = StockMovement::where('product_variant_id', $variantId)
            ->whereNotNull('store_id')
            ->select('store_id')
            ->selectRaw("SUM(CASE WHEN type = 'in' THEN quantity ELSE 0 END) as total_in")
            ->selectRaw("SUM(CASE WHEN type = 'out' THEN quantity ELSE 0 END) as total_out")
            ->groupBy('store_id')
            ->get();

        $totalStock = 0;

        foreach ($stockByStore as $stock) {
            $quantity = $stock->total_in - $stock->total_out;
            $totalStock += $quantity;

            StoreStock::updateOrCreate(
                [
                    'store_id' => $stock->store_id,
                    'product_variant_id' => $variantId,
                ],
                [
                    'quantity' => $quantity,
                ]
            );

            $this->line("  Store {$stock->store_id}: {$quantity} unités");
        }

        // Mettre à jour le stock total du variant
        $variant->update(['stock_quantity' => $totalStock]);
        $this->info("  Total: {$totalStock} unités");
    }

    private function recalculateAll(): void
    {
        $variants = ProductVariant::withoutGlobalScopes()->get();
        $bar = $this->output->createProgressBar($variants->count());
        $bar->start();

        foreach ($variants as $variant) {
            $this->recalculateVariantSilent($variant->id);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    private function recalculateVariantSilent(int $variantId): void
    {
        // Calculer le stock par store
        $stockByStore = StockMovement::where('product_variant_id', $variantId)
            ->whereNotNull('store_id')
            ->select('store_id')
            ->selectRaw("SUM(CASE WHEN type = 'in' THEN quantity ELSE 0 END) as total_in")
            ->selectRaw("SUM(CASE WHEN type = 'out' THEN quantity ELSE 0 END) as total_out")
            ->groupBy('store_id')
            ->get();

        $totalStock = 0;

        foreach ($stockByStore as $stock) {
            $quantity = $stock->total_in - $stock->total_out;
            $totalStock += $quantity;

            StoreStock::updateOrCreate(
                [
                    'store_id' => $stock->store_id,
                    'product_variant_id' => $variantId,
                ],
                [
                    'quantity' => $quantity,
                ]
            );
        }

        // Mettre à jour le stock total du variant
        ProductVariant::withoutGlobalScopes()
            ->where('id', $variantId)
            ->update(['stock_quantity' => $totalStock]);
    }
}
