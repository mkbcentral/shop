<?php

namespace App\Actions\Store;

use App\Services\StoreService;

class AssignUserToStoreAction
{
    public function __construct(
        private StoreService $storeService
    ) {}

    /**
     * Execute the action
     */
    public function execute(int $storeId, int $userId, string $role = 'staff', bool $isDefault = false): void
    {
        $this->storeService->assignUserToStore($storeId, $userId, $role, $isDefault);
    }
}
