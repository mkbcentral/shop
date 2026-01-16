<?php

namespace App\Actions\Category;

use App\Dtos\Category\CreateCategoryDto;
use App\Models\Category;
use App\Services\CategoryService;

class CreateCategoryAction
{
    public function __construct(
        private CategoryService $categoryService
    ) {}

    /**
     * Create a new category from DTO.
     *
     * @param CreateCategoryDto|array $data
     * @return Category
     */
    public function execute(CreateCategoryDto|array $data): Category
    {
        // Convert array to DTO if needed
        if (is_array($data)) {
            $data = CreateCategoryDto::fromArray($data);
        }

        return $this->categoryService->createCategory($data);
    }
}
