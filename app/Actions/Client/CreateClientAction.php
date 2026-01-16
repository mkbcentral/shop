<?php

namespace App\Actions\Client;

use App\Models\Client;
use App\Services\ClientService;

class CreateClientAction
{
    public function __construct(
        private ClientService $clientService
    ) {}

    /**
     * Create a new client.
     */
    public function execute(array $data): Client
    {
        // Validate required fields
        if (!isset($data['name'])) {
            throw new \Exception("Client name is required");
        }

        return $this->clientService->createClient($data);
    }
}
