<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class OrganizationForm extends Form
{
    #[Validate('required', message: 'Le nom de l\'organisation est requis')]
    #[Validate('string', message: 'Le nom doit être une chaîne de caractères')]
    #[Validate('max:255', message: 'Le nom ne peut pas dépasser 255 caractères')]
    #[Validate('unique:organizations,name', message: 'Ce nom d\'organisation existe déjà')]
    public $name = '';

    #[Validate('required', message: 'Le type d\'organisation est requis')]
    #[Validate('in:individual,company,franchise,cooperative,group', message: 'Le type sélectionné n\'est pas valide')]
    public $type = 'company';

    #[Validate('required', message: 'Le type d\'activité est requis')]
    #[Validate('in:retail,food,services,mixed', message: 'Le type d\'activité sélectionné n\'est pas valide')]
    public $business_activity = 'retail';

    #[Validate('nullable')]
    #[Validate('string', message: 'La raison sociale doit être une chaîne de caractères')]
    #[Validate('max:255', message: 'La raison sociale ne peut pas dépasser 255 caractères')]
    public $legal_name = '';

    #[Validate('nullable')]
    #[Validate('string', message: 'La forme juridique doit être une chaîne de caractères')]
    #[Validate('max:100', message: 'La forme juridique ne peut pas dépasser 100 caractères')]
    public $legal_form = '';

    #[Validate('nullable')]
    #[Validate('string', message: 'Le NIF/RCCM doit être une chaîne de caractères')]
    #[Validate('max:100', message: 'Le NIF/RCCM ne peut pas dépasser 100 caractères')]
    public $tax_id = '';

    #[Validate('nullable')]
    #[Validate('string', message: 'Le numéro d\'immatriculation doit être une chaîne de caractères')]
    #[Validate('max:100', message: 'Le numéro d\'immatriculation ne peut pas dépasser 100 caractères')]
    public $registration_number = '';

    #[Validate('nullable')]
    #[Validate('email', message: 'L\'adresse email doit être valide')]
    #[Validate('max:255', message: 'L\'email ne peut pas dépasser 255 caractères')]
    public $email = '';

    #[Validate('nullable')]
    #[Validate('string', message: 'Le téléphone doit être une chaîne de caractères')]
    #[Validate('max:50', message: 'Le téléphone ne peut pas dépasser 50 caractères')]
    public $phone = '';

    #[Validate('nullable')]
    #[Validate('string', message: 'L\'adresse doit être une chaîne de caractères')]
    #[Validate('max:500', message: 'L\'adresse ne peut pas dépasser 500 caractères')]
    public $address = '';

    #[Validate('nullable')]
    #[Validate('string', message: 'La ville doit être une chaîne de caractères')]
    #[Validate('max:100', message: 'La ville ne peut pas dépasser 100 caractères')]
    public $city = '';

    #[Validate('required', message: 'Le pays est requis')]
    #[Validate('string', message: 'Le pays doit être une chaîne de caractères')]
    #[Validate('size:2', message: 'Le code pays doit contenir exactement 2 caractères')]
    public $country = 'CD';

    #[Validate('nullable')]
    #[Validate('image', message: 'Le fichier doit être une image')]
    #[Validate('max:2048', message: 'L\'image ne peut pas dépasser 2 Mo')]
    public $logo;

    #[Validate('nullable')]
    #[Validate('url', message: 'Le site web doit être une URL valide')]
    #[Validate('max:255', message: 'Le site web ne peut pas dépasser 255 caractères')]
    public $website = '';

    #[Validate('required', message: 'La devise est requise')]
    #[Validate('string', message: 'La devise doit être une chaîne de caractères')]
    #[Validate('size:3', message: 'Le code devise doit contenir exactement 3 caractères')]
    public $currency = 'USD';

    #[Validate('required', message: 'Le fuseau horaire est requis')]
    #[Validate('string', message: 'Le fuseau horaire doit être une chaîne de caractères')]
    #[Validate('max:50', message: 'Le fuseau horaire ne peut pas dépasser 50 caractères')]
    public $timezone = 'Africa/Kinshasa';

    #[Validate('required', message: 'Le plan d\'abonnement est requis')]
    #[Validate('in:free,starter,professional,enterprise', message: 'Le plan d\'abonnement sélectionné n\'est pas valide')]
    public $subscription_plan = 'free';

    public $current_logo = null;

    /**
     * Set the organization data for editing
     */
    public function setOrganization($organization)
    {
        $this->name = $organization->name;
        $this->type = $organization->type;
        // Convertir l'enum business_activity en string pour la validation
        $this->business_activity = $organization->business_activity instanceof \App\Enums\BusinessActivityType
            ? $organization->business_activity->value
            : ($organization->business_activity ?? 'retail');
        $this->legal_name = $organization->legal_name ?? '';
        $this->legal_form = $organization->legal_form ?? '';
        $this->tax_id = $organization->tax_id ?? '';
        $this->registration_number = $organization->registration_number ?? '';
        $this->email = $organization->email ?? '';
        $this->phone = $organization->phone ?? '';
        $this->address = $organization->address ?? '';
        $this->city = $organization->city ?? '';
        $this->country = $organization->country;
        $this->website = $organization->website ?? '';
        $this->currency = $organization->currency;
        $this->timezone = $organization->timezone;
        // Convertir l'enum en string pour la validation
        $this->subscription_plan = $organization->subscription_plan instanceof \App\Enums\SubscriptionPlan
            ? $organization->subscription_plan->value
            : $organization->subscription_plan;
        $this->current_logo = $organization->logo;
    }

    /**
     * Reset the form
     */
    public function reset(...$properties)
    {
        if (empty($properties)) {
            $this->name = '';
            $this->type = 'company';
            $this->business_activity = 'retail';
            $this->legal_name = '';
            $this->legal_form = '';
            $this->tax_id = '';
            $this->registration_number = '';
            $this->email = '';
            $this->phone = '';
            $this->address = '';
            $this->city = '';
            $this->country = 'CD';
            $this->logo = null;
            $this->website = '';
            $this->currency = 'USD';
            $this->timezone = 'Africa/Kinshasa';
            $this->subscription_plan = 'free';
            $this->current_logo = null;
        } else {
            parent::reset(...$properties);
        }
    }

    /**
     * Get validation rules for update (with dynamic name unique rule)
     */
    public function getRulesForUpdate($organizationId)
    {
        return [
            'name' => 'required|string|max:255|unique:organizations,name,' . $organizationId,
            'type' => 'required|in:individual,company,franchise,cooperative,group',
            'business_activity' => 'required|in:retail,food,services,mixed',
            'legal_name' => 'nullable|string|max:255',
            'legal_form' => 'nullable|string|max:100',
            'tax_id' => 'nullable|string|max:100',
            'registration_number' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'required|string|size:2',
            'logo' => 'nullable|image|max:2048',
            'website' => 'nullable|url|max:255',
            'currency' => 'required|string|size:3',
            'timezone' => 'required|string|max:50',
            'subscription_plan' => 'required|in:free,starter,professional,enterprise',
        ];
    }

    /**
     * Get all data as array
     */
    public function toArray()
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'business_activity' => $this->business_activity,
            'legal_name' => $this->legal_name ?: null,
            'legal_form' => $this->legal_form ?: null,
            'tax_id' => $this->tax_id ?: null,
            'registration_number' => $this->registration_number ?: null,
            'email' => $this->email ?: null,
            'phone' => $this->phone ?: null,
            'address' => $this->address ?: null,
            'city' => $this->city ?: null,
            'country' => $this->country,
            'website' => $this->website ?: null,
            'currency' => $this->currency,
            'timezone' => $this->timezone,
            'subscription_plan' => $this->subscription_plan,
        ];
    }
}
