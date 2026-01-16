<?php

namespace App\Livewire\Organization;

use App\Enums\PaymentStatus;
use App\Enums\SubscriptionPlan;
use App\Models\Organization;
use App\Services\SubscriptionService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class PaymentModal extends Component
{
    public ?Organization $organization = null;
    public bool $showModal = false;
    public string $paymentMethod = 'stripe';
    public array $planData = [];
    public string $currency = '€';

    public function mount()
    {
        $user = Auth::user();
        
        if (!$user) {
            return;
        }

        $this->organization = $user->defaultOrganization;
        
        if (!$this->organization) {
            return;
        }

        // Check if payment is needed
        if (!$this->organization->isAccessible()) {
            $this->showModal = true;
            
            // Load plan data from cache
            $allPlans = SubscriptionService::getPlansFromCache();
            $planSlug = $this->organization->subscription_plan->value;
            $this->planData = $allPlans[$planSlug] ?? [];
            $this->currency = SubscriptionService::getCurrencyFromCache();
        }
    }

    /**
     * Process payment (simulation for now)
     */
    public function processPayment()
    {
        if (!$this->organization) {
            return;
        }

        // TODO: Intégrer Stripe/PayPal ici
        // Pour le moment, on simule un paiement réussi
        $paymentReference = 'PAY_' . strtoupper(uniqid());

        $this->organization->markPaymentCompleted(
            $paymentReference,
            $this->paymentMethod
        );

        $this->showModal = false;

        session()->flash('success', 'Paiement effectué avec succès ! Bienvenue à bord 🎉');

        // Refresh the page to update the dashboard
        return redirect()->route('dashboard');
    }

    /**
     * Cancel and use free plan instead
     */
    public function useFreePlan()
    {
        if (!$this->organization) {
            return;
        }

        // Récupérer les limites du plan gratuit depuis le cache/DB
        $plans = SubscriptionService::getPlansFromCache();
        $freePlan = $plans['free'] ?? [];

        $this->organization->update([
            'subscription_plan' => SubscriptionPlan::FREE,
            'payment_status' => PaymentStatus::COMPLETED,
            'max_stores' => $freePlan['max_stores'] ?? 1,
            'max_users' => $freePlan['max_users'] ?? 3,
            'max_products' => $freePlan['max_products'] ?? 100,
        ]);

        // Assigner les rôles admin et manager au propriétaire
        $this->organization->assignOwnerRolesAndMenus();

        $this->showModal = false;

        session()->flash('success', 'Vous utilisez maintenant le plan gratuit. Vous pouvez passer à un plan supérieur à tout moment.');

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.organization.payment-modal');
    }
}
