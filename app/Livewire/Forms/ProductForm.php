<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class ProductForm extends Form
{
    #[Validate('required', message: 'Le nom du produit est requis')]
    #[Validate('string', message: 'Le nom du produit doit être une chaîne de caractères')]
    #[Validate('max:255', message: 'Le nom du produit ne peut pas dépasser 255 caractères')]
    public $name = '';

    #[Validate('nullable')]
    #[Validate('string', message: 'La description doit être une chaîne de caractères')]
    #[Validate('min:10', message: 'La description doit contenir au moins 10 caractères')]
    #[Validate('max:1000', message: 'La description ne peut pas dépasser 1000 caractères')]
    public $description = '';

    #[Validate('required', message: 'La référence est requise')]
    #[Validate('string', message: 'La référence doit être une chaîne de caractères')]
    #[Validate('max:100', message: 'La référence ne peut pas dépasser 100 caractères')]
    #[Validate('unique:products,reference', message: 'Cette référence existe déjà')]
    public $reference = '';

    #[Validate('nullable')]
    #[Validate('string', message: 'Le code-barres doit être une chaîne de caractères')]
    #[Validate('max:255', message: 'Le code-barres ne peut pas dépasser 255 caractères')]
    #[Validate('unique:products,barcode', message: 'Ce code-barres existe déjà')]
    public $barcode = '';

    #[Validate('required', message: 'Le prix de vente est requis')]
    #[Validate('numeric', message: 'Le prix de vente doit être un nombre')]
    #[Validate('min:0', message: 'Le prix de vente doit être supérieur ou égal à 0')]
    public $price = '';

    #[Validate('nullable')]
    #[Validate('numeric', message: "Le prix d'achat doit être un nombre")]
    #[Validate('min:0', message: "Le prix d'achat doit être supérieur ou égal à 0")]
    public $cost_price = '';

    #[Validate('required', message: 'La catégorie est requise')]
    #[Validate('exists:categories,id', message: 'La catégorie sélectionnée est invalide')]
    public $category_id = '';

    #[Validate('nullable')]
    #[Validate('exists:product_types,id', message: 'Le type de produit sélectionné est invalide')]
    public $product_type_id = '';

    #[Validate('nullable')]
    #[Validate('image', message: "Le fichier doit être une image")]
    #[Validate('max:2048', message: "L'image ne peut pas dépasser 2 Mo")]
    public $image;

    #[Validate('required', message: 'Le statut est requis')]
    #[Validate('in:active,inactive', message: 'Le statut doit être actif ou inactif')]
    public $status = 'active';

    #[Validate('nullable')]
    #[Validate('integer', message: "Le seuil d'alerte doit être un nombre entier")]
    #[Validate('min:0', message: "Le seuil d'alerte doit être supérieur ou égal à 0")]
    public $stock_alert_threshold = 10;

    /**
     * Set the product data for editing
     */
    public function setProduct($product)
    {
        $this->name = $product->name;
        $this->description = $product->description;
        $this->reference = $product->reference;
        $this->barcode = $product->barcode;
        $this->price = $product->price;
        $this->cost_price = $product->cost_price;
        $this->category_id = $product->category_id;
        $this->product_type_id = $product->product_type_id;
        $this->status = $product->status;
        $this->stock_alert_threshold = $product->stock_alert_threshold ?? 10;
    }

    /**
     * Reset the form
     */
    public function reset(...$properties)
    {
        if (empty($properties)) {
            $this->name = '';
            $this->description = '';
            $this->reference = '';
            $this->barcode = '';
            $this->price = '';
            $this->cost_price = '';
            $this->category_id = '';
            $this->product_type_id = '';
            $this->image = null;
            $this->status = 'active';
            $this->stock_alert_threshold = 10;
        } else {
            parent::reset(...$properties);
        }
    }

    /**
     * Get validation rules for update (with dynamic reference unique rule)
     */
    public function getRulesForUpdate($productId)
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'reference' => 'required|string|max:100|unique:products,reference,' . $productId,
            'barcode' => 'nullable|string|max:255|unique:products,barcode,' . $productId,
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'product_type_id' => 'nullable|exists:product_types,id',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive',
            'stock_alert_threshold' => 'nullable|integer|min:0',
        ];
    }
}
