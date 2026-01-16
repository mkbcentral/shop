<?php

namespace App\Livewire\Organization;

use App\Enums\PaymentStatus;
use App\Enums\SubscriptionPlan;
use App\Models\Organization;
use App\Services\SubscriptionService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class OrganizationPayment extends Component
{
    public Organization $organization;
    public $paymentMethod = 'stripe';
    public array $planData = [];
    public string $currency = '€';

    public function mount($organization)
    {
        \Log::info('OrganizationPayment mount called', [
            'organization_param' => $organization,
            'auth_id' => Auth::id(),
        ]);
        
        $this->organization = Organization::findOrFail($organization);

        \Log::info('Organization loaded', [
            'org_id' => $this->organization->id,
            'org_name' => $this->organization->name,
            'owner_id' => $this->organization->owner_id,
        ]);

        // Check if user owns this organization
        if ($this->organization->owner_id !== Auth::id()) {
            \Log::warning('Ownership check failed', [
                'org_owner' => $this->organization->owner_id,
                'current_user' => Auth::id(),
            ]);
            abort(403, 'Accès non autorisé');
        }

        // If already paid, redirect to dashboard
        if ($this->organization->isAccessible()) {
            return redirect()->route('dashboard')
                ->with('success', 'Votre organisation est déjà active.');
        }

        // Load plan data from cache
        $allPlans = SubscriptionService::getPlansFromCache();
        $planSlug = $this->organization->subscription_plan->value;
        $this->planData = $allPlans[$planSlug] ?? [];
        $this->currency = SubscriptionService::getCurrencyFromCache();
    }

    /**
     * Process payment (simulation for now)
     */
    public function processPayment()
    {
        // TODO: Intégrer Stripe/PayPal ici
        // Pour le moment, on simule un paiement réussi

        $paymentReference = 'PAY_' . strtoupper(uniqid());

        $this->organization->markPaymentCompleted(
            $paymentReference,
            $this->paymentMethod
        );

        session()->flash('success', 'Paiement effectué avec succès ! Bienvenue à bord 🎉');

        return redirect()->route('dashboard');
    }

    /**
     * Cancel and use free plan instead
     */
    public function useFreePlan()
    {
        $this->organization->update([
            'subscription_plan' => SubscriptionPlan::FREE,
            'payment_status' => PaymentStatus::COMPLETED,
            'is_active' => true,
        ]);

        session()->flash('info', 'Votre compte a été migré vers le plan gratuit.');

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.organization.organization-payment');
    }
}
