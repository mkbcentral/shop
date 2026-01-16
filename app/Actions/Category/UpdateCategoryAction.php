<?php

namespace App\Actions\Category;

use App\Dtos\Category\UpdateCategoryDto;
use App\Models\Category;
use App\Services\CategoryService;

class UpdateCategoryAction
{
    public function __construct(
        private CategoryService $categoryService
    ) {}

    /**
     * Update a category from DTO.
     *
     * @param int $categoryId
     * @param UpdateCategoryDto|array $data
     * @return Category
     */
    public function execute(int $categoryId, UpdateCategoryDto|array $data): Category
    {
        // Convert array to DTO if needed
        if (is_array($data)) {
            $data = UpdateCategoryDto::fromArray($data);
        }

        return $this->categoryService->updateCategory($categoryId, $data);
    }
}
