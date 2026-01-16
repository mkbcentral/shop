<?php

namespace App\Livewire\Forms;

use App\Models\Category;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CategoryForm extends Form
{
    public ?int $categoryId = null;

    #[Validate('required', message: 'Le nom de la catégorie est obligatoire.')]
    #[Validate('string', message: 'Le nom de la catégorie doit être une chaîne de caractères.')]
    #[Validate('max:255', message: 'Le nom ne peut pas dépasser 255 caractères.')]
    public string $name = '';

    #[Validate('nullable')]
    #[Validate('string', message: 'La description doit être une chaîne de caractères.')]
    #[Validate('max:500', message: 'La description ne peut pas dépasser 500 caractères.')]
    public ?string $description = null;

    /**
     * Set the category data for editing
     */
    public function setCategory(Category $category): void
    {
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
    }

    /**
     * Get the data as an array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description ?: null,
        ];
    }

    /**
     * Reset the form
     */
    public function reset(...$properties): void
    {
        if (empty($properties)) {
            $this->categoryId = null;
            $this->name = '';
            $this->description = null;
        } else {
            parent::reset(...$properties);
        }
    }

    /**
     * Check if we are editing an existing category
     */
    public function isEditing(): bool
    {
        return $this->categoryId !== null;
    }
}
