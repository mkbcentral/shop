<?php

namespace App\Actions\Store;

use App\Dtos\Store\UpdateStoreDto;
use App\Models\Store;
use App\Services\StoreService;

class UpdateStoreAction
{
    public function __construct(
        private StoreService $storeService
    ) {}

    /**
     * Execute the action
     */
    public function execute(int $storeId, UpdateStoreDto|array $data): Store
    {
        if (is_array($data)) {
            $data = UpdateStoreDto::fromArray($data);
        }

        return $this->storeService->updateStore($storeId, $data->toArray());
    }
}
