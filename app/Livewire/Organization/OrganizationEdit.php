<?php

namespace App\Livewire\Organization;

use App\Livewire\Forms\OrganizationForm;
use App\Models\Organization;
use App\Services\OrganizationService;
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

        // Upload du nouveau logo si fourni
        if ($this->form->logo) {
            // Supprimer l'ancien logo
            if ($this->form->current_logo && \Storage::disk('public')->exists($this->form->current_logo)) {
                \Storage::disk('public')->delete($this->form->current_logo);
            }
            $data['logo'] = $this->form->logo->store('organizations/logos', 'public');
        }

        try {
            $service->update($this->organization, $data);

            // Émettre l'événement pour rafraîchir la liste (broadcast global)
            $this->dispatch('organization-updated')->to('organization.organization-index');
            $this->dispatch('organization-updated')->to('organization.organization-show');

            session()->flash('success', 'L\'organisation a été mise à jour avec succès !');

            return $this->redirect(route('organizations.show', $this->organization), navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Erreur : ' . $e->getMessage(), type: 'error');
        }
    }

    public function removeLogo(OrganizationService $service): void
    {
        if ($this->form->current_logo && \Storage::disk('public')->exists($this->form->current_logo)) {
            \Storage::disk('public')->delete($this->form->current_logo);
        }

        $service->update($this->organization, ['logo' => null]);
        $this->form->current_logo = null;

        session()->flash('success', 'Le logo a été supprimé.');
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

        return view('livewire.organization.organization-edit', [
            'types' => $types,
            'legalForms' => $legalForms,
            'currencies' => $currencies,
            'timezones' => $timezones,
        ]);
    }
}
