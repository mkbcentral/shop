<?php

namespace App\Dtos\Auth;

readonly class RegisterDto
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $passwordConfirmation,
        public ?string $role = 'user',
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            passwordConfirmation: $data['password_confirmation'],
            role: $data['role'] ?? 'user',
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->passwordConfirmation,
            'role' => $this->role,
        ];
    }
}
