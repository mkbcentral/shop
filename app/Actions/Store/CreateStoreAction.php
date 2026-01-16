<?php

namespace App\Actions\Store;

use App\Dtos\Store\CreateStoreDto;
use App\Models\Store;
use App\Services\StoreService;

class CreateStoreAction
{
    public function __construct(
        private StoreService $storeService
    ) {}

    /**
     * Execute the action
     */
    public function execute(CreateStoreDto|array $data): Store
    {
        if (is_array($data)) {
            $data = CreateStoreDto::fromArray($data);
        }

        return $this->storeService->createStore($data->toArray());
    }
}
