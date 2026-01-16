<?php

namespace App\Livewire\Forms;

use App\Models\Supplier;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SupplierForm extends Form
{
    public ?int $supplierId = null;

    #[Validate('required', message: 'Le nom du fournisseur est obligatoire.')]
    #[Validate('string', message: 'Le nom doit être une chaîne de caractères.')]
    #[Validate('max:255', message: 'Le nom ne peut pas dépasser 255 caractères.')]
    public string $name = '';

    #[Validate('nullable')]
    #[Validate('string', message: 'Le téléphone doit être une chaîne de caractères.')]
    #[Validate('max:20', message: 'Le téléphone ne peut pas dépasser 20 caractères.')]
    public ?string $phone = null;

    #[Validate('nullable')]
    #[Validate('email', message: 'L\'adresse email n\'est pas valide.')]
    #[Validate('max:255', message: 'L\'email ne peut pas dépasser 255 caractères.')]
    public ?string $email = null;

    #[Validate('nullable')]
    #[Validate('string', message: 'L\'adresse doit être une chaîne de caractères.')]
    #[Validate('max:500', message: 'L\'adresse ne peut pas dépasser 500 caractères.')]
    public ?string $address = null;

    /**
     * Set the supplier data for editing
     */
    public function setSupplier(Supplier $supplier): void
    {
        $this->supplierId = $supplier->id;
        $this->name = $supplier->name ?? '';
        $this->phone = $supplier->phone;
        $this->email = $supplier->email;
        $this->address = $supplier->address;
    }

    /**
     * Get the data as an array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'phone' => $this->phone ?: null,
            'email' => $this->email ?: null,
            'address' => $this->address ?: null,
        ];
    }

    /**
     * Reset the form
     */
    public function reset(...$properties): void
    {
        if (empty($properties)) {
            $this->supplierId = null;
            $this->name = '';
            $this->phone = null;
            $this->email = null;
            $this->address = null;
        } else {
            parent::reset(...$properties);
        }
    }
}
