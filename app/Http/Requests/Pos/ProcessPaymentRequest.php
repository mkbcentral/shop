<?php

declare(strict_types=1);

namespace App\Http\Requests\Pos;

use Illuminate\Foundation\Http\FormRequest;

class ProcessPaymentRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Règles de validation pour le paiement
     */
    public function rules(): array
    {
        return [
            'cart' => ['required', 'array', 'min:1'],
            'cart.*.variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'cart.*.quantity' => ['required', 'integer', 'min:1'],
            'cart.*.price' => ['required', 'numeric', 'min:0'],
            
            'client_id' => ['nullable', 'integer', 'exists:clients,id'],
            
            'payment_method' => ['required', 'string', 'in:cash,card,mobile,bank_transfer'],
            
            'paid_amount' => ['required', 'numeric', 'min:0'],
            
            'discount' => ['nullable', 'numeric', 'min:0'],
            
            'tax' => ['nullable', 'numeric', 'min:0'],
            
            'notes' => ['nullable', 'string', 'max:500'],
            
            'subtotal' => ['required', 'numeric', 'min:0'],
            
            'total' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * Messages de validation personnalisés
     */
    public function messages(): array
    {
        return [
            'cart.required' => 'Le panier ne peut pas être vide.',
            'cart.min' => 'Le panier doit contenir au moins un article.',
            'cart.*.variant_id.required' => 'L\'identifiant du produit est requis.',
            'cart.*.variant_id.exists' => 'Le produit sélectionné n\'existe pas.',
            'cart.*.quantity.required' => 'La quantité est requise.',
            'cart.*.quantity.min' => 'La quantité doit être d\'au moins 1.',
            'cart.*.price.required' => 'Le prix est requis.',
            'cart.*.price.min' => 'Le prix doit être positif.',
            
            'client_id.exists' => 'Le client sélectionné n\'existe pas.',
            
            'payment_method.required' => 'La méthode de paiement est requise.',
            'payment_method.in' => 'Méthode de paiement invalide. Choisissez: cash, card, mobile ou bank_transfer.',
            
            'paid_amount.required' => 'Le montant payé est requis.',
            'paid_amount.min' => 'Le montant payé doit être positif.',
            
            'discount.min' => 'La remise ne peut pas être négative.',
            
            'tax.min' => 'La taxe ne peut pas être négative.',
            
            'notes.max' => 'Les notes ne peuvent pas dépasser 500 caractères.',
            
            'subtotal.required' => 'Le sous-total est requis.',
            'subtotal.min' => 'Le sous-total doit être positif.',
            
            'total.required' => 'Le total est requis.',
            'total.min' => 'Le total doit être positif.',
        ];
    }

    /**
     * Validation supplémentaire après les règles de base
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Vérifier que le montant payé est suffisant
            if ($this->paid_amount < $this->total) {
                $missing = $this->total - $this->paid_amount;
                $validator->errors()->add(
                    'paid_amount',
                    sprintf('Montant insuffisant. Il manque %s CDF.', number_format($missing, 2))
                );
            }

            // Vérifier que le calcul du total est cohérent
            $calculatedTotal = $this->subtotal - ($this->discount ?? 0) + ($this->tax ?? 0);
            $tolerance = 0.01; // Tolérance pour les arrondis
            
            if (abs($calculatedTotal - $this->total) > $tolerance) {
                $validator->errors()->add(
                    'total',
                    'Le total calculé ne correspond pas. Veuillez recalculer.'
                );
            }

            // Vérifier que le sous-total correspond à la somme des articles
            $cartSubtotal = 0;
            foreach ($this->cart ?? [] as $item) {
                $cartSubtotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
            }

            if (abs($cartSubtotal - $this->subtotal) > $tolerance) {
                $validator->errors()->add(
                    'subtotal',
                    'Le sous-total ne correspond pas à la somme des articles.'
                );
            }
        });
    }

    /**
     * Prépare les données pour la validation
     */
    protected function prepareForValidation(): void
    {
        // Normaliser les montants
        if ($this->has('paid_amount')) {
            $this->merge([
                'paid_amount' => (float) $this->paid_amount,
            ]);
        }

        if ($this->has('discount')) {
            $this->merge([
                'discount' => (float) $this->discount,
            ]);
        }

        if ($this->has('tax')) {
            $this->merge([
                'tax' => (float) $this->tax,
            ]);
        }
    }
}
