<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\StoreStock;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class StoreStockSeeder extends Seeder
{
    public function run(): void
    {
        $mainStore = Store::where('is_main', true)->first();

        if (!$mainStore) {
            $this->command->warn('⚠️  Aucun magasin principal trouvé');
            return;
        }

        // Migrer les stocks des product_variants vers store_stock
        $variants = ProductVariant::all();

        foreach ($variants as $variant) {
            StoreStock::create([
                'store_id' => $mainStore->id,
                'product_variant_id' => $variant->id,
                'quantity' => $variant->stock_quantity ?? 0,
                'low_stock_threshold' => $variant->low_stock_threshold ?? 10,
                'min_stock_threshold' => $variant->min_stock_threshold ?? 0,
            ]);
        }

        $this->command->info("✅ {$variants->count()} stocks migrés vers le magasin principal");
    }
}
