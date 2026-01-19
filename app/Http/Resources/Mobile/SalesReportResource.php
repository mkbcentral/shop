<?php

declare(strict_types=1);

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource pour les rapports de ventes
 */
class SalesReportResource extends JsonResource
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
            'total_sales' => [
                'raw' => $data->total_sales ?? 0,
                'formatted' => number_format($data->total_sales ?? 0, 2, ',', ' '),
            ],
            'transaction_count' => $data->transaction_count ?? 0,
            'average_ticket' => [
                'raw' => $data->average_ticket ?? 0,
                'formatted' => number_format($data->average_ticket ?? 0, 2, ',', ' '),
            ],
            'payment_methods' => $data->payment_methods ?? [],
            'breakdown' => $this->getBreakdown($data),
            'trend' => $data->trend ?? null,
        ];
    }

    /**
     * Get the appropriate breakdown (daily, weekly, or hourly)
     */
    private function getBreakdown($data): array
    {
        if (isset($data->hourly_distribution)) {
            return [
                'type' => 'hourly',
                'data' => $data->hourly_distribution,
            ];
        }

        if (isset($data->daily_breakdown)) {
            return [
                'type' => 'daily',
                'data' => $data->daily_breakdown,
            ];
        }

        if (isset($data->weekly_breakdown)) {
            return [
                'type' => 'weekly',
                'data' => $data->weekly_breakdown,
            ];
        }

        return [
            'type' => 'none',
            'data' => [],
        ];
    }
}
