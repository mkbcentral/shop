<?php

namespace App\Livewire\Product;

use App\Models\ProductType;
use Livewire\Component;

class DynamicAttributes extends Component
{
    public $productTypeId;
    public $attributeValues = [];
    public $productAttributes = [];

    protected $listeners = ['productTypeChanged' => 'loadAttributes'];

    public function mount($productTypeId = null, $attributeValues = [])
    {
        $this->productTypeId = $productTypeId;
        $this->attributeValues = $attributeValues;

        if ($productTypeId) {
            $this->loadAttributes();
        }

        // Émettre les valeurs existantes au parent pour synchronisation
        if (!empty($this->attributeValues)) {
            $this->dispatch('attributesUpdated', $this->attributeValues);
        }
    }

    public function loadAttributes()
    {
        if (!$this->productTypeId) {
            $this->productAttributes = [];
            return;
        }

        $productType = ProductType::with('attributes')->find($this->productTypeId);

        if ($productType) {
            $this->productAttributes = $productType->attributes->sortBy('display_order')->toArray();
        }
    }

    public function updatedProductTypeId($value)
    {
        $this->productTypeId = $value;
        $this->attributeValues = [];
        $this->loadAttributes();
    }

    public function updatedAttributeValues($value)
    {
        $this->dispatch('attributesUpdated', $this->attributeValues);
    }

    public function getAttributeValuesProperty()
    {
        return $this->attributeValues;
    }

    public function render()
    {
        return view('livewire.product.dynamic-attributes');
    }
}
