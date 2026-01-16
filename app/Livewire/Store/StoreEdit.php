<?php

namespace App\Livewire\Store;

use App\Services\StoreService;
use App\Dtos\Store\UpdateStoreDto;
use Livewire\Component;
use Livewire\Attributes\Validate;

class StoreEdit extends Component
{
    public $storeId;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('nullable|string|max:255')]
    public $code = '';

    #[Validate('nullable|string')]
    public $address = '';

    #[Validate('nullable|string|max:50')]
    public $city = '';

    #[Validate('nullable|string|max:20')]
    public $phone = '';

    #[Validate('nullable|email|max:255')]
    public $email = '';

    #[Validate('nullable|string')]
    public $description = '';

    #[Validate('boolean')]
    public $is_main = false;

    #[Validate('boolean')]
    public $is_active = true;

    protected $listeners = ['edit-store' => 'loadStore'];

    public $showModal = false;

    public function loadStore($storeId, StoreService $service)
    {
        try {
            $this->storeId = $storeId;
            $store = $service->getStoreById($storeId);

            $this->name = $store->name;
            $this->code = $store->code ?? '';
            $this->address = $store->address ?? '';
            $this->city = $store->city ?? '';
            $this->phone = $store->phone ?? '';
            $this->email = $store->email ?? '';
            $this->description = $store->description ?? '';
            $this->is_main = $store->is_main;
            $this->is_active = $store->is_active;

            $this->showModal = true;

        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Erreur : ' . $e->getMessage(), type: 'error');
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'storeId',
            'name',
            'code',
            'address',
            'city',
            'phone',
            'email',
            'description',
            'is_main',
            'is_active',
        ]);
        $this->resetErrorBag();
    }

    public function update(StoreService $service)
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:stores,name,' . $this->storeId,
        ]);

        try {
            $dto = new UpdateStoreDto(
                name: $this->name,
                code: $this->code ?: null,
                address: $this->address ?: null,
                city: $this->city ?: null,
                phone: $this->phone ?: null,
                email: $this->email ?: null,
                description: $this->description ?: null,
                isMain: $this->is_main,
                isActive: $this->is_active
            );

            $service->updateStore($this->storeId, $dto);

            $this->dispatch('show-toast', message: 'Magasin mis à jour avec succès !', type: 'success');
            $this->dispatch('storeUpdated');
            $this->closeModal();

        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Erreur : ' . $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.store.edit');
    }
}
