<?php

namespace App\Livewire\Forms;

use App\Models\ProductType;
use Livewire\Form;

class ProductTypeForm extends Form
{
    public ?int $productTypeId = null;
    public string $name = '';
    public ?string $slug = '';
    public string $icon = '📦';
    public ?string $description = '';
    public bool $has_variants = false;
    public bool $has_expiry_date = false;
    public bool $has_weight = false;
    public bool $has_dimensions = false;
    public bool $has_serial_number = false;
    public bool $is_active = true;
    public int $display_order = 0;

    protected function rules(): array
    {
        $uniqueRule = $this->productTypeId
            ? "unique:product_types,slug,{$this->productTypeId}"
            : 'unique:product_types,slug';

        return [
            'name' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', $uniqueRule],
            'icon' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'has_variants' => 'boolean',
            'has_expiry_date' => 'boolean',
            'has_weight' => 'boolean',
            'has_dimensions' => 'boolean',
            'has_serial_number' => 'boolean',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer',
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'slug.unique' => 'Ce slug est déjà utilisé.',
        ];
    }

    public function setProductType(ProductType $productType): void
    {
        $this->productTypeId = $productType->id;
        $this->name = $productType->name;
        $this->slug = $productType->slug ?? '';
        $this->icon = $productType->icon ?? '📦';
        $this->description = $productType->description ?? '';
        $this->has_variants = $productType->has_variants ?? false;
        $this->has_expiry_date = $productType->has_expiry_date ?? false;
        $this->has_weight = $productType->has_weight ?? false;
        $this->has_dimensions = $productType->has_dimensions ?? false;
        $this->has_serial_number = $productType->has_serial_number ?? false;
        $this->is_active = $productType->is_active ?? true;
        $this->display_order = $productType->display_order ?? 0;
    }

    public function isEditing(): bool
    {
        return $this->productTypeId !== null;
    }

    public function reset(...$properties): void
    {
        $this->productTypeId = null;
        $this->name = '';
        $this->slug = '';
        $this->icon = '📦';
        $this->description = '';
        $this->has_variants = false;
        $this->has_expiry_date = false;
        $this->has_weight = false;
        $this->has_dimensions = false;
        $this->has_serial_number = false;
        $this->is_active = true;
        $this->display_order = 0;

        $this->resetValidation();
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug ?: null,
            'icon' => $this->icon ?: '📦',
            'description' => $this->description ?: null,
            'has_variants' => $this->has_variants,
            'has_expiry_date' => $this->has_expiry_date,
            'has_weight' => $this->has_weight,
            'has_dimensions' => $this->has_dimensions,
            'has_serial_number' => $this->has_serial_number,
            'is_active' => $this->is_active,
            'display_order' => $this->display_order,
        ];
    }
}
