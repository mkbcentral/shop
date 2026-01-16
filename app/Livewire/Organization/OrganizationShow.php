<?php

namespace App\Livewire\Organization;

use App\Models\Organization;
use App\Services\OrganizationService;
use App\Services\StoreService;
use App\Dtos\Store\CreateStoreDto;
use App\Repositories\StoreRepository;
use App\Livewire\Forms\StoreForm;
use Livewire\Component;

class OrganizationShow extends Component
{
    public Organization $organization;
    public array $statistics = [];
    public bool $showStoreModal = false;

    public StoreForm $storeForm;

    protected $listeners = ['organization-updated' => 'refreshData'];

    public function mount(Organization $organization, OrganizationService $service): void
    {
        $this->authorize('view', $organization);

        // Ne pas eager-load les relations pour éviter les problèmes de sérialisation Livewire
        $this->organization = $organization;
        $this->statistics = $this->normalizeArray($service->getStatistics($organization));
    }

    /**
     * Convertit récursivement les objets en arrays pour la sérialisation Livewire
     */
    protected function normalizeArray(array $data): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            if (is_object($value)) {
                $value = (array) $value;
            }
            if (is_array($value)) {
                $result[$key] = $this->normalizeArray($value);
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    public function refreshData(OrganizationService $service): void
    {
        $this->organization = $this->organization->fresh();
        $this->statistics = $this->normalizeArray($service->getStatistics($this->organization));
    }

    public function openStoreModal(StoreRepository $repository): void
    {
        $this->authorize('createStore', $this->organization);

        $this->storeForm->reset();
        $this->storeForm->code = $repository->generateNextCode($this->organization->id);
        $this->storeForm->organization_id = $this->organization->id;
        $this->showStoreModal = true;
    }

    public function closeStoreModal(): void
    {
        $this->showStoreModal = false;
        $this->storeForm->reset();
    }

    public function saveStore(StoreService $service, OrganizationService $orgService): void
    {
        $this->authorize('createStore', $this->organization);

        $this->storeForm->validate();

        try {
            $data = $this->storeForm->toArray();

            $dto = new CreateStoreDto(
                name: $data['name'],
                code: $data['code'],
                address: $data['address'],
                phone: $data['phone'],
                email: $data['email'],
                managerId: null,
                organizationId: $this->organization->id,
                isActive: $data['is_active'],
                isMain: $data['is_main'],
                settings: null
            );

            $service->createStore($dto->toArray());

            // Refresh sans eager-load pour éviter les problèmes de sérialisation
            $this->organization = $this->organization->fresh();
            $this->statistics = $this->normalizeArray($orgService->getStatistics($this->organization));

            $this->closeStoreModal();

            $this->dispatch('show-toast', message: 'Magasin créé avec succès !', type: 'success');

        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Erreur : ' . $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.organization.organization-show');
    }
}
