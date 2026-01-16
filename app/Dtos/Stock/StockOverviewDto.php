<?php

namespace App\Dtos\Stock;

class StockOverviewDto
{
    public function __construct(
        public readonly ?string $search = null,
        public readonly ?int $categoryId = null,
        public readonly ?string $stockLevel = null,
        public readonly string $sortField = 'stock_quantity',
        public readonly string $sortDirection = 'asc',
        public readonly int $perPage = 15,
    ) {}

    /**
     * Create from Livewire component properties.
     */
    public static function fromLivewire(array $data): self
    {
        return new self(
            search: !empty($data['search']) ? $data['search'] : null,
            categoryId: !empty($data['categoryId']) ? (int) $data['categoryId'] : null,
            stockLevel: !empty($data['stockLevel']) ? $data['stockLevel'] : null,
            sortField: $data['sortField'] ?? 'stock_quantity',
            sortDirection: $data['sortDirection'] ?? 'asc',
            perPage: $data['perPage'] ?? 15,
        );
    }

    /**
     * Get Livewire queryString configuration.
     */
    public static function getQueryStringConfig(): array
    {
        return [
            'search' => ['except' => ''],
            'categoryId' => ['except' => ''],
            'stockLevel' => ['except' => ''],
            'sortField' => ['except' => 'stock_quantity'],
            'sortDirection' => ['except' => 'asc'],
        ];
    }

    /**
     * Convert to array for repository usage.
     */
    public function toRepositoryParams(): array
    {
        return [
            'search' => $this->search,
            'category_id' => $this->categoryId,
            'stock_level' => $this->stockLevel,
            'sort_field' => $this->sortField,
            'sort_direction' => $this->sortDirection,
        ];
    }

    /**
     * Check if any filters are active.
     */
    public function hasActiveFilters(): bool
    {
        return $this->search !== null
            || $this->categoryId !== null
            || $this->stockLevel !== null;
    }

    /**
     * Create a new instance with modified search.
     */
    public function withSearch(?string $search): self
    {
        return new self(
            search: $search,
            categoryId: $this->categoryId,
            stockLevel: $this->stockLevel,
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
            stockLevel: $this->stockLevel,
            sortField: $this->sortField,
            sortDirection: $this->sortDirection,
            perPage: $this->perPage,
        );
    }

    /**
     * Create a new instance with modified stock level.
     */
    public function withStockLevel(?string $stockLevel): self
    {
        return new self(
            search: $this->search,
            categoryId: $this->categoryId,
            stockLevel: $stockLevel,
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
            stockLevel: $this->stockLevel,
            sortField: $field,
            sortDirection: $direction,
            perPage: $this->perPage,
        );
    }

    /**
     * Create a new instance without any filters.
     */
    public function withoutFilters(): self
    {
        return new self(
            search: null,
            categoryId: null,
            stockLevel: null,
            sortField: $this->sortField,
            sortDirection: $this->sortDirection,
            perPage: $this->perPage,
        );
    }

    /**
     * Check if custom sorting is applied.
     */
    public function hasCustomSorting(): bool
    {
        return $this->sortField !== 'stock_quantity' || $this->sortDirection !== 'asc';
    }
}
