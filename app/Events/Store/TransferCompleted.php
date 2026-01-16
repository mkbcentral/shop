<?php

namespace App\Events\Store;

use App\Models\StoreTransfer;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransferCompleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public StoreTransfer $transfer
    ) {}
}
