<?php

namespace App\Actions\Auth;

use App\Services\AuthService;
use App\Models\User;

class DisableTwoFactorAction
{
    public function __construct(
        private AuthService $authService
    ) {}

    /**
     * Disable two-factor authentication for user.
     */
    public function execute(User $user): bool
    {
        return $this->authService->disableTwoFactor($user);
    }
}
