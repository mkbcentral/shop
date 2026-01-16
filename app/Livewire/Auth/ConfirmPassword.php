<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ConfirmPassword extends Component
{
    #[Validate('required', message: 'Le mot de passe est requis.')]
    public string $password = '';

    public function confirmPassword()
    {
        $this->validate();

        if (!Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => 'Le mot de passe fourni est incorrect.',
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function render()
    {
        return view('livewire.auth.confirm-password');
    }
}
