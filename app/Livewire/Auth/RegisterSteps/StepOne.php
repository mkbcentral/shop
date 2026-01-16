<?php

namespace App\Livewire\Auth\RegisterSteps;

use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\On;
use Livewire\Component;

class StepOne extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Validation rules
     */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', Password::defaults()],
            'password_confirmation' => ['required', 'string', 'same:password'],
        ];
    }

    /**
     * Custom validation messages
     */
    protected function messages(): array
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email' => 'L\'adresse e-mail doit être valide.',
            'email.unique' => 'Cette adresse e-mail est déjà utilisée.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins :min caractères.',
            'password_confirmation.required' => 'La confirmation du mot de passe est obligatoire.',
            'password_confirmation.same' => 'Les mots de passe ne correspondent pas.',
        ];
    }

    /**
     * Load saved data when component mounts
     */
    public function mount()
    {
        $data = session('registration.step1', []);
        $this->fill($data);
    }

    /**
     * Continue to next step
     */
    public function nextStep()
    {
        $validated = $this->validate();
        // Save data to session
        session(['registration.step1' => $validated]);

        // Emit event to parent component
        $this->dispatch('step-completed', step: 1);
    }

    public function render()
    {
        return view('livewire.auth.register-steps.step-one');
    }
}
