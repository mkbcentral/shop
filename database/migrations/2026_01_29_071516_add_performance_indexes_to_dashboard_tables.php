<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add performance indexes for dashboard queries optimization
     */
    public function up(): void
    {
        // Index for sales queries filtering by date, store and status
        Schema::table('sales', function (Blueprint $table) {
            if (!$this->indexExists('sales', 'idx_sales_date_store_status')) {
                $table->index(['sale_date', 'store_id', 'status'], 'idx_sales_date_store_status');
            }
        });

        // Index for products filtering by store
        Schema::table('products', function (Blueprint $table) {
            if (!$this->indexExists('products', 'idx_products_store')) {
                $table->index(['store_id'], 'idx_products_store');
            }
        });

        // Index for product variants stock queries
        Schema::table('product_variants', function (Blueprint $table) {
            if (!$this->indexExists('product_variants', 'idx_variants_stock_threshold')) {
                $table->index(['stock_quantity', 'low_stock_threshold'], 'idx_variants_stock_threshold');
            }
        });

        // Index for product_store_stock queries
        if (Schema::hasTable('product_store_stock')) {
            Schema::table('product_store_stock', function (Blueprint $table) {
                if (!$this->indexExists('product_store_stock', 'idx_store_stock_variant_store')) {
                    $table->index(['product_variant_id', 'store_id'], 'idx_store_stock_variant_store');
                }
                if (!$this->indexExists('product_store_stock', 'idx_store_stock_quantity')) {
                    $table->index(['quantity'], 'idx_store_stock_quantity');
                }
            });
        }

        // Index for sale_items for top products queries
        Schema::table('sale_items', function (Blueprint $table) {
            if (!$this->indexExists('sale_items', 'idx_sale_items_variant')) {
                $table->index(['product_variant_id', 'sale_id'], 'idx_sale_items_variant');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex('idx_sales_date_store_status');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_store');
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropIndex('idx_variants_stock_threshold');
        });

        if (Schema::hasTable('product_store_stock')) {
            Schema::table('product_store_stock', function (Blueprint $table) {
                $table->dropIndex('idx_store_stock_variant_store');
                $table->dropIndex('idx_store_stock_quantity');
            });
        }

        Schema::table('sale_items', function (Blueprint $table) {
            $table->dropIndex('idx_sale_items_variant');
        });
    }

    /**
     * Check if an index exists on a table
     */
    private function indexExists(string $table, string $index): bool
    {
        $connection = Schema::getConnection();
        $databaseName = $connection->getDatabaseName();

        $result = $connection->select(
            "SELECT COUNT(*) as count FROM information_schema.statistics
             WHERE table_schema = ? AND table_name = ? AND index_name = ?",
            [$databaseName, $table, $index]
        );

        return !empty($result) && $result[0]->count > 0;
    }
};
