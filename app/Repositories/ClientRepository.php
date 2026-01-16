<?php

namespace App\Repositories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ClientRepository
{
    /**
     * Count all clients.
     */
    public function count(): int
    {
        return Client::count();
    }

    /**
     * Get a new query builder for Client.
     */
    public function query(): Builder
    {
        return Client::query();
    }

    /**
     * Get all clients.
     */
    public function all(): Collection
    {
        return Client::orderBy('name')->get();
    }

    /**
     * Get paginated clients.
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Client::orderBy('name')->paginate($perPage);
    }

    /**
     * Find client by ID.
     */
    public function find(int $id): ?Client
    {
        return Client::find($id);
    }

    /**
     * Find client by email.
     */
    public function findByEmail(string $email): ?Client
    {
        return Client::where('email', $email)->first();
    }

    /**
     * Find client by phone.
     */
    public function findByPhone(string $phone): ?Client
    {
        return Client::where('phone', $phone)->first();
    }

    /**
     * Create a new client.
     */
    public function create(array $data): Client
    {
        return Client::create($data);
    }

    /**
     * Update a client.
     */
    public function update(Client $client, array $data): bool
    {
        return $client->update($data);
    }

    /**
     * Delete a client.
     */
    public function delete(Client $client): bool
    {
        return $client->delete();
    }

    /**
     * Search clients by name, email, or phone.
     */
    public function search(string $query): Collection
    {
        return Client::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->orderBy('name')
            ->get();
    }

    /**
     * Get clients with sales.
     */
    public function withSales(): Collection
    {
        return Client::with('sales')->orderBy('name')->get();
    }

    /**
     * Get top clients by spending.
     */
    public function topClients(int $limit = 10): Collection
    {
        return Client::withCount('sales')
            ->with(['sales' => function ($query) {
                $query->where('status', 'completed')
                    ->where('payment_status', 'paid');
            }])
            ->orderByDesc('sales_count')
            ->limit($limit)
            ->get();
    }
}
