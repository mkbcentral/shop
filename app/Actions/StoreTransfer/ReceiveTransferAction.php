<?php

namespace App\Actions\StoreTransfer;

use App\Models\StoreTransfer;
use App\Services\StoreTransferService;

class ReceiveTransferAction
{
    public function __construct(
        private StoreTransferService $transferService
    ) {}

    /**
     * Execute the action
     */
    public function execute(int $transferId, array $receivedQuantities, int $userId, ?string $notes = null): StoreTransfer
    {
        return $this->transferService->receiveTransfer($transferId, $receivedQuantities, $userId, $notes);
    }
}
