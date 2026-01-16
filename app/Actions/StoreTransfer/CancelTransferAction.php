<?php

namespace App\Actions\StoreTransfer;

use App\Models\StoreTransfer;
use App\Services\StoreTransferService;

class CancelTransferAction
{
    public function __construct(
        private StoreTransferService $transferService
    ) {}

    /**
     * Execute the action
     */
    public function execute(int $transferId, int $userId, string $reason): StoreTransfer
    {
        return $this->transferService->cancelTransfer($transferId, $userId, $reason);
    }
}
