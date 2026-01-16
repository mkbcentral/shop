<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class VerifyEmail extends Component
{
    public function mount()
    {
        try {
            Log::info('=== Début resendVerification ===');

            if (Auth::user()->hasVerifiedEmail()) {
                Log::info('Email déjà vérifié, redirection vers dashboard');
                return redirect()->intended(route('dashboard', absolute: false));
            }

            $user = Auth::user();
            Log::info('Utilisateur: ' . $user->email . ' (ID: ' . $user->id . ')');

            $user->sendEmailVerificationNotification();

            Log::info('✓ Email de vérification envoyé avec succès');

            session()->flash('status', 'verification-link-sent');

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Un nouveau lien de vérification a été envoyé à votre adresse e-mail.'
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Erreur lors de l\'envoi: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Une erreur est survenue lors de l\'envoi de l\'email: ' . $e->getMessage()
            ]);
        }
    }

    public function resendVerification()
    {


        try {
            Log::info('=== Début resendVerification ===');

            if (Auth::user()->hasVerifiedEmail()) {
                Log::info('Email déjà vérifié, redirection vers dashboard');
                return redirect()->intended(route('dashboard', absolute: false));
            }

            $user = Auth::user();
            Log::info('Utilisateur: ' . $user->email . ' (ID: ' . $user->id . ')');

            $user->sendEmailVerificationNotification();

            Log::info('✓ Email de vérification envoyé avec succès');

            session()->flash('status', 'verification-link-sent');

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Un nouveau lien de vérification a été envoyé à votre adresse e-mail.'
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Erreur lors de l\'envoi: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Une erreur est survenue lors de l\'envoi de l\'email: ' . $e->getMessage()
            ]);
        }
    }

    public function logout()
    {
        Auth::guard('web')->logout();

        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.auth.verify-email');
    }
}
