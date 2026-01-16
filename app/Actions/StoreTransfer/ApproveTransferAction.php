<?php

namespace App\Actions\StoreTransfer;

use App\Models\StoreTransfer;
use App\Services\StoreTransferService;

class ApproveTransferAction
{
    public function __construct(
        private StoreTransferService $transferService
    ) {}

    /**
     * Execute the action
     */
    public function execute(int $transferId, int $userId): StoreTransfer
    {
        return $this->transferService->approveTransfer($transferId, $userId);
    }
}
