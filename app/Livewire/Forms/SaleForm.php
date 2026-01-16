<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class SaleForm extends Form
{
    #[Validate('nullable')]
    #[Validate('exists:clients,id', message: 'Le client sélectionné est invalide')]
    public $client_id = null;

    #[Validate('required', message: 'La date de vente est requise')]
    #[Validate('date', message: 'La date de vente doit être une date valide')]
    public $sale_date = '';

    #[Validate('required', message: 'La méthode de paiement est requise')]
    #[Validate('in:cash,card,transfer,cheque', message: 'La méthode de paiement est invalide')]
    public $payment_method = 'cash';

    #[Validate('required', message: 'Le statut de paiement est requis')]
    #[Validate('in:pending,paid,partial,refunded', message: 'Le statut de paiement est invalide')]
    public $payment_status = 'pending';

    #[Validate('required', message: 'Le statut est requis')]
    #[Validate('in:pending,completed,cancelled', message: 'Le statut est invalide')]
    public $status = 'pending';

    #[Validate('nullable')]
    #[Validate('numeric', message: 'La remise doit être un nombre')]
    #[Validate('min:0', message: 'La remise doit être supérieure ou égale à 0')]
    public $discount = 0;

    #[Validate('nullable')]
    #[Validate('numeric', message: 'La taxe doit être un nombre')]
    #[Validate('min:0', message: 'La taxe doit être supérieure ou égale à 0')]
    public $tax = 0;

    #[Validate('nullable')]
    #[Validate('numeric', message: 'Le montant payé doit être un nombre')]
    #[Validate('min:0', message: 'Le montant payé doit être supérieur ou égal à 0')]
    public $paid_amount = 0;

    #[Validate('nullable')]
    #[Validate('string', message: 'Les notes doivent être une chaîne de caractères')]
    #[Validate('max:1000', message: 'Les notes ne peuvent pas dépasser 1000 caractères')]
    public $notes = null;

    /**
     * Set the sale data for editing
     */
    public function setSale($sale)
    {
        $this->client_id = $sale->client_id;
        $this->sale_date = $sale->sale_date->format('Y-m-d');
        $this->payment_method = $sale->payment_method;
        $this->payment_status = $sale->payment_status;
        $this->status = $sale->status;
        $this->discount = $sale->discount;
        $this->tax = $sale->tax;
        $this->paid_amount = $sale->paid_amount;
        $this->notes = $sale->notes;
    }

    /**
     * Reset the form
     */
    public function reset(...$properties)
    {
        if (empty($properties)) {
            $this->client_id = null;
            $this->sale_date = now()->format('Y-m-d');
            $this->payment_method = 'cash';
            $this->payment_status = 'pending';
            $this->status = 'pending';
            $this->discount = 0;
            $this->tax = 0;
            $this->paid_amount = 0;
            $this->notes = null;
        } else {
            parent::reset(...$properties);
        }
    }
}
