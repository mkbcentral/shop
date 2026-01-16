<?php

namespace App\Livewire\Store;

use App\Services\StoreService;
use App\Dtos\Store\CreateStoreDto;
use App\Repositories\StoreRepository;
use Livewire\Component;
use Livewire\Attributes\Validate;

class StoreCreate extends Component
{
    #[Validate('required|string|max:255|unique:stores,name')]
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

    public function mount(StoreRepository $repository)
    {
        // Generate automatic code based on organization
        $this->code = $repository->generateNextCode(auth()->user()->organization_id);
    }

    public function save(StoreService $service)
    {
        $validated = $this->validate();

        try {
            $dto = new CreateStoreDto(
                name: $validated['name'],
                code: $validated['code'] ?? null,
                address: $validated['address'] ?? null,
                phone: $validated['phone'] ?? null,
                email: $validated['email'] ?? null,
                managerId: null,
                organizationId: auth()->user()->organization_id,
                isActive: $validated['is_active'],
                isMain: $validated['is_main'],
                settings: null
            );

            $store = $service->createStore($dto->toArray());

            session()->flash('success', 'Magasin créé avec succès !');

            return $this->redirect(route('stores.index'), navigate: true);

        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.store.create');
    }
}
