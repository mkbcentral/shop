<?php

namespace App\Exceptions\Category;

use Exception;

class CategoryHasProductsException extends Exception
{
    public function __construct(int $categoryId, int $productsCount)
    {
        parent::__construct(
            "Cannot delete category with ID {$categoryId}. It has {$productsCount} associated product(s). Please move or delete the products first."
        );
    }

    public static function fromCategory($category): self
    {
        return new self($category->id, $category->products()->count());
    }
}
