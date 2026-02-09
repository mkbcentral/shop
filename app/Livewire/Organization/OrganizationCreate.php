<?php

namespace App\Livewire\Organization;

use App\Livewire\Forms\OrganizationForm;
use App\Services\OrganizationService;
use Livewire\Component;
use Livewire\WithFileUploads;

class OrganizationCreate extends Component
{
    use WithFileUploads;

    public OrganizationForm $form;

    public function save(OrganizationService $service)
    {
        $this->form->validate();

        $data = $this->form->toArray();

        // Upload du logo si fourni
        if ($this->form->logo) {
            $data['logo'] = $this->form->logo->store('organizations/logos', 'public');
        }

        try {
            $organization = $service->create($data, auth()->user());

            // Définir comme organisation courante
            session(['current_organization_id' => $organization->id]);

            // Émettre l'événement pour rafraîchir la liste (broadcast global)
            $this->dispatch('organization-created')->to('organization.organization-index');

            session()->flash('success', "L'organisation \"{$organization->name}\" a été créée avec succès !");

            return $this->redirect(route('organizations.index'), navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Erreur : ' . $e->getMessage(), type: 'error');
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

        return view('livewire.organization.organization-create', [
            'types' => $types,
            'legalForms' => $legalForms,
            'currencies' => $currencies,
            'timezones' => $timezones,
            'businessActivities' => $businessActivities,
        ]);
    }
}
