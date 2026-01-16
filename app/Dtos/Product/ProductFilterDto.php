<?php

namespace App\Dtos\Product;

class ProductFilterDto
{
    public function __construct(
        public readonly ?string $search = null,
        public readonly ?int $categoryId = null,
        public readonly ?string $status = null,
        public readonly ?string $stockLevel = null,
        public readonly string $viewMode = 'table',
        public readonly string $sortField = 'name',
        public readonly string $sortDirection = 'asc',
        public readonly int $perPage = 15,
    ) {}

    /**
     * Create DTO from Livewire component properties.
     */
    public static function fromLivewire(array $data): self
    {
        return new self(
            search: !empty($data['search']) ? $data['search'] : null,
            categoryId: !empty($data['categoryFilter']) ? (int) $data['categoryFilter'] : null,
            status: !empty($data['statusFilter']) ? $data['statusFilter'] : null,
            stockLevel: !empty($data['stockLevelFilter']) ? $data['stockLevelFilter'] : null,
            viewMode: $data['viewMode'] ?? 'table',
            sortField: $data['sortField'] ?? 'name',
            sortDirection: $data['sortDirection'] ?? 'asc',
            perPage: $data['perPage'] ?? 15,
        );
    }

    /**
     * Check if any filters are active.
     */
    public function hasActiveFilters(): bool
    {
        return $this->search !== null
            || $this->categoryId !== null
            || $this->status !== null
            || $this->stockLevel !== null;
    }

    /**
     * Check if sorting is applied (not default).
     */
    public function hasCustomSorting(): bool
    {
        return $this->sortField !== 'name' || $this->sortDirection !== 'asc';
    }

    /**
     * Get query string configuration for Livewire.
     */
    public static function getQueryStringConfig(): array
    {
        return [
            'search' => ['except' => ''],
            'categoryFilter' => ['except' => ''],
            'statusFilter' => ['except' => ''],
            'stockLevelFilter' => ['except' => ''],
            'viewMode' => ['except' => 'table'],
            'sortField' => ['except' => 'name'],
            'sortDirection' => ['except' => 'asc'],
        ];
    }

    /**
     * Convert to array for repository methods.
     */
    public function toRepositoryParams(): array
    {
        return [
            'search' => $this->search,
            'categoryId' => $this->categoryId,
            'status' => $this->status,
            'stockLevel' => $this->stockLevel,
            'sortField' => $this->sortField,
            'sortDirection' => $this->sortDirection,
        ];
    }

    /**
     * Create a new instance with modified search.
     */
    public function withSearch(?string $search): self
    {
        return new self(
            search: $search,
            categoryId: $this->categoryId,
            status: $this->status,
            stockLevel: $this->stockLevel,
            viewMode: $this->viewMode,
            sortField: $this->sortField,
            sortDirection: $this->sortDirection,
            perPage: $this->perPage,
        );
    }

    /**
     * Create a new instance with modified category.
     */
    public function withCategory(?int $categoryId): self
    {
        return new self(
            search: $this->search,
            categoryId: $categoryId,
            status: $this->status,
            stockLevel: $this->stockLevel,
            viewMode: $this->viewMode,
            sortField: $this->sortField,
            sortDirection: $this->sortDirection,
            perPage: $this->perPage,
        );
    }

    /**
     * Create a new instance with cleared filters.
     */
    public function withoutFilters(): self
    {
        return new self(
            search: null,
            categoryId: null,
            status: null,
            stockLevel: null,
            viewMode: $this->viewMode,
            sortField: $this->sortField,
            sortDirection: $this->sortDirection,
            perPage: $this->perPage,
        );
    }

    /**
     * Create a new instance with modified sorting.
     */
    public function withSort(string $field, string $direction = 'asc'): self
    {
        return new self(
            search: $this->search,
            categoryId: $this->categoryId,
            status: $this->status,
            stockLevel: $this->stockLevel,
            viewMode: $this->viewMode,
            sortField: $field,
            sortDirection: $direction,
            perPage: $this->perPage,
        );
    }
}
