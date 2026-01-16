<?php

namespace App\Actions\Auth;

use App\Dtos\Auth\LoginDto;
use App\Dtos\Auth\AuthResponseDto;
use App\Services\AuthService;

class LoginAction
{
    public function __construct(
        private AuthService $authService
    ) {}

    /**
     * Execute login action.
     */
    public function execute(array $data): AuthResponseDto
    {
        // Create DTO from input data
        $loginDto = LoginDto::fromArray($data);

        // Authenticate user
        return $this->authService->login($loginDto);
    }

    /**
     * Execute web login (session-based).
     */
    public function executeWeb(array $data): bool
    {
        // Create DTO from input data
        $loginDto = LoginDto::fromArray($data);

        // Attempt authentication
        return $this->authService->attemptLogin($loginDto);
    }
}
