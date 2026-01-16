<?php

namespace App\Livewire\Forms;

use App\Enums\SubscriptionPlan;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;
use Livewire\Form;

class RegisterForm extends Form
{
    // Step 1: User Information
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    // Step 2: Organization Information
    public string $organization_name = '';
    public string $subscription_plan = 'free';

    // Navigation
    public int $currentStep = 1;

    /**
     * Disable automatic validation
     */
    public function rules(): array
    {
        // Return empty array to disable automatic validation
        // Validation will be done manually in nextStep() method
        return [];
    }

    /**
     * Validate current step
     */
    public function validateStep(): void
    {
        $rules = [];
        if ($this->currentStep === 1) {
            $rules = $this->step1Rules();
        } elseif ($this->currentStep === 2) {
            $rules = $this->step2Rules();
        }

        // Validate only if we have rules
        if (!empty($rules)) {
            $this->validate($rules);
        }
    }

    /**
     * Validate all steps for final submission
     */
    public function validateAll(): void
    {
        $allRules = array_merge(
            $this->step1Rules(),
            $this->step2Rules()
        );

        $this->validate($allRules);
    }

    /**
     * Step 1 validation rules
     */
    protected function step1Rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', Password::defaults()],
            'password_confirmation' => ['required', 'string', 'same:password'],
        ];
    }

    /**
     * Step 2 validation rules
     */
    protected function step2Rules(): array
    {
        return [
            'organization_name' => ['required', 'string', 'max:255'],
            'subscription_plan' => ['required', Rule::in(array_column(SubscriptionPlan::cases(), 'value'))],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'name.string' => 'Le nom doit être une chaîne de caractères.',
            'name.max' => 'Le nom ne doit pas dépasser 255 caractères.',
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.string' => 'L\'adresse e-mail doit être une chaîne de caractères.',
            'email.email' => 'Veuillez fournir une adresse e-mail valide.',
            'email.max' => 'L\'adresse e-mail ne doit pas dépasser 255 caractères.',
            'email.unique' => 'Cette adresse e-mail est déjà utilisée.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.string' => 'Le mot de passe doit être une chaîne de caractères.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'password_confirmation.required' => 'La confirmation du mot de passe est obligatoire.',
            'organization_name.required' => 'Le nom de l\'organisation est obligatoire.',
            'organization_name.string' => 'Le nom de l\'organisation doit être une chaîne de caractères.',
            'organization_name.max' => 'Le nom de l\'organisation ne doit pas dépasser 255 caractères.',
            'subscription_plan.required' => 'Veuillez choisir un plan d\'abonnement.',
            'subscription_plan.in' => 'Le plan d\'abonnement sélectionné n\'est pas valide.',
        ];
    }

    /**
     * Get custom attribute names.
     */
    public function validationAttributes(): array
    {
        return [
            'name' => 'nom',
            'email' => 'adresse e-mail',
            'password' => 'mot de passe',
            'password_confirmation' => 'confirmation du mot de passe',
            'organization_name' => 'nom de l\'organisation',
            'subscription_plan' => 'plan d\'abonnement',
        ];
    }

    /**
     * Get data for user creation.
     */
    public function userData(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }

    /**
     * Reset the form fields.
     */
    public function resetForm(): void
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
    }
}
