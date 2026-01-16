<?php

namespace App\Exceptions\Category;

use Exception;

class CategoryNotFoundException extends Exception
{
    public function __construct(int $categoryId)
    {
        parent::__construct("Category with ID {$categoryId} not found.");
    }
}
