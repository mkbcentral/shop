<?php

namespace App\Dtos\Auth;

readonly class TwoFactorDto
{
    public function __construct(
        public string $code,
        public ?bool $recovery = false,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            code: $data['code'] ?? $data['recovery_code'] ?? '',
            recovery: isset($data['recovery_code']),
        );
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'recovery' => $this->recovery,
        ];
    }

    public function isRecoveryCode(): bool
    {
        return $this->recovery;
    }
}
