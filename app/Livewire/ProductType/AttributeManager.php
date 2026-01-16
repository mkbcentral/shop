<?php

namespace App\Livewire\ProductType;

use App\Services\ProductTypeService;
use Livewire\Component;

class AttributeManager extends Component
{
    public $productTypeId;
    public $attributes = [];
    public $showForm = false;
    public $editingIndex = null;

    // Form fields for attribute
    public $name = '';
    public $code = '';
    public $type = 'text';
    public $options = [];
    public $unit = '';
    public $default_value = '';
    public $is_required = false;
    public $is_variant_attribute = false;
    public $is_filterable = true;
    public $is_visible = true;
    public $display_order = 0;

    public $optionsString = ''; // For textarea input

    protected $rules = [
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:255',
        'type' => 'required|in:text,number,select,boolean,date,color',
        'optionsString' => 'nullable|string',
        'unit' => 'nullable|string|max:50',
        'default_value' => 'nullable|string',
        'is_required' => 'boolean',
        'is_variant_attribute' => 'boolean',
        'is_filterable' => 'boolean',
        'is_visible' => 'boolean',
    ];

    public function mount($productTypeId = null)
    {
        $this->productTypeId = $productTypeId;

        if ($productTypeId) {
            $service = app(ProductTypeService::class);
            $productType = $service->getProductTypeById($productTypeId);

            if ($productType) {
                $this->attributes = $productType->attributes->map(function($attr) {
                    return [
                        'id' => $attr->id,
                        'name' => $attr->name,
                        'code' => $attr->code,
                        'type' => $attr->type,
                        'options' => $attr->options ?? [],
                        'unit' => $attr->unit,
                        'default_value' => $attr->default_value,
                        'is_required' => $attr->is_required,
                        'is_variant_attribute' => $attr->is_variant_attribute,
                        'is_filterable' => $attr->is_filterable,
                        'is_visible' => $attr->is_visible,
                        'display_order' => $attr->display_order,
                    ];
                })->toArray();
            }
        }
    }

    public function addAttribute()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingIndex = null;
    }

    public function editAttribute($index)
    {
        $attribute = $this->attributes[$index];

        $this->name = $attribute['name'];
        $this->code = $attribute['code'];
        $this->type = $attribute['type'];
        $this->options = $attribute['options'] ?? [];
        $this->optionsString = !empty($this->options) ? implode("\n", $this->options) : '';
        $this->unit = $attribute['unit'] ?? '';
        $this->default_value = $attribute['default_value'] ?? '';
        $this->is_required = $attribute['is_required'] ?? false;
        $this->is_variant_attribute = $attribute['is_variant_attribute'] ?? false;
        $this->is_filterable = $attribute['is_filterable'] ?? true;
        $this->is_visible = $attribute['is_visible'] ?? true;
        $this->display_order = $attribute['display_order'] ?? 0;

        $this->showForm = true;
        $this->editingIndex = $index;
    }

    public function saveAttribute()
    {
        $this->validate();

        // Parse options from textarea
        if ($this->type === 'select' && !empty($this->optionsString)) {
            $this->options = array_filter(array_map('trim', explode("\n", $this->optionsString)));
        } else {
            $this->options = [];
        }

        $attributeData = [
            'name' => $this->name,
            'code' => $this->code,
            'type' => $this->type,
            'options' => $this->options,
            'unit' => $this->unit,
            'default_value' => $this->default_value,
            'is_required' => $this->is_required,
            'is_variant_attribute' => $this->is_variant_attribute,
            'is_filterable' => $this->is_filterable,
            'is_visible' => $this->is_visible,
            'display_order' => count($this->attributes) + 1,
        ];

        if ($this->editingIndex !== null) {
            // Update existing
            $attributeData['id'] = $this->attributes[$this->editingIndex]['id'] ?? null;
            $this->attributes[$this->editingIndex] = $attributeData;
        } else {
            // Add new
            $this->attributes[] = $attributeData;
        }

        $this->showForm = false;
        $this->resetForm();

        $this->dispatch('attributes-updated', $this->attributes);
        session()->flash('message', 'Attribut enregistré avec succès.');
    }

    public function deleteAttribute($index)
    {
        unset($this->attributes[$index]);
        $this->attributes = array_values($this->attributes);

        $this->dispatch('attributes-updated', $this->attributes);
        session()->flash('message', 'Attribut supprimé avec succès.');
    }

    public function cancelForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function moveUp($index)
    {
        if ($index > 0) {
            $temp = $this->attributes[$index];
            $this->attributes[$index] = $this->attributes[$index - 1];
            $this->attributes[$index - 1] = $temp;

            $this->dispatch('attributes-updated', $this->attributes);
        }
    }

    public function moveDown($index)
    {
        if ($index < count($this->attributes) - 1) {
            $temp = $this->attributes[$index];
            $this->attributes[$index] = $this->attributes[$index + 1];
            $this->attributes[$index + 1] = $temp;

            $this->dispatch('attributes-updated', $this->attributes);
        }
    }

    protected function resetForm()
    {
        $this->name = '';
        $this->code = '';
        $this->type = 'text';
        $this->options = [];
        $this->optionsString = '';
        $this->unit = '';
        $this->default_value = '';
        $this->is_required = false;
        $this->is_variant_attribute = false;
        $this->is_filterable = true;
        $this->is_visible = true;
        $this->display_order = 0;
    }

    public function getAttributesProperty()
    {
        return $this->attributes;
    }

    public function render()
    {
        return view('livewire.product-type.attribute-manager');
    }
}

