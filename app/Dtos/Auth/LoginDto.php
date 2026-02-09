<?php

namespace App\Dtos\Auth;

readonly class LoginDto
{
    public function __construct(
        public string $email,
        public string $password,
        public bool $remember = false,
        public string $deviceName = 'mobile-app',
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'],
            password: $data['password'],
            remember: $data['remember'] ?? false,
            deviceName: $data['device_name'] ?? 'mobile-app',
        );
    }

    public static function fromRequest(\Illuminate\Http\Request $request): self
    {
        return self::fromArray($request->validated());
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
            'remember' => $this->remember,
            'device_name' => $this->deviceName,
        ];
    }

    public function credentials(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
