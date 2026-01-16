<?php

namespace App\Actions\Store;

use App\Services\StoreService;

class SwitchUserStoreAction
{
    public function __construct(
        private StoreService $storeService
    ) {}

    /**
     * Execute the action
     */
    public function execute(int $userId, int $storeId): void
    {
        $this->storeService->switchUserStore($userId, $storeId);
    }
}
