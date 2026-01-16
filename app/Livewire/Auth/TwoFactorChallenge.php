<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Validate;
use Livewire\Component;

class TwoFactorChallenge extends Component
{
    #[Validate(onUpdate: false)]
    public string $code = '';

    #[Validate(onUpdate: false)]
    public string $recovery_code = '';

    public bool $useRecoveryCode = false;

    protected function rules()
    {
        return $this->useRecoveryCode
            ? ['recovery_code' => 'required|string']
            : ['code' => 'required|string'];
    }

    public function authenticate()
    {
        $this->validate();

        $challengedUser = session('login.id');

        if (!$challengedUser) {
            return redirect()->route('login');
        }

        if ($this->useRecoveryCode) {
            // La validation du recovery code est gérée par Fortify
            session(['auth.recovery_code' => $this->recovery_code]);
        } else {
            // La validation du code 2FA est gérée par Fortify
            session(['auth.two_factor_code' => $this->code]);
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function toggleRecoveryCode()
    {
        $this->useRecoveryCode = !$this->useRecoveryCode;
        $this->code = '';
        $this->recovery_code = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.auth.two-factor-challenge');
    }
}
