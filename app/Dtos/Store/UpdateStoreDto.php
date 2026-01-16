<?php

namespace App\Dtos\Store;

class UpdateStoreDto
{
    public function __construct(
        public readonly ?string $name,
        public readonly ?string $code,
        public readonly ?string $address,
        public readonly ?string $phone,
        public readonly ?string $email,
        public readonly ?int $managerId,
        public readonly ?bool $isActive,
        public readonly ?bool $isMain,
        public readonly ?array $settings,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            code: $data['code'] ?? null,
            address: $data['address'] ?? null,
            phone: $data['phone'] ?? null,
            email: $data['email'] ?? null,
            managerId: $data['manager_id'] ?? null,
            isActive: $data['is_active'] ?? null,
            isMain: $data['is_main'] ?? null,
            settings: $data['settings'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'code' => $this->code,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'manager_id' => $this->managerId,
            'is_active' => $this->isActive,
            'is_main' => $this->isMain,
            'settings' => $this->settings,
        ], fn($value) => $value !== null);
    }
}
