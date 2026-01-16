<?php

namespace App\Dtos\Auth;

readonly class AuthResponseDto
{
    public function __construct(
        public bool $success,
        public ?string $token = null,
        public ?array $user = null,
        public ?string $message = null,
    ) {}

    public static function success(string $token, array $user): self
    {
        return new self(
            success: true,
            token: $token,
            user: $user,
            message: 'Authentication successful',
        );
    }

    public static function failure(string $message = 'Authentication failed'): self
    {
        return new self(
            success: false,
            message: $message,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'success' => $this->success,
            'token' => $this->token,
            'user' => $this->user,
            'message' => $this->message,
        ], fn($value) => $value !== null);
    }
}
