<?php

namespace App\Actions\Client;

use App\Services\ClientService;

class DeleteClientAction
{
    public function __construct(
        private ClientService $clientService
    ) {}

    /**
     * Delete a client (soft delete).
     */
    public function execute(int $clientId): bool
    {
        return $this->clientService->deleteClient($clientId);
    }
}
