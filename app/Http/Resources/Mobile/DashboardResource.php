<?php

declare(strict_types=1);

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource pour le Dashboard Mobile
 */
class DashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => new UserContextResource($this->resource['user'] ?? []),
            'kpis' => [
                'total_products' => $this->resource['kpis']['total_products'] ?? 0,
                'total_clients' => $this->resource['kpis']['total_clients'] ?? 0,
                'total_suppliers' => $this->resource['kpis']['total_suppliers'] ?? 0,
            ],
            'sales' => [
                'today' => $this->formatCurrency($this->resource['sales']['today'] ?? 0),
                'month' => $this->formatCurrency($this->resource['sales']['month'] ?? 0),
                'total_count' => $this->resource['sales']['total_count'] ?? 0,
                'growth_percent' => $this->resource['sales']['growth_percent'] ?? 0,
            ],
            'stock_alerts' => [
                'total' => $this->resource['stock_alerts']['total'] ?? 0,
                'low_stock' => $this->resource['stock_alerts']['low_stock'] ?? 0,
                'out_of_stock' => $this->resource['stock_alerts']['out_of_stock'] ?? 0,
                'has_critical' => ($this->resource['stock_alerts']['out_of_stock'] ?? 0) > 0,
            ],
            'chart' => $this->resource['chart'] ?? [],
            'stores_performance' => $this->when(
                isset($this->resource['stores_performance']),
                $this->resource['stores_performance']
            ),
            'top_products' => $this->resource['top_products'] ?? [],
            'recent_sales' => $this->resource['recent_sales'] ?? [],
        ];
    }

    /**
     * Format currency value
     */
    private function formatCurrency($value): array
    {
        return [
            'raw' => $value,
            'formatted' => number_format($value, 2, ',', ' '),
        ];
    }
}
