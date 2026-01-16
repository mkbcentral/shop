<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Invoice;
use App\Models\StockMovement;
use App\Models\Store;
use Illuminate\Database\Seeder;

class MigrateDataToMainStoreSeeder extends Seeder
{
    public function run(): void
    {
        $mainStore = Store::where('is_main', true)->first();

        if (!$mainStore) {
            $this->command->warn('⚠️  Aucun magasin principal trouvé');
            return;
        }

        // Assigner tous les produits au magasin principal
        Product::whereNull('store_id')->update(['store_id' => $mainStore->id]);
        $productsCount = Product::where('store_id', $mainStore->id)->count();

        // Assigner toutes les ventes au magasin principal
        Sale::whereNull('store_id')->update(['store_id' => $mainStore->id]);
        $salesCount = Sale::where('store_id', $mainStore->id)->count();

        // Assigner tous les achats au magasin principal
        Purchase::whereNull('store_id')->update(['store_id' => $mainStore->id]);
        $purchasesCount = Purchase::where('store_id', $mainStore->id)->count();

        // Assigner toutes les factures au magasin principal
        Invoice::whereNull('store_id')->update(['store_id' => $mainStore->id]);
        $invoicesCount = Invoice::where('store_id', $mainStore->id)->count();

        // Assigner tous les mouvements de stock au magasin principal
        StockMovement::whereNull('store_id')->update(['store_id' => $mainStore->id]);
        $movementsCount = StockMovement::where('store_id', $mainStore->id)->count();

        $this->command->info("✅ Migration des données vers {$mainStore->name}:");
        $this->command->info("   - {$productsCount} produits");
        $this->command->info("   - {$salesCount} ventes");
        $this->command->info("   - {$purchasesCount} achats");
        $this->command->info("   - {$invoicesCount} factures");
        $this->command->info("   - {$movementsCount} mouvements de stock");
    }
}
