<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Store;
use App\Models\Sale;
use App\Models\StockMovement;
use Illuminate\Console\Command;

class FixOrphanStoreData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'store:fix-orphans 
                            {--store= : Target store ID (default: main store)}
                            {--dry-run : Show what would be fixed without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix products, sales, and stock movements without store assignment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $targetStoreId = $this->option('store');

        $this->info('🔧 CORRECTION DES DONNÉES SANS MAGASIN');
        $this->newLine();

        // Obtenir le magasin cible
        if ($targetStoreId) {
            $targetStore = Store::find($targetStoreId);
            if (!$targetStore) {
                $this->error("❌ Magasin ID {$targetStoreId} introuvable.");
                return 1;
            }
        } else {
            $targetStore = Store::where('is_main', true)->first();
            if (!$targetStore) {
                $targetStore = Store::first();
            }
            
            if (!$targetStore) {
                $this->error('❌ Aucun magasin trouvé dans le système.');
                return 1;
            }
        }

        $this->info("🏪 Magasin cible: {$targetStore->name} (ID: {$targetStore->id})");
        $this->newLine();

        if ($dryRun) {
            $this->warn('⚠️  MODE DRY-RUN: Aucune modification ne sera effectuée');
            $this->newLine();
        }

        // Compter les données orphelines
        $orphanProducts = Product::whereNull('store_id')->count();
        $orphanSales = Sale::whereNull('store_id')->count();
        $orphanMovements = StockMovement::whereNull('store_id')->count();

        if ($orphanProducts === 0 && $orphanSales === 0 && $orphanMovements === 0) {
            $this->info('✅ Aucune donnée orpheline trouvée. Tout est en ordre !');
            return 0;
        }

        // Afficher le résumé
        $this->table(
            ['Type de Données', 'Nombre sans Magasin'],
            [
                ['Produits', $orphanProducts],
                ['Ventes', $orphanSales],
                ['Mouvements de Stock', $orphanMovements],
            ]
        );
        $this->newLine();

        // Confirmer l'action
        if (!$dryRun) {
            if (!$this->confirm("Voulez-vous assigner ces données au magasin '{$targetStore->name}' ?")) {
                $this->info('❌ Opération annulée.');
                return 0;
            }
            $this->newLine();
        }

        // Corriger les produits
        if ($orphanProducts > 0) {
            $this->fixProducts($targetStore, $dryRun);
        }

        // Corriger les ventes
        if ($orphanSales > 0) {
            $this->fixSales($targetStore, $dryRun);
        }

        // Corriger les mouvements de stock
        if ($orphanMovements > 0) {
            $this->fixStockMovements($targetStore, $dryRun);
        }

        $this->newLine();
        if ($dryRun) {
            $this->info('✅ Aperçu terminé. Exécutez sans --dry-run pour appliquer les corrections.');
        } else {
            $this->info('✅ Toutes les corrections ont été appliquées avec succès !');
        }

        return 0;
    }

    /**
     * Fix products without store
     */
    private function fixProducts(Store $targetStore, bool $dryRun)
    {
        $products = Product::whereNull('store_id')->get();
        
        $this->info("📦 Correction de {$products->count()} produit(s)...");
        
        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        foreach ($products as $product) {
            if (!$dryRun) {
                $product->update(['store_id' => $targetStore->id]);
            }
            
            if ($this->option('verbose')) {
                $this->newLine();
                $this->line("  ✓ {$product->reference} | {$product->name}");
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    /**
     * Fix sales without store
     */
    private function fixSales(Store $targetStore, bool $dryRun)
    {
        $sales = Sale::whereNull('store_id')->get();
        
        $this->info("💰 Correction de {$sales->count()} vente(s)...");
        
        $bar = $this->output->createProgressBar($sales->count());
        $bar->start();

        foreach ($sales as $sale) {
            if (!$dryRun) {
                $sale->update(['store_id' => $targetStore->id]);
            }
            
            if ($this->option('verbose')) {
                $this->newLine();
                $this->line("  ✓ {$sale->sale_number} | " . number_format($sale->total, 2) . " FC");
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    /**
     * Fix stock movements without store
     */
    private function fixStockMovements(Store $targetStore, bool $dryRun)
    {
        $movements = StockMovement::whereNull('store_id')->get();
        
        $this->info("📊 Correction de {$movements->count()} mouvement(s) de stock...");
        
        $bar = $this->output->createProgressBar($movements->count());
        $bar->start();

        foreach ($movements as $movement) {
            if (!$dryRun) {
                $movement->update(['store_id' => $targetStore->id]);
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }
}
