<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Store;
use App\Models\Sale;
use App\Models\StockMovement;
use Illuminate\Console\Command;

class AuditStoreData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'store:audit 
                            {--store= : Filter by specific store ID}
                            {--products : Show products audit}
                            {--sales : Show sales audit}
                            {--stock : Show stock movements audit}
                            {--all : Show all audits}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Audit products, sales, and stock by store';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $storeId = $this->option('store');
        $showProducts = $this->option('products');
        $showSales = $this->option('sales');
        $showStock = $this->option('stock');
        $showAll = $this->option('all');

        // Si aucune option n'est spécifiée, afficher tout
        if (!$showProducts && !$showSales && !$showStock && !$showAll) {
            $showAll = true;
        }

        $this->info('🔍 AUDIT DES DONNÉES PAR MAGASIN');
        $this->newLine();

        // Liste des magasins
        $stores = $storeId ? Store::where('id', $storeId)->get() : Store::all();

        if ($stores->isEmpty()) {
            $this->error('❌ Aucun magasin trouvé.');
            return 1;
        }

        // Afficher les magasins
        $this->info('📍 MAGASINS DANS LE SYSTÈME:');
        $storeData = [];
        foreach ($stores as $store) {
            $storeData[] = [
                $store->id,
                $store->name,
                $store->code,
                $store->is_main ? '✓' : '',
                $store->is_active ? '✓' : '',
            ];
        }
        $this->table(['ID', 'Nom', 'Code', 'Principal', 'Actif'], $storeData);
        $this->newLine();

        // Audit des produits
        if ($showProducts || $showAll) {
            $this->auditProducts($stores);
        }

        // Audit des ventes
        if ($showSales || $showAll) {
            $this->auditSales($stores);
        }

        // Audit des mouvements de stock
        if ($showStock || $showAll) {
            $this->auditStockMovements($stores);
        }

        // Vérifier les données sans magasin
        $this->checkOrphanData();

        return 0;
    }

    /**
     * Audit products by store
     */
    private function auditProducts($stores)
    {
        $this->info('📦 AUDIT DES PRODUITS PAR MAGASIN:');

        $productsData = [];
        $totalProducts = 0;

        foreach ($stores as $store) {
            $productCount = Product::where('store_id', $store->id)->count();
            $activeCount = Product::where('store_id', $store->id)->where('status', 'active')->count();
            $inactiveCount = $productCount - $activeCount;
            
            $totalProducts += $productCount;

            $productsData[] = [
                $store->id,
                $store->name,
                $productCount,
                $activeCount,
                $inactiveCount,
            ];
        }

        $this->table(
            ['Store ID', 'Magasin', 'Total Produits', 'Actifs', 'Inactifs'],
            $productsData
        );

        // Résumé
        $this->line("  <fg=cyan>Total produits dans tous les magasins: {$totalProducts}</>");
        $this->newLine();

        // Afficher quelques exemples
        if ($this->option('verbose')) {
            foreach ($stores as $store) {
                $products = Product::where('store_id', $store->id)->limit(5)->get();
                if ($products->count() > 0) {
                    $this->line("  📋 Exemples de produits du magasin '{$store->name}':");
                    foreach ($products as $product) {
                        $this->line("     - {$product->reference} | {$product->name}");
                    }
                    $this->newLine();
                }
            }
        }
    }

    /**
     * Audit sales by store
     */
    private function auditSales($stores)
    {
        $this->info('💰 AUDIT DES VENTES PAR MAGASIN:');

        $salesData = [];
        $totalSales = 0;
        $totalAmount = 0;

        foreach ($stores as $store) {
            $saleCount = Sale::where('store_id', $store->id)->count();
            $saleAmount = Sale::where('store_id', $store->id)->sum('total');
            
            $totalSales += $saleCount;
            $totalAmount += $saleAmount;

            $salesData[] = [
                $store->id,
                $store->name,
                $saleCount,
                number_format($saleAmount, 2) . ' FC',
            ];
        }

        $this->table(
            ['Store ID', 'Magasin', 'Nombre de Ventes', 'Montant Total'],
            $salesData
        );

        $this->line("  <fg=cyan>Total ventes: {$totalSales}</>");
        $this->line("  <fg=cyan>Montant total: " . number_format($totalAmount, 2) . " FC</>");
        $this->newLine();
    }

    /**
     * Audit stock movements by store
     */
    private function auditStockMovements($stores)
    {
        $this->info('📊 AUDIT DES MOUVEMENTS DE STOCK PAR MAGASIN:');

        $stockData = [];
        $totalMovements = 0;

        foreach ($stores as $store) {
            $movementCount = StockMovement::where('store_id', $store->id)->count();
            $entriesCount = StockMovement::where('store_id', $store->id)->where('type', 'in')->count();
            $exitsCount = StockMovement::where('store_id', $store->id)->where('type', 'out')->count();
            
            $totalMovements += $movementCount;

            $stockData[] = [
                $store->id,
                $store->name,
                $movementCount,
                $entriesCount,
                $exitsCount,
            ];
        }

        $this->table(
            ['Store ID', 'Magasin', 'Total Mouvements', 'Entrées', 'Sorties'],
            $stockData
        );

        $this->line("  <fg=cyan>Total mouvements: {$totalMovements}</>");
        $this->newLine();
    }

    /**
     * Check for data without store assignment
     */
    private function checkOrphanData()
    {
        $this->warn('⚠️  VÉRIFICATION DES DONNÉES SANS MAGASIN:');

        $orphanProducts = Product::whereNull('store_id')->count();
        $orphanSales = Sale::whereNull('store_id')->count();
        $orphanMovements = StockMovement::whereNull('store_id')->count();

        $hasOrphans = false;

        if ($orphanProducts > 0) {
            $this->error("  ❌ {$orphanProducts} produit(s) sans magasin assigné");
            $hasOrphans = true;
            
            if ($this->option('verbose')) {
                $products = Product::whereNull('store_id')->limit(5)->get();
                foreach ($products as $product) {
                    $this->line("     - ID: {$product->id} | {$product->name}");
                }
            }
        }

        if ($orphanSales > 0) {
            $this->error("  ❌ {$orphanSales} vente(s) sans magasin assigné");
            $hasOrphans = true;
        }

        if ($orphanMovements > 0) {
            $this->error("  ❌ {$orphanMovements} mouvement(s) de stock sans magasin assigné");
            $hasOrphans = true;
        }

        if (!$hasOrphans) {
            $this->info("  ✅ Toutes les données sont correctement assignées à un magasin");
        } else {
            $this->newLine();
            $this->warn("  💡 Pour corriger, vous pouvez assigner ces données au magasin principal:");
            $this->line("     php artisan store:fix-orphans");
        }

        $this->newLine();
    }
}
