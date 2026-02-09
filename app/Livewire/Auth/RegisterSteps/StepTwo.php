<?php

namespace App\Livewire\Auth\RegisterSteps;

use App\Enums\BusinessActivityType;
use App\Services\SubscriptionService;
use Illuminate\Validation\Rule;
use Livewire\Component;

class StepTwo extends Component
{
    public string $organization_name = '';
    public string $organization_phone = '';
    public string $business_activity = 'retail';
    public string $subscription_plan = 'free';
    public array $plans = [];
    public array $businessActivities = [];
    public string $currency = '€';

    /**
     * Validation rules
     */
    protected function rules(): array
    {
        $plans = SubscriptionService::getPlansFromCache();
        $planSlugs = array_keys($plans);
        $activityValues = array_column(BusinessActivityType::cases(), 'value');

        return [
            'organization_name' => ['required', 'string', 'max:255'],
            'organization_phone' => ['required', 'string', 'max:20'],
            'business_activity' => ['required', Rule::in($activityValues)],
            'subscription_plan' => ['required', Rule::in($planSlugs)],
        ];
    }

    /**
     * Custom validation messages
     */
    protected function messages(): array
    {
        return [
            'organization_name.required' => 'Le nom de l\'organisation est obligatoire.',
            'organization_name.max' => 'Le nom de l\'organisation ne doit pas dépasser 255 caractères.',
            'organization_phone.required' => 'Le téléphone de l\'organisation est obligatoire.',
            'organization_phone.max' => 'Le téléphone de l\'organisation ne doit pas dépasser 20 caractères.',
            'business_activity.required' => 'Vous devez choisir un type d\'activité.',
            'business_activity.in' => 'Le type d\'activité sélectionné n\'est pas valide.',
            'subscription_plan.required' => 'Vous devez choisir un plan.',
            'subscription_plan.in' => 'Le plan sélectionné n\'est pas valide.',
        ];
    }

    /**
     * Load saved data and plans
     */
    public function mount()
    {
        // Charger les plans depuis le cache (même logique que welcome page)
        $this->plans = SubscriptionService::getPlansFromCache();
        $this->currency = SubscriptionService::getCurrencyFromCache();

        // Charger les types d'activités commerciales
        $this->businessActivities = BusinessActivityType::options();

        $data = session('registration.step2', []);
        $this->fill($data);
    }

    /**
     * Continue to next step
     */
    public function nextStep()
    {
        $validated = $this->validate();

        // Save data to session
        session(['registration.step2' => $validated]);

        // Emit event to parent component
        $this->dispatch('step-completed', step: 2);
    }

    /**
     * Go back to previous step
     */
    public function previousStep()
    {
        $this->dispatch('go-back', step: 1);
    }

    public function render()
    {
        return view('livewire.auth.register-steps.step-two');
    }
}
