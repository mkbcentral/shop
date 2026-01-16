<?php

namespace App\Actions\StoreTransfer;

use App\Dtos\Store\CreateTransferDto;
use App\Models\StoreTransfer;
use App\Services\StoreTransferService;

class CreateTransferAction
{
    public function __construct(
        private StoreTransferService $transferService
    ) {}

    /**
     * Execute the action
     */
    public function execute(CreateTransferDto|array $data): StoreTransfer
    {
        if (is_array($data)) {
            $data = CreateTransferDto::fromArray($data);
        }

        return $this->transferService->createTransfer($data->toArray());
    }
}
