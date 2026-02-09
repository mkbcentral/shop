<?php

namespace App\Livewire\Organization;

use App\Livewire\Forms\OrganizationForm;
use App\Models\Organization;
use App\Models\SubscriptionPlan;
use App\Services\OrganizationService;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class OrganizationEdit extends Component
{
    use WithFileUploads;

    public Organization $organization;
    public OrganizationForm $form;

    public function mount(Organization $organization): void
    {
        $this->authorize('update', $organization);

        $this->organization = $organization;
        $this->form->setOrganization($organization);
    }

    public function save(OrganizationService $service)
    {
        $this->form->validate($this->form->getRulesForUpdate($this->organization->id));

        $data = $this->form->toArray();

        // Le logo est géré séparément via updatedFormLogo()
        unset($data['logo']);

        try {
            $service->update($this->organization, $data);

            // Émettre l'événement pour rafraîchir la liste (broadcast global)
            $this->dispatch('organization-updated')->to('organization.organization-index');
            $this->dispatch('organization-updated')->to('organization.organization-show');

            session()->flash('organization_updated', true);

            return redirect()->route('organizations.show', $this->organization);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Erreur : ' . $e->getMessage(), type: 'error');
        }
    }

    public function removeLogo(OrganizationService $service): void
    {
        if ($this->form->current_logo && Storage::disk('public')->exists($this->form->current_logo)) {
            Storage::disk('public')->delete($this->form->current_logo);
        }

        $service->update($this->organization, ['logo' => null]);
        $this->form->current_logo = null;
        $this->organization->refresh();

        $this->dispatch('show-toast', message: 'Le logo a été supprimé.', type: 'success');
    }

    public function updatedFormLogo(OrganizationService $service): void
    {
        $this->validateOnly('form.logo');

        if (!$this->form->logo) {
            return;
        }

        try {
            // Supprimer l'ancien logo
            if ($this->form->current_logo && Storage::disk('public')->exists($this->form->current_logo)) {
                Storage::disk('public')->delete($this->form->current_logo);
            }

            // Stocker le nouveau logo
            $logoPath = $this->form->logo->store('organizations/logos', 'public');

            // Mettre à jour en base de données
            $service->update($this->organization, ['logo' => $logoPath]);

            // Mettre à jour les propriétés
            $this->form->current_logo = $logoPath;
            $this->organization->refresh();
            $this->form->logo = null;

            $this->dispatch('show-toast', message: 'Le logo a été mis à jour.', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Erreur lors de la mise à jour du logo.', type: 'error');
        }
    }

    public function render()
    {
        $types = [
            'individual' => 'Entrepreneur individuel',
            'company' => 'Entreprise / Société',
            'franchise' => 'Franchise',
            'cooperative' => 'Coopérative',
            'group' => 'Groupe commercial',
        ];

        $legalForms = [
            '' => 'Sélectionner...',
            'EI' => 'Entreprise Individuelle (EI)',
            'SARL' => 'SARL',
            'SA' => 'SA',
            'SAS' => 'SAS',
            'SNC' => 'SNC',
            'SCOP' => 'SCOP',
            'Autre' => 'Autre',
        ];

        $currencies = [
            'CDF' => 'Franc Congolais (CDF)',
            'USD' => 'Dollar US (USD)',
            'EUR' => 'Euro (EUR)',
        ];

        $timezones = [
            'Africa/Kinshasa' => 'Kinshasa (UTC+1)',
            'Africa/Lubumbashi' => 'Lubumbashi (UTC+2)',
        ];

        $businessActivities = [
            'retail' => 'Commerce de détail (vêtements, électronique...)',
            'food' => 'Alimentaire (restaurants, épiceries...)',
            'services' => 'Services (coiffure, esthétique, photographie...)',
            'mixed' => 'Mixte (Produits & Services)',
        ];

        // Charger les plans depuis la base de données
        $subscriptionPlans = SubscriptionPlan::active()->ordered()->get();

        return view('livewire.organization.organization-edit', [
            'types' => $types,
            'legalForms' => $legalForms,
            'currencies' => $currencies,
            'timezones' => $timezones,
            'businessActivities' => $businessActivities,
            'subscriptionPlans' => $subscriptionPlans,
        ]);
    }
}
