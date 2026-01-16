<?php

namespace App\Services;

use App\Dtos\Category\CreateCategoryDto;
use App\Dtos\Category\UpdateCategoryDto;
use App\Events\Category\CategoryCreated;
use App\Events\Category\CategoryDeleted;
use App\Events\Category\CategoryUpdated;
use App\Exceptions\Category\CategoryHasProductsException;
use App\Exceptions\Category\CategoryNotFoundException;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Log;

class CategoryService
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private SlugGeneratorService $slugGenerator
    ) {}

    /**
     * Create a new category from DTO.
     */
    public function createCategory(CreateCategoryDto|array $dto): Category
    {
        // Support both DTO and array for backward compatibility
        if (is_array($dto)) {
            $dto = CreateCategoryDto::fromArray($dto);
        }

        $data = $dto->toArray();

        // Generate slug if not provided
        if (!isset($data['slug']) || empty($data['slug'])) {
            $data['slug'] = $this->slugGenerator->generate(
                $data['name'],
                fn($slug) => $this->categoryRepository->findBySlug($slug)
            );
        }

        $category = $this->categoryRepository->create($data);

        // Dispatch event
        event(new CategoryCreated($category));

        Log::info('Category created', [
            'category_id' => $category->id,
            'name' => $category->name
        ]);

        return $category;
    }

    /**
     * Update a category from DTO.
     */
    public function updateCategory(int $categoryId, UpdateCategoryDto|array $dto): Category
    {
        // Support both DTO and array for backward compatibility
        if (is_array($dto)) {
            $dto = UpdateCategoryDto::fromArray($dto);
        }

        $category = $this->categoryRepository->find($categoryId);

        if (!$category) {
            throw new CategoryNotFoundException($categoryId);
        }

        // Store original attributes for event
        $originalAttributes = $category->getAttributes();

        $data = $dto->toArray();

        // Update slug if name changed and slug not provided
        if (isset($data['name']) && !isset($data['slug'])) {
            $data['slug'] = $this->slugGenerator->generate(
                $data['name'],
                fn($slug) => $this->categoryRepository->findBySlug($slug),
                $categoryId
            );
        }

        $this->categoryRepository->update($category, $data);
        $category = $category->fresh();

        // Dispatch event with changed attributes
        $changedAttributes = array_diff_assoc($category->getAttributes(), $originalAttributes);
        event(new CategoryUpdated($category, $changedAttributes));

        Log::info('Category updated', [
            'category_id' => $category->id,
            'changed_fields' => array_keys($changedAttributes)
        ]);

        return $category;
    }

    /**
     * Delete a category.
     */
    public function deleteCategory(int $categoryId): bool
    {
        $category = $this->categoryRepository->find($categoryId);

        if (!$category) {
            throw new CategoryNotFoundException($categoryId);
        }

        // Check if category has products
        $productsCount = $category->products()->count();
        if ($productsCount > 0) {
            throw new CategoryHasProductsException($categoryId, $productsCount);
        }

        $categoryName = $category->name;
        $deleted = $this->categoryRepository->delete($category);

        if ($deleted) {
            // Dispatch event
            event(new CategoryDeleted($categoryId, $categoryName));

            Log::info('Category deleted', [
                'category_id' => $categoryId,
                'name' => $categoryName
            ]);
        }

        return $deleted;
    }
}
