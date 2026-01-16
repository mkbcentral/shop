<?php

namespace App\Livewire\Product;

use App\Models\Product;
use App\Models\ProductVariant;
use Livewire\Component;

class VariantSelector extends Component
{
    public $productId;
    public $product;
    public $variants = [];
    public $selectedVariantId = null;
    public $selectedOptions = [];
    public $matchingVariant = null;
    public $isOpen = false;

    protected $listeners = [
        'openVariantSelector' => 'open',
    ];

    public function mount($productId = null)
    {
        if ($productId) {
            $this->loadProduct($productId);
        }
    }

    public function open($productId)
    {
        $this->reset(['selectedOptions', 'selectedVariantId', 'matchingVariant']);
        $this->loadProduct($productId);
        $this->isOpen = true;
    }

    public function loadProduct($productId)
    {
        $this->productId = $productId;

        $this->product = Product::with([
            'productType.variantAttributes',
            'variants.attributeValues.productAttribute'
        ])->find($productId);

        if ($this->product) {
            $this->variants = $this->product->variants()
                ->with('attributeValues.productAttribute')
                ->where('stock_quantity', '>', 0)
                ->get();

            // Initialize selected options with null values
            if ($this->product->productType && $this->product->productType->has_variants) {
                foreach ($this->product->productType->variantAttributes as $attr) {
                    $this->selectedOptions[$attr->code] = null;
                }
            }
        }
    }

    public function updatedSelectedOptions()
    {
        $this->findMatchingVariant();
    }

    public function findMatchingVariant()
    {
        if (empty($this->selectedOptions) || empty($this->variants)) {
            $this->matchingVariant = null;
            return;
        }

        // Check if all options are selected
        $allSelected = true;
        foreach ($this->selectedOptions as $value) {
            if (empty($value)) {
                $allSelected = false;
                break;
            }
        }

        if (!$allSelected) {
            $this->matchingVariant = null;
            return;
        }

        // Find variant matching all selected options
        foreach ($this->variants as $variant) {
            $matches = true;

            foreach ($this->selectedOptions as $attrCode => $selectedValue) {
                $attrValue = $variant->attributeValues->first(function($av) use ($attrCode) {
                    return $av->productAttribute->code === $attrCode;
                });

                if (!$attrValue || $attrValue->value !== $selectedValue) {
                    $matches = false;
                    break;
                }
            }

            if ($matches) {
                $this->matchingVariant = $variant;
                $this->selectedVariantId = $variant->id;
                return;
            }
        }

        $this->matchingVariant = null;
        $this->selectedVariantId = null;
    }

    public function selectVariant()
    {
        if (!$this->matchingVariant) {
            return;
        }

        // Dispatch event with selected variant
        $this->dispatch('variantSelected', [
            'product_id' => $this->productId,
            'variant_id' => $this->matchingVariant->id,
            'variant_details' => $this->matchingVariant->getFormattedAttributes(),
            'stock' => $this->matchingVariant->stock_quantity,
            'price' => $this->product->price + $this->matchingVariant->additional_price,
        ]);

        $this->close();
    }

    public function close()
    {
        $this->isOpen = false;
        $this->reset(['selectedOptions', 'selectedVariantId', 'matchingVariant']);
    }

    public function getAvailableOptionsForAttribute($attributeCode)
    {
        if (empty($this->variants)) {
            return [];
        }

        $options = [];

        foreach ($this->variants as $variant) {
            // Check if variant matches currently selected options (except this attribute)
            $matchesOthers = true;

            foreach ($this->selectedOptions as $code => $value) {
                if ($code === $attributeCode || empty($value)) {
                    continue;
                }

                $attrValue = $variant->attributeValues->first(function($av) use ($code) {
                    return $av->productAttribute->code === $code;
                });

                if (!$attrValue || $attrValue->value !== $value) {
                    $matchesOthers = false;
                    break;
                }
            }

            if ($matchesOthers) {
                $attrValue = $variant->attributeValues->first(function($av) use ($attributeCode) {
                    return $av->productAttribute->code === $attributeCode;
                });

                if ($attrValue && !in_array($attrValue->value, $options)) {
                    $options[] = $attrValue->value;
                }
            }
        }

        return $options;
    }

    public function render()
    {
        return view('livewire.product.variant-selector');
    }
}
