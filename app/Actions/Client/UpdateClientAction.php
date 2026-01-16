<?php

namespace App\Actions\Client;

use App\Models\Client;
use App\Services\ClientService;

class UpdateClientAction
{
    public function __construct(
        private ClientService $clientService
    ) {}

    /**
     * Update a client.
     */
    public function execute(int $clientId, array $data): Client
    {
        return $this->clientService->updateClient($clientId, $data);
    }
}
