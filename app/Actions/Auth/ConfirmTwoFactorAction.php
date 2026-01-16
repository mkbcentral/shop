<?php

namespace App\Actions\Auth;

use App\Services\AuthService;
use App\Models\User;

class ConfirmTwoFactorAction
{
    public function __construct(
        private AuthService $authService
    ) {}

    /**
     * Confirm two-factor authentication for user.
     */
    public function execute(User $user, string $code): bool
    {
        return $this->authService->confirmTwoFactor($user, $code);
    }
}
