<?php

namespace App\Exceptions\Category;

use Exception;

class CategorySlugConflictException extends Exception
{
    public function __construct(string $slug)
    {
        parent::__construct("A category with slug '{$slug}' already exists.");
    }
}
