<?php

namespace App\Livewire\Auth;

use App\Livewire\Forms\LoginForm;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Login extends Component
{
    // Direct properties instead of Form Object
    public LoginForm $form;

    public ?string $errorMessage = null;
    public ?string $successMessage = null;

    public int $maxAttempts = 5;
    public int $remainingAttempts = 5;
    public int $lockoutSeconds = 0;
    public bool $isLocked = false;

    /**
     * Attempt to authenticate the request's credentials.
     */
    public function login()
    {
        // Reset messages
        $this->errorMessage = null;
        $this->successMessage = null;

        // Update attempt status
        $this->updateAttemptStatus();

        // Check if locked out
        if ($this->isLocked) {
            return;
        }

        // Validate inputs
        $this->validate();

        // Check rate limiting
        $this->ensureIsNotRateLimited();

        // Check if user exists
        $user = User::where('email', $this->form->email)->first();
        if (!$user) {
            RateLimiter::hit($this->throttleKey());
            $this->updateAttemptStatus();
            $this->errorMessage = 'Aucun compte n\'existe avec cette adresse e-mail.';
            $this->addError('email', $this->errorMessage);
            return;
        }

        // Check if user is active
        if (isset($user->is_active) && !$user->is_active) {
            $this->errorMessage = 'Votre compte a été désactivé. Veuillez contacter l\'administrateur.';
            $this->addError('email', $this->errorMessage);
            return;
        }

        // Attempt authentication
        if (Auth::attempt(['email' => $this->form->email, 'password' => $this->form->password], $this->form->remember)) {
            RateLimiter::clear($this->throttleKey());

            request()->session()->regenerate();

            $this->successMessage = 'Connexion réussie ! Redirection en cours...';
            $this->dispatch('show-toast', message: $this->successMessage, type: 'success');
            //check if user is super admin and redirect accordingly
            if ($user->isSuperAdmin()) {
                return $this->redirectIntended(route('admin.dashboard', absolute: false));
            }
            return $this->redirectIntended(route('dashboard', absolute: false));
        }

        // Authentication failed
        RateLimiter::hit($this->throttleKey());
        $this->updateAttemptStatus();

        $this->errorMessage = 'Le mot de passe est incorrect.';
        $this->addError('password', $this->errorMessage);
    }

    /**
     * Update the attempt status for display
     */
    protected function updateAttemptStatus(): void
    {
        $attempts = RateLimiter::attempts($this->throttleKey());
        $this->remainingAttempts = max(0, $this->maxAttempts - $attempts);

        if (RateLimiter::tooManyAttempts($this->throttleKey(), $this->maxAttempts)) {
            $this->isLocked = true;
            $this->lockoutSeconds = RateLimiter::availableIn($this->throttleKey());
            $minutes = ceil($this->lockoutSeconds / 60);
            $this->errorMessage = "Trop de tentatives de connexion. Veuillez réessayer dans {$minutes} minute(s).";
        } else {
            $this->isLocked = false;
            $this->lockoutSeconds = 0;
        }
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), $this->maxAttempts)) {
            return;
        }

        $this->updateAttemptStatus();

        throw ValidationException::withMessages([
            'email' => $this->errorMessage,
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(string: Str::lower($this->form->email) . '|' . request()->ip());
    }

    /**
     * Clear error message when email changes
     */
    public function updatedEmail()
    {
        $this->errorMessage = null;
        $this->resetErrorBag('email');
    }

    /**
     * Clear error message when password changes
     */
    public function updatedPassword()
    {
        $this->errorMessage = null;
        $this->resetErrorBag('password');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
