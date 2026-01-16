<?php

namespace App\Livewire\Forms;

use App\Models\Store;
use Livewire\Form;

class StoreForm extends Form
{
    public $name = '';
    public $code = '';
    public $address = '';
    public $phone = '';
    public $email = '';
    public $is_main = false;
    public $is_active = true;
    public ?int $organization_id = null;

    /**
     * Validation rules
     */
    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'is_main' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Validation messages in French
     */
    protected function messages(): array
    {
        return [
            'name.required' => 'Le nom du magasin est obligatoire.',
            'name.string' => 'Le nom du magasin doit être une chaîne de caractères.',
            'name.max' => 'Le nom du magasin ne peut pas dépasser 255 caractères.',
            'code.string' => 'Le code du magasin doit être une chaîne de caractères.',
            'code.max' => 'Le code du magasin ne peut pas dépasser 255 caractères.',
            'address.string' => 'L\'adresse doit être une chaîne de caractères.',
            'address.max' => 'L\'adresse ne peut pas dépasser 500 caractères.',
            'phone.string' => 'Le téléphone doit être une chaîne de caractères.',
            'phone.max' => 'Le téléphone ne peut pas dépasser 20 caractères.',
            'email.email' => 'L\'adresse email n\'est pas valide.',
            'email.max' => 'L\'email ne peut pas dépasser 255 caractères.',
            'is_main.boolean' => 'La valeur de magasin principal doit être vrai ou faux.',
            'is_active.boolean' => 'La valeur de statut actif doit être vrai ou faux.',
        ];
    }

    /**
     * Set form data from an existing store
     */
    public function setStore(Store $store): void
    {
        $this->name = $store->name;
        $this->code = $store->code ?? '';
        $this->address = $store->address ?? '';
        $this->phone = $store->phone ?? '';
        $this->email = $store->email ?? '';
        $this->is_main = $store->is_main ?? false;
        $this->is_active = $store->is_active ?? true;
        $this->organization_id = $store->organization_id;
    }

    /**
     * Reset the form
     */
    public function reset(...$properties): void
    {
        $this->name = '';
        $this->code = '';
        $this->address = '';
        $this->phone = '';
        $this->email = '';
        $this->is_main = false;
        $this->is_active = true;
        $this->organization_id = null;

        $this->resetValidation();
    }

    /**
     * Get validation rules for update (exclude current store from unique check)
     */
    public function getRulesForUpdate(int $storeId): array
    {
        return [
            'name' => "required|string|max:255|unique:stores,name,{$storeId}",
            'code' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'is_main' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get form data as array for DTO
     * Note: 'code' is only included for creation (when set), not for updates
     */
    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
            'address' => $this->address ?: null,
            'phone' => $this->phone ?: null,
            'email' => $this->email ?: null,
            'is_main' => $this->is_main,
            'is_active' => $this->is_active,
            'organization_id' => $this->organization_id,
        ];

        return $data;
    }
}
