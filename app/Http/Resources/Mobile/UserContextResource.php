<?php

declare(strict_types=1);

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource pour le contexte utilisateur
 */
class UserContextResource extends JsonResource
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
            'name' => $data->name ?? null,
            'email' => $data->email ?? null,
            'role' => $data->role ?? null,
            'organization' => $data->organization ?? null,
            'current_store' => $data->current_store ?? null,
            'permissions' => [
                'can_access_all_stores' => $data->can_access_all_stores ?? false,
                'is_cashier_or_staff' => $data->is_cashier_or_staff ?? false,
            ],
            'accessible_stores' => $data->accessible_stores ?? [],
        ];
    }
}
