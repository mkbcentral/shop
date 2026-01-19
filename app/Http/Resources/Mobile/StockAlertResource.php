<?php

declare(strict_types=1);

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource pour les alertes de stock
 */
class StockAlertResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Handle both array and object
        $data = is_array($this->resource) ? (object) $this->resource : $this->resource;

        return [
            'id' => $data->id ?? null,
            'product_id' => $data->product_id ?? null,
            'product_name' => $data->product_name ?? $data->product ?? 'N/A',
            'variant_name' => $data->variant_name ?? $data->variant ?? null,
            'sku' => $data->sku ?? null,
            'current_stock' => $data->current_stock ?? $data->stock_quantity ?? 0,
            'threshold' => $data->threshold ?? $data->low_stock_threshold ?? null,
            'status' => $data->status ?? 'unknown',
            'severity' => $this->getSeverity($data),
            'severity_color' => $this->getSeverityColor($data),
            'store' => $data->store ?? null,
        ];
    }

    /**
     * Determine severity level
     */
    private function getSeverity($data): string
    {
        $status = $data->status ?? '';

        if ($status === 'out_of_stock' || ($data->current_stock ?? 0) <= 0) {
            return 'critical';
        }

        if ($status === 'low_stock') {
            return 'warning';
        }

        return 'info';
    }

    /**
     * Get severity color for mobile UI
     */
    private function getSeverityColor($data): string
    {
        $severity = $this->getSeverity($data);

        return match ($severity) {
            'critical' => '#EF4444', // Red
            'warning' => '#F59E0B',  // Amber
            'info' => '#3B82F6',     // Blue
            default => '#6B7280',    // Gray
        };
    }
}
