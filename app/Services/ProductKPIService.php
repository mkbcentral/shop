<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ProductKPIService
{
    /**
     * Get the current organization ID for filtering
     */
    protected function getOrganizationId(): ?int
    {
        return current_organization_id();
    }

    /**
     * Apply organization filter to a query builder
     */
    protected function applyOrganizationFilter($query): void
    {
        $orgId = $this->getOrganizationId();
        if ($orgId) {
            $query->where('products.organization_id', $orgId);
        }
    }

    /**
     * Get the organization condition for raw SQL
     */
    protected function getOrganizationCondition(): string
    {
        $orgId = $this->getOrganizationId();
        if ($orgId) {
            return "AND products.organization_id = {$orgId}";
        }
        return '';
    }

    /**
     * Calculate all KPIs for the product dashboard.
     *
     * @return array{
     *     total_products: int,
     *     active_products: int,
     *     low_stock_count: int,
     *     out_of_stock_count: int,
     *     total_stock_value: float
     * }
     */
    public function calculateAllKPIs(): array
    {
        return [
            'total_products' => $this->getTotalProducts(),
            'active_products' => $this->getActiveProducts(),
            'low_stock_count' => $this->getLowStockCount(),
            'out_of_stock_count' => $this->getOutOfStockCount(),
            'total_stock_value' => $this->getTotalStockValue(),
        ];
    }

    /**
     * Get total number of products (excluding soft deleted).
     */
    public function getTotalProducts(): int
    {
        $query = DB::table('products')
            ->whereNull('deleted_at');

        // Always filter by organization
        $this->applyOrganizationFilter($query);

        // Filter by current store if user is not admin
        // Include products that belong to store OR have stock in store
        if (!user_can_access_all_stores() && current_store_id()) {
            $storeId = current_store_id();
            $query->where(function ($q) use ($storeId) {
                $q->where('store_id', $storeId)
                    ->orWhereIn('id', function ($sub) use ($storeId) {
                        $sub->select('product_variants.product_id')
                            ->from('product_variants')
                            ->join('store_stock', 'product_variants.id', '=', 'store_stock.product_variant_id')
                            ->where('store_stock.store_id', $storeId)
                            ->where('store_stock.quantity', '>', 0);
                    });
            });
        }

        return $query->count();
    }

    /**
     * Get number of active products.
     */
    public function getActiveProducts(): int
    {
        $query = DB::table('products')
            ->where('status', 'active')
            ->whereNull('deleted_at');

        // Always filter by organization
        $this->applyOrganizationFilter($query);

        // Filter by current store if user is not admin
        // Include products that belong to store OR have stock in store
        if (!user_can_access_all_stores() && current_store_id()) {
            $storeId = current_store_id();
            $query->where(function ($q) use ($storeId) {
                $q->where('store_id', $storeId)
                    ->orWhereIn('id', function ($sub) use ($storeId) {
                        $sub->select('product_variants.product_id')
                            ->from('product_variants')
                            ->join('store_stock', 'product_variants.id', '=', 'store_stock.product_variant_id')
                            ->where('store_stock.store_id', $storeId)
                            ->where('store_stock.quantity', '>', 0);
                    });
            });
        }

        return $query->count();
    }

    /**
     * Get count of products with low stock (total stock <= 10).
     */
    public function getLowStockCount(): int
    {
        $organizationCondition = $this->getOrganizationCondition();
        $storeCondition = '';
        $stockJoin = 'INNER JOIN product_variants ON products.id = product_variants.product_id';
        $stockColumn = 'product_variants.stock_quantity';

        if (!user_can_access_all_stores() && current_store_id()) {
            $storeId = current_store_id();
            // For store users, check store_stock table for store-specific stock
            $stockJoin = '
                INNER JOIN product_variants ON products.id = product_variants.product_id
                LEFT JOIN store_stock ON product_variants.id = store_stock.product_variant_id
                    AND store_stock.store_id = ' . $storeId;
            $stockColumn = 'COALESCE(store_stock.quantity, 0)';
            $storeCondition = "AND (products.store_id = {$storeId} OR store_stock.store_id = {$storeId})";
        }

        return DB::table(DB::raw("(
            SELECT products.id
            FROM products
            {$stockJoin}
            WHERE products.deleted_at IS NULL
            {$organizationCondition}
            {$storeCondition}
            GROUP BY products.id
            HAVING SUM({$stockColumn}) <= 10 AND SUM({$stockColumn}) > 0
        ) as low_stock_products"))
        ->count();
    }

    /**
     * Get count of products that are out of stock.
     */
    public function getOutOfStockCount(): int
    {
        $organizationCondition = $this->getOrganizationCondition();
        $storeCondition = '';
        $stockJoin = 'INNER JOIN product_variants ON products.id = product_variants.product_id';
        $stockColumn = 'product_variants.stock_quantity';

        if (!user_can_access_all_stores() && current_store_id()) {
            $storeId = current_store_id();
            // For store users, check store_stock table for store-specific stock
            $stockJoin = '
                INNER JOIN product_variants ON products.id = product_variants.product_id
                LEFT JOIN store_stock ON product_variants.id = store_stock.product_variant_id
                    AND store_stock.store_id = ' . $storeId;
            $stockColumn = 'COALESCE(store_stock.quantity, 0)';
            $storeCondition = "AND (products.store_id = {$storeId} OR store_stock.store_id = {$storeId})";
        }

        return DB::table(DB::raw("(
            SELECT products.id
            FROM products
            {$stockJoin}
            WHERE products.deleted_at IS NULL
            {$organizationCondition}
            {$storeCondition}
            GROUP BY products.id
            HAVING SUM({$stockColumn}) = 0
        ) as out_of_stock_products"))
        ->count();
    }

    /**
     * Calculate total stock value (quantity * price) for all products.
     */
    public function getTotalStockValue(): float
    {
        $orgId = $this->getOrganizationId();

        // Filter by current store if user is not admin
        if (!user_can_access_all_stores() && current_store_id()) {
            $storeId = current_store_id();
            // Use store_stock for store-specific value
            $query = DB::table('products')
                ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
                ->join('store_stock', 'product_variants.id', '=', 'store_stock.product_variant_id')
                ->whereNull('products.deleted_at')
                ->where('store_stock.store_id', $storeId);

            if ($orgId) {
                $query->where('products.organization_id', $orgId);
            }

            return $query->selectRaw('SUM(store_stock.quantity * products.price) as total_value')
                ->value('total_value') ?? 0;
        }

        // Admin: use global stock from variants
        $query = DB::table('products')
            ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->whereNull('products.deleted_at');

        if ($orgId) {
            $query->where('products.organization_id', $orgId);
        }

        return $query->selectRaw('SUM(product_variants.stock_quantity * products.price) as total_value')
            ->value('total_value') ?? 0;
    }

    /**
     * Get count of products by status.
     *
     * @return array{active: int, inactive: int}
     */
    public function getProductsByStatus(): array
    {
        $query = DB::table('products')
            ->whereNull('deleted_at');

        // Always filter by organization
        $this->applyOrganizationFilter($query);

        // Filter by current store if user is not admin
        // Include products that belong to store OR have stock in store
        if (!user_can_access_all_stores() && current_store_id()) {
            $storeId = current_store_id();
            $query->where(function ($q) use ($storeId) {
                $q->where('store_id', $storeId)
                    ->orWhereIn('id', function ($sub) use ($storeId) {
                        $sub->select('product_variants.product_id')
                            ->from('product_variants')
                            ->join('store_stock', 'product_variants.id', '=', 'store_stock.product_variant_id')
                            ->where('store_stock.store_id', $storeId)
                            ->where('store_stock.quantity', '>', 0);
                    });
            });
        }

        $counts = $query->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        return [
            'active' => $counts['active'] ?? 0,
            'inactive' => $counts['inactive'] ?? 0,
        ];
    }

    /**
     * Get average profit margin across all products.
     */
    public function getAverageProfitMargin(): ?float
    {
        $query = DB::table('products')
            ->whereNull('deleted_at')
            ->whereNotNull('cost_price')
            ->where('price', '>', 0);

        // Always filter by organization
        $this->applyOrganizationFilter($query);

        // Filter by current store if user is not admin
        // Include products that belong to store OR have stock in store
        if (!user_can_access_all_stores() && current_store_id()) {
            $storeId = current_store_id();
            $query->where(function ($q) use ($storeId) {
                $q->where('store_id', $storeId)
                    ->orWhereIn('id', function ($sub) use ($storeId) {
                        $sub->select('product_variants.product_id')
                            ->from('product_variants')
                            ->join('store_stock', 'product_variants.id', '=', 'store_stock.product_variant_id')
                            ->where('store_stock.store_id', $storeId)
                            ->where('store_stock.quantity', '>', 0);
                    });
            });
        }

        $result = $query->selectRaw('AVG(((price - cost_price) / price) * 100) as avg_margin')
            ->value('avg_margin');

        return $result ? round($result, 2) : null;
    }

    /**
     * Get total inventory cost (quantity * cost_price).
     */
    public function getTotalInventoryCost(): float
    {
        $orgId = $this->getOrganizationId();

        // Filter by current store if user is not admin
        if (!user_can_access_all_stores() && current_store_id()) {
            $storeId = current_store_id();
            // Use store_stock for store-specific cost
            $query = DB::table('products')
                ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
                ->join('store_stock', 'product_variants.id', '=', 'store_stock.product_variant_id')
                ->whereNull('products.deleted_at')
                ->whereNotNull('products.cost_price')
                ->where('store_stock.store_id', $storeId);

            if ($orgId) {
                $query->where('products.organization_id', $orgId);
            }

            return $query->selectRaw('SUM(store_stock.quantity * products.cost_price) as total_cost')
                ->value('total_cost') ?? 0;
        }

        // Admin: use global stock from variants
        $query = DB::table('products')
            ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->whereNull('products.deleted_at')
            ->whereNotNull('products.cost_price');

        if ($orgId) {
            $query->where('products.organization_id', $orgId);
        }

        return $query->selectRaw('SUM(product_variants.stock_quantity * products.cost_price) as total_cost')
            ->value('total_cost') ?? 0;
    }
}
