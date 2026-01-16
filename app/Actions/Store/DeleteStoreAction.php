<?php

namespace App\Actions\Store;

use App\Services\StoreService;

class DeleteStoreAction
{
    public function __construct(
        private StoreService $storeService
    ) {}

    /**
     * Execute the action
     */
    public function execute(int $storeId): bool
    {
        return $this->storeService->deleteStore($storeId);
    }
}
