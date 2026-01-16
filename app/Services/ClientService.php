<?php

namespace App\Services;

use App\Models\Client;
use App\Repositories\ClientRepository;

class ClientService
{
    public function __construct(
        private ClientRepository $clientRepository
    ) {}

    /**
     * Create a new client.
     */
    public function createClient(array $data): Client
    {
        // Check for duplicate email
        if (isset($data['email']) && $this->clientRepository->findByEmail($data['email'])) {
            throw new \Exception("A client with this email already exists");
        }

        // Check for duplicate phone
        if (isset($data['phone']) && $this->clientRepository->findByPhone($data['phone'])) {
            throw new \Exception("A client with this phone number already exists");
        }

        return $this->clientRepository->create($data);
    }

    /**
     * Update a client.
     */
    public function updateClient(int $clientId, array $data): Client
    {
        $client = $this->clientRepository->find($clientId);

        if (!$client) {
            throw new \Exception("Client not found");
        }

        // Check for duplicate email (excluding current client)
        if (isset($data['email'])) {
            $existingClient = $this->clientRepository->findByEmail($data['email']);
            if ($existingClient && $existingClient->id !== $clientId) {
                throw new \Exception("A client with this email already exists");
            }
        }

        // Check for duplicate phone (excluding current client)
        if (isset($data['phone'])) {
            $existingClient = $this->clientRepository->findByPhone($data['phone']);
            if ($existingClient && $existingClient->id !== $clientId) {
                throw new \Exception("A client with this phone number already exists");
            }
        }

        $this->clientRepository->update($client, $data);

        return $client->fresh();
    }

    /**
     * Delete a client.
     */
    public function deleteClient(int $clientId): bool
    {
        $client = $this->clientRepository->find($clientId);

        if (!$client) {
            throw new \Exception("Client not found");
        }

        // Check if client has sales
        if ($client->hasSales()) {
            throw new \Exception("Cannot delete client with existing sales history. Consider archiving instead.");
        }

        return $this->clientRepository->delete($client);
    }

    /**
     * Search clients.
     */
    public function searchClients(string $query): \Illuminate\Database\Eloquent\Collection
    {
        return $this->clientRepository->search($query);
    }

    /**
     * Get client profile with statistics.
     */
    public function getClientProfile(int $clientId): array
    {
        $client = $this->clientRepository->find($clientId);

        if (!$client) {
            throw new \Exception("Client not found");
        }

        return [
            'client' => $client,
            'total_spent' => $client->total_spent,
            'total_purchases' => $client->total_purchases,
            'last_purchase_date' => $client->last_purchase_date,
            'recent_sales' => $client->sales()->latest('sale_date')->limit(5)->get(),
        ];
    }

    /**
     * Get top clients.
     */
    public function getTopClients(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return $this->clientRepository->topClients($limit);
    }
}
