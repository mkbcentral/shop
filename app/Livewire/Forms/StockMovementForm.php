<?php

namespace App\Livewire\Forms;

use App\Models\ProductVariant;
use App\Models\StockMovement;
use Livewire\Form;

class StockMovementForm extends Form
{
    public ?string $type = 'add'; // add, remove, adjust

    public ?int $product_variant_id = null;

    public ?int $quantity = null;

    public ?string $movement_type = null;

    public ?string $reference = null;

    public ?string $reason = null;

    public ?float $unit_price = null;

    public ?string $date = null;

    // For adjust type only
    public ?int $new_quantity = null;

    // Option to update product cost_price
    public bool $update_product_cost = false;

    /**
     * Get validation rules based on form type
     */
    public function rules(): array
    {
        $rules = [
            'product_variant_id' => ['required', 'exists:product_variants,id'],
            'movement_type' => ['nullable', 'string'],
            'reference' => ['nullable', 'string', 'max:255'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
            'date' => ['nullable', 'date'],
        ];

        // Use movement_type to determine validation rules (more reliable than $type)
        $isAdjustment = $this->movement_type === \App\Models\StockMovement::MOVEMENT_ADJUSTMENT;

        if ($isAdjustment) {
            // For adjust: new_quantity and reason are required
            $rules['new_quantity'] = ['required', 'integer', 'min:0'];
            $rules['reason'] = ['required', 'string', 'max:500'];
            $rules['quantity'] = ['nullable', 'integer'];
        } else {
            // For add/remove: quantity is required
            $rules['quantity'] = ['required', 'integer', 'min:1'];
            $rules['reason'] = ['nullable', 'string', 'max:500'];
        }

        return $rules;
    }

    /**
     * Get validation messages
     */
    public function messages(): array
    {
        return [
            'product_variant_id.required' => 'Le produit est obligatoire.',
            'product_variant_id.exists' => 'Le produit sélectionné n\'existe pas.',
            'quantity.required' => 'La quantité est obligatoire.',
            'quantity.integer' => 'La quantité doit être un nombre entier.',
            'quantity.min' => 'La quantité doit être supérieure à 0.',
            'new_quantity.required' => 'La nouvelle quantité est obligatoire.',
            'new_quantity.integer' => 'La nouvelle quantité doit être un nombre entier.',
            'new_quantity.min' => 'La nouvelle quantité ne peut pas être négative.',
            'reason.required' => 'La raison est obligatoire pour un ajustement.',
            'reason.string' => 'La raison doit être une chaîne de caractères.',
            'reason.max' => 'La raison ne peut pas dépasser 500 caractères.',
            'movement_type.string' => 'Le type de mouvement doit être une chaîne de caractères.',
            'reference.string' => 'La référence doit être une chaîne de caractères.',
            'reference.max' => 'La référence ne peut pas dépasser 255 caractères.',
            'unit_price.numeric' => 'Le prix unitaire doit être un nombre.',
            'unit_price.min' => 'Le prix unitaire doit être positif.',
            'date.date' => 'La date doit être valide.',
        ];
    }

    /**
     * Set the form type (add, remove, adjust)
     */
    public function setType(string $type): void
    {
        $this->type = $type;
        $this->date = now()->format('Y-m-d');

        // Set default movement_type based on type
        if ($type === 'add') {
            $this->movement_type = StockMovement::MOVEMENT_PURCHASE;
            $this->generateReference();
        } elseif ($type === 'remove') {
            $this->movement_type = StockMovement::MOVEMENT_SALE;
            $this->generateReference();
        } elseif ($type === 'adjust') {
            $this->movement_type = StockMovement::MOVEMENT_ADJUSTMENT;
            $this->generateReference();
        }
    }

    /**
     * Get the product's current cost_price
     */
    public function getProductCostPrice(): ?float
    {
        if (!$this->product_variant_id) {
            return null;
        }

        $variant = ProductVariant::with('product')->find($this->product_variant_id);
        return $variant?->product?->cost_price;
    }

    /**
     * Pre-fill unit_price with product's cost_price
     */
    public function prefillUnitPrice(): void
    {
        $costPrice = $this->getProductCostPrice();
        if ($costPrice !== null) {
            $this->unit_price = $costPrice;
        }
    }

    /**
     * Generate a reference based on movement type
     */
    public function generateReference(): void
    {
        $prefixes = [
            StockMovement::MOVEMENT_PURCHASE => 'BC',    // Bon de Commande
            StockMovement::MOVEMENT_SALE => 'VT',        // Vente
            StockMovement::MOVEMENT_ADJUSTMENT => 'AJ',  // Ajustement
            StockMovement::MOVEMENT_TRANSFER => 'TR',    // Transfert
            StockMovement::MOVEMENT_RETURN => 'RT',      // Retour
        ];

        $prefix = $prefixes[$this->movement_type] ?? 'MV'; // MV = Mouvement par défaut
        $year = now()->format('Y');
        $month = now()->format('m');

        // Count existing movements of this type this month
        $count = StockMovement::where('movement_type', $this->movement_type)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();

        $nextNumber = str_pad($count + 1, 4, '0', STR_PAD_LEFT);

        $this->reference = "{$prefix}-{$year}{$month}-{$nextNumber}";
    }

    /**
     * Get the data as an array
     */
    public function toArray(): array
    {
        $isAdjustment = $this->movement_type === \App\Models\StockMovement::MOVEMENT_ADJUSTMENT;

        $data = [
            'product_variant_id' => $this->product_variant_id,
            'quantity' => $this->quantity,
            'movement_type' => $this->movement_type,
            'reference' => $this->reference ?: null,
            'reason' => $this->reason ?: null,
            'unit_price' => $this->unit_price ?: null,
            'date' => $this->date ?: now()->format('Y-m-d'),
            'update_product_cost' => $this->update_product_cost,
        ];

        // For adjust type, include new_quantity
        if ($isAdjustment) {
            $data['new_quantity'] = $this->new_quantity;
        }

        return $data;
    }

    /**
     * Reset the form
     */
    public function reset(...$properties): void
    {
        if (empty($properties)) {
            $this->type = 'add';
            $this->product_variant_id = null;
            $this->quantity = null;
            $this->movement_type = null;
            $this->reference = null;
            $this->reason = null;
            $this->unit_price = null;
            $this->date = now()->format('Y-m-d');
            $this->new_quantity = null;
            $this->update_product_cost = false;
        } else {
            parent::reset(...$properties);
        }
    }

    /**
     * Get available stock for selected variant
     */
    public function getAvailableStock(): ?int
    {
        if (!$this->product_variant_id) {
            return null;
        }

        $variant = ProductVariant::find($this->product_variant_id);
        return $variant?->stock_quantity;
    }
}
