<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Mobile;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Form Request pour la création d'un produit via l'API Mobile
 */
class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Champs obligatoires
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'product_type_id' => 'required|exists:product_types,id',
            'cost_price' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'stock_alert_threshold' => 'required|integer|min:0',

            // Champs optionnels
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:500',
            'initial_stock' => 'nullable|integer|min:0',

            // Variantes (optionnelles - toggle dans le modal)
            'variants' => 'nullable|array',
            'variants.*.sku' => 'nullable|string|max:100',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.stock_quantity' => 'nullable|integer|min:0',
            'variants.*.attribute_values' => 'nullable|array',
            'variants.*.attribute_values.*.attribute_id' => 'nullable|integer|exists:product_attributes,id',
            'variants.*.attribute_values.*.value' => 'nullable|string|max:255',

            // Attributs du produit (optionnels - toggle dans le modal)
            'attributes' => 'nullable|array',
            'attributes.*.attribute_id' => 'nullable|integer|exists:product_attributes,id',
            'attributes.*.value' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // Nom du produit
            'name.required' => 'Le nom du produit est obligatoire',
            'name.string' => 'Le nom du produit doit être une chaîne de caractères',
            'name.max' => 'Le nom du produit ne peut pas dépasser 255 caractères',

            // Catégorie
            'category_id.required' => 'La catégorie est obligatoire',
            'category_id.exists' => 'La catégorie sélectionnée n\'existe pas',

            // Type de produit
            'product_type_id.required' => 'Le type de produit est obligatoire',
            'product_type_id.exists' => 'Le type de produit sélectionné n\'existe pas',

            // Prix d'achat
            'cost_price.required' => 'Le prix d\'achat est obligatoire',
            'cost_price.numeric' => 'Le prix d\'achat doit être un nombre',
            'cost_price.min' => 'Le prix d\'achat ne peut pas être négatif',

            // Prix de vente
            'price.required' => 'Le prix de vente est obligatoire',
            'price.numeric' => 'Le prix de vente doit être un nombre',
            'price.min' => 'Le prix de vente ne peut pas être négatif',

            // Statut
            'status.required' => 'Le statut est obligatoire',
            'status.in' => 'Le statut doit être "active" ou "inactive"',

            // Seuil d'alerte
            'stock_alert_threshold.required' => 'Le seuil d\'alerte est obligatoire',
            'stock_alert_threshold.integer' => 'Le seuil d\'alerte doit être un nombre entier',
            'stock_alert_threshold.min' => 'Le seuil d\'alerte ne peut pas être négatif',

            // Champs optionnels
            'description.string' => 'La description doit être une chaîne de caractères',
            'image.string' => 'L\'image doit être une chaîne de caractères',
            'image.max' => 'Le chemin de l\'image ne peut pas dépasser 500 caractères',
            'initial_stock.integer' => 'Le stock initial doit être un nombre entier',
            'initial_stock.min' => 'Le stock initial ne peut pas être négatif',

            // Variantes
            'variants.array' => 'Les variantes doivent être un tableau',
            'variants.*.sku.string' => 'Le SKU de la variante doit être une chaîne de caractères',
            'variants.*.sku.max' => 'Le SKU de la variante ne peut pas dépasser 100 caractères',
            'variants.*.price.numeric' => 'Le prix de la variante doit être un nombre',
            'variants.*.price.min' => 'Le prix de la variante ne peut pas être négatif',
            'variants.*.stock_quantity.integer' => 'La quantité de stock de la variante doit être un nombre entier',
            'variants.*.stock_quantity.min' => 'La quantité de stock de la variante ne peut pas être négative',
            'variants.*.attribute_values.array' => 'Les valeurs d\'attributs de la variante doivent être un tableau',
            'variants.*.attribute_values.*.attribute_id.exists' => 'L\'attribut sélectionné n\'existe pas',
            'variants.*.attribute_values.*.value.max' => 'La valeur de l\'attribut ne peut pas dépasser 255 caractères',

            // Attributs du produit
            'attributes.array' => 'Les attributs doivent être un tableau',
            'attributes.*.attribute_id.exists' => 'L\'attribut sélectionné n\'existe pas',
            'attributes.*.value.max' => 'La valeur de l\'attribut ne peut pas dépasser 255 caractères',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nom du produit',
            'category_id' => 'catégorie',
            'product_type_id' => 'type de produit',
            'cost_price' => 'prix d\'achat',
            'price' => 'prix de vente',
            'status' => 'statut',
            'stock_alert_threshold' => 'seuil d\'alerte',
            'description' => 'description',
            'image' => 'image',
            'initial_stock' => 'stock initial',
            'variants' => 'variantes',
            'attributes' => 'attributs',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
