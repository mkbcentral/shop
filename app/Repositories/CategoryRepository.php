<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository
{
    /**
     * Get all categories.
     */
    public function all(): Collection
    {
        return Category::orderBy('name')->get();
    }

    /**
     * Find category by ID.
     */
    public function find(int $id): ?Category
    {
        return Category::find($id);
    }

    /**
     * Find category by ID or fail.
     */
    public function findOrFail(int $id): Category
    {
        return Category::findOrFail($id);
    }

    /**
     * Find category by slug.
     */
    public function findBySlug(string $slug): ?Category
    {
        return Category::where('slug', $slug)->first();
    }

    /**
     * Create a new category.
     */
    public function create(array $data): Category
    {
        return Category::create($data);
    }

    /**
     * Update a category.
     */
    public function update(Category $category, array $data): bool
    {
        return $category->update($data);
    }

    /**
     * Delete a category.
     */
    public function delete(Category $category): bool
    {
        return $category->delete();
    }

    /**
     * Get categories with product count.
     */
    public function withProductCount(): Collection
    {
        return Category::withCount('products')->orderBy('name')->get();
    }

    /**
     * Get categories with active products only.
     */
    public function withActiveProducts(): Collection
    {
        return Category::with(['products' => function ($query) {
            $query->where('status', 'active');
        }])->orderBy('name')->get();
    }

    /**
     * Get paginated categories with optional search.
     */
    public function paginate(?string $search = null, int $perPage = 10)
    {
        return Category::query()
            ->withCount('products')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->orderBy('name')
            ->paginate($perPage);
    }
}
