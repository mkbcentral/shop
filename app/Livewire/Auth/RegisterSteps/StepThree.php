<?php

namespace App\Livewire\Auth\RegisterSteps;

use App\Services\SubscriptionService;
use Livewire\Component;

class StepThree extends Component
{
    public array $userData = [];
    public array $organizationData = [];
    public array $plan = [];
    public string $planSlug = '';
    public string $currency = '€';

    /**
     * Load saved data
     */
    public function mount()
    {
        $this->userData = session('registration.step1', []);
        $this->organizationData = session('registration.step2', []);
        $this->currency = SubscriptionService::getCurrencyFromCache();

        if (isset($this->organizationData['subscription_plan'])) {
            $this->planSlug = $this->organizationData['subscription_plan'];
            $allPlans = SubscriptionService::getPlansFromCache();
            $this->plan = $allPlans[$this->planSlug] ?? [];
        }
    }

    /**
     * Complete registration
     */
    public function complete()
    {
        // Dispatch event to parent to handle final registration
        $this->dispatch('complete-registration');
    }

    /**
     * Go back to previous step
     */
    public function previousStep()
    {
        $this->dispatch('go-back', step: 2);
    }

    public function render()
    {
        return view('livewire.auth.register-steps.step-three');
    }
}
