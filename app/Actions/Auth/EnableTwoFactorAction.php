<?php

namespace App\Actions\Auth;

use App\Services\AuthService;
use App\Models\User;

class EnableTwoFactorAction
{
    public function __construct(
        private AuthService $authService
    ) {}

    /**
     * Enable two-factor authentication for user.
     */
    public function execute(User $user): array
    {
        return $this->authService->enableTwoFactor($user);
    }
}
