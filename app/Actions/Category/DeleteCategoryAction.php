<?php

namespace App\Actions\Category;

use App\Exceptions\Category\CategoryHasProductsException;
use App\Exceptions\Category\CategoryNotFoundException;
use App\Services\CategoryService;

class DeleteCategoryAction
{
    public function __construct(
        private CategoryService $categoryService
    ) {}

    /**
     * Delete a category.
     *
     * @param int $categoryId
     * @return bool
     * @throws CategoryNotFoundException
     * @throws CategoryHasProductsException
     */
    public function execute(int $categoryId): bool
    {
        return $this->categoryService->deleteCategory($categoryId);
    }
}
