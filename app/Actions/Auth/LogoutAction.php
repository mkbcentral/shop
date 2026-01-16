<?php

namespace App\Actions\Auth;

use App\Services\AuthService;

class LogoutAction
{
    public function __construct(
        private AuthService $authService
    ) {}

    /**
     * Execute logout action.
     */
    public function execute(): void
    {
        $this->authService->logout();
    }

    /**
     * Execute logout for current device only.
     */
    public function executeCurrentDevice(): void
    {
        $this->authService->logoutCurrentDevice();
    }
}
