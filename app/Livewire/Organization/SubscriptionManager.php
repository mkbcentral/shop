<?php

namespace App\Livewire\Organization;

use App\Models\Organization;
use App\Models\SubscriptionPayment;
use App\Services\SubscriptionService;
use Livewire\Component;
use Livewire\WithPagination;

class SubscriptionManager extends Component
{
    use WithPagination;

    public Organization $organization;
    public array $availablePlans = [];
    public $subscriptionHistory;
    public string $subscriptionStatus = 'active';
    public int $daysUntilExpiration = 0;

    // Modal states
    public bool $showSubscribeModal = false;
    public bool $showReactivateModal = false;
    public bool $showRenewModal = false;
    public bool $showCancelModal = false;

    // Form data
    public string $selectedPlan = '';
    public string $billingPeriod = 'monthly';

    protected $listeners = ['subscription-updated' => 'loadData'];

    public function mount(Organization $organization): void
    {
        $this->authorize('manageSubscription', $organization);

        $this->organization = $organization;
        $this->loadData();
    }

    public function loadData(): void
    {
        $this->organization = $this->organization->fresh();

        // Charger les plans disponibles pour souscription
        $subscriptionService = app(SubscriptionService::class);
        $allPlans = $subscriptionService->getAvailablePlans();

        // Préparer les plans disponibles avec les détails
        $this->availablePlans = [];
        foreach ($allPlans as $planKey => $planData) {
            if ($planKey === 'free') continue; // Ne pas afficher le plan gratuit

            $this->availablePlans[$planKey] = [
                'name' => $planData['name'] ?? ucfirst($planKey),
                'description' => $this->getPlanDescription($planKey),
                'monthly_price' => $planData['monthly_price'] ?? 0,
                'yearly_price' => $planData['yearly_price'] ?? 0,
                'features' => $subscriptionService->getPlanFeatures($planKey),
            ];
        }

        // Charger l'historique des abonnements
        $this->subscriptionHistory = SubscriptionPayment::where('organization_id', $this->organization->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Déterminer le statut de l'abonnement
        $this->determineSubscriptionStatus();
    }

    protected function getPlanDescription(string $plan): string
    {
        return match($plan) {
            'starter' => 'Idéal pour les petites entreprises qui démarrent',
            'professional' => 'Pour les entreprises en croissance avec plusieurs magasins',
            'enterprise' => 'Solution complète pour les grandes organisations',
            default => '',
        };
    }

    protected function determineSubscriptionStatus(): void
    {
        if ($this->organization->subscription_plan === 'free') {
            $this->subscriptionStatus = 'free';
            $this->daysUntilExpiration = 0;
            return;
        }

        if (!$this->organization->subscription_end_date) {
            $this->subscriptionStatus = 'active';
            $this->daysUntilExpiration = 0;
            return;
        }

        $now = now();
        $endDate = $this->organization->subscription_end_date;

        if ($endDate->isPast()) {
            $this->subscriptionStatus = 'expired';
            $this->daysUntilExpiration = 0;
        } elseif ($endDate->diffInDays($now) <= 7) {
            $this->subscriptionStatus = 'expiring_soon';
            $this->daysUntilExpiration = (int) $endDate->diffInDays($now);
        } else {
            $this->subscriptionStatus = 'active';
            $this->daysUntilExpiration = (int) $endDate->diffInDays($now);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Subscribe to a plan
    |--------------------------------------------------------------------------
    */

    public function selectPlan(string $plan): void
    {
        $this->selectedPlan = $plan;
        $this->billingPeriod = 'monthly';
        $this->showSubscribeModal = true;
    }

    public function confirmSubscription(): void
    {
        $this->validate([
            'selectedPlan' => 'required|in:starter,professional,enterprise',
            'billingPeriod' => 'required|in:monthly,yearly',
        ]);

        try {
            $subscriptionService = app(SubscriptionService::class);
            $duration = $this->billingPeriod === 'monthly' ? 1 : 12;

            $subscriptionService->upgrade(
                $this->organization,
                $this->selectedPlan,
                $duration,
                'mobile_money' // Méthode de paiement par défaut
            );

            $this->showSubscribeModal = false;
            $this->loadData();

            $this->dispatch('show-toast', [
                'message' => 'Abonnement activé avec succès !',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Erreur : ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Reactivate expired subscription
    |--------------------------------------------------------------------------
    */

    public function confirmReactivation(): void
    {
        $this->validate([
            'billingPeriod' => 'required|in:monthly,yearly',
        ]);

        try {
            $subscriptionService = app(SubscriptionService::class);
            $duration = $this->billingPeriod === 'monthly' ? 1 : 12;

            $subscriptionService->reactivate(
                $this->organization,
                $duration,
                'mobile_money'
            );

            $this->showReactivateModal = false;
            $this->loadData();

            $this->dispatch('show-toast', [
                'message' => 'Abonnement réactivé avec succès !',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Erreur : ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Renew active subscription
    |--------------------------------------------------------------------------
    */

    public function confirmRenewal(): void
    {
        $this->validate([
            'billingPeriod' => 'required|in:monthly,yearly',
        ]);

        try {
            $subscriptionService = app(SubscriptionService::class);
            $duration = $this->billingPeriod === 'monthly' ? 1 : 12;

            $subscriptionService->renew(
                $this->organization,
                $duration,
                'mobile_money'
            );

            $this->showRenewModal = false;
            $this->loadData();

            $this->dispatch('show-toast', [
                'message' => 'Abonnement renouvelé avec succès !',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Erreur : ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Cancel subscription
    |--------------------------------------------------------------------------
    */

    public function confirmCancellation(): void
    {
        try {
            $subscriptionService = app(SubscriptionService::class);

            // Annuler sans immédiat (l'abonnement reste actif jusqu'à la fin)
            $subscriptionService->cancel(
                $this->organization,
                false, // cancelImmediately = false
                'Cancelled by user from subscription manager'
            );

            $this->showCancelModal = false;
            $this->loadData();

            $this->dispatch('show-toast', [
                'message' => 'Annulation planifiée. Votre abonnement restera actif jusqu\'à son expiration.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Erreur : ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        return view('livewire.organization.subscription-manager');
    }
}
