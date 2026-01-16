<?php

namespace App\Dtos\Store;

class CreateStoreDto
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $code,
        public readonly ?string $address,
        public readonly ?string $phone,
        public readonly ?string $email,
        public readonly ?int $managerId,
        public readonly int $organizationId,
        public readonly bool $isActive,
        public readonly bool $isMain,
        public readonly ?array $settings,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            code: $data['code'] ?? null,
            address: $data['address'] ?? null,
            phone: $data['phone'] ?? null,
            email: $data['email'] ?? null,
            managerId: $data['manager_id'] ?? null,
            organizationId: $data['organization_id'],
            isActive: $data['is_active'] ?? true,
            isMain: $data['is_main'] ?? false,
            settings: $data['settings'] ?? null,
        );
    }

    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'manager_id' => $this->managerId,
            'organization_id' => $this->organizationId,
            'is_active' => $this->isActive,
            'is_main' => $this->isMain,
            'settings' => $this->settings,
        ];

        // Only include code if explicitly provided (not null)
        // Otherwise, let the service auto-generate it
        if ($this->code !== null) {
            $data['code'] = $this->code;
        }

        return $data;
    }
}
