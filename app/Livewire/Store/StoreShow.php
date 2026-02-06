<?php

namespace App\Livewire\Store;

use App\Services\StoreService;
use App\Services\StoreTransferService;
use App\Livewire\Forms\StoreForm;
use App\Repositories\StoreRepository;
use Livewire\Component;
use Livewire\WithPagination;

class StoreShow extends Component
{
    use WithPagination;

    public $storeId;
    public $activeTab = 'overview'; // overview, stock, transfers, users, sales, purchases

    public $perPage = 10;

    // Store form
    public StoreForm $form;
    public $showEditModal = false;

    // Assign user modal
    public $showAssignModal = false;
    public $selectedUserId = null;
    public $selectedRole = 'staff';
    public $isDefaultStore = false;

    protected $queryString = [
        'activeTab' => ['except' => 'overview'],
    ];

    protected $listeners = [
        'edit-store' => 'openEditModal',
    ];

    public function mount($storeId)
    {
        $this->storeId = $storeId;
    }

    public function openEditModal()
    {
        try {
            $repository = app(StoreRepository::class);
            $store = $repository->find($this->storeId);
            if (!$store) {
                $this->dispatch('show-toast', message: 'Magasin introuvable.', type: 'error');
                return;
            }
            $this->form->setStore($store);
            $this->showEditModal = true;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Une erreur est survenue : ' . $e->getMessage(), type: 'error');
        }
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->form->reset();
    }

    public function saveStore(StoreService $service)
    {
        $this->form->validate();

        try {
            $data = $this->form->toArray();
            // Use $this->storeId from the component, not from form
            $service->updateStore($this->storeId, $data);
            $this->dispatch('show-toast', message: 'Magasin mis à jour avec succès !', type: 'success');
            $this->closeEditModal();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Erreur : ' . $e->getMessage(), type: 'error');
        }
    }

    public function changeTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function openAssignModal()
    {
        $this->showAssignModal = true;
        $this->resetAssignForm();
    }

    public function closeAssignModal()
    {
        $this->showAssignModal = false;
        $this->resetAssignForm();
    }

    public function resetAssignForm()
    {
        $this->selectedUserId = null;
        $this->selectedRole = 'staff';
        $this->isDefaultStore = false;
        $this->resetErrorBag();
    }

    public function assignUser(StoreService $service)
    {
        $this->validate([
            'selectedUserId' => 'required|exists:users,id',
            'selectedRole' => 'required|in:admin,manager,cashier,staff',
        ]);

        try {
            $service->assignUserToStore(
                storeId: $this->storeId,
                userId: $this->selectedUserId,
                role: $this->selectedRole,
                isDefault: $this->isDefaultStore
            );

            $this->dispatch('show-toast', message: 'Utilisateur assigné avec succès !', type: 'success');
            $this->closeAssignModal();

        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Erreur : ' . $e->getMessage(), type: 'error');
        }
    }

    public function removeUser($userId, StoreService $service)
    {
        try {
            $service->removeUserFromStore($this->storeId, $userId);
            $this->dispatch('show-toast', message: 'Utilisateur retiré avec succès !', type: 'success');

        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Erreur : ' . $e->getMessage(), type: 'error');
        }
    }

    public function render(StoreService $service, StoreTransferService $transferService)
    {
        $store = $service->getStoreById($this->storeId);
        $statistics = $service->getStoreStatistics($this->storeId);

        $data = [
            'store' => $store,
            'statistics' => $statistics,
        ];

        // Load data based on active tab
        switch ($this->activeTab) {
            case 'stock':
                $data['stockItems'] = $service->getStoreStock($this->storeId)
                    ->paginate($this->perPage);
                break;

            case 'transfers':
                $data['outgoingTransfers'] = $transferService->getStoreOutgoingTransfers($this->storeId)
                    ->paginate($this->perPage);
                $data['incomingTransfers'] = $transferService->getStoreIncomingTransfers($this->storeId)
                    ->paginate($this->perPage);
                break;

            case 'users':
                $data['users'] = $store->users;
                $data['availableUsers'] = \App\Models\User::whereDoesntHave('stores', function ($query) {
                    $query->where('store_id', $this->storeId);
                })->get();
                break;

            case 'sales':
                $data['sales'] = $store->sales()
                    ->with('client')
                    ->latest()
                    ->paginate($this->perPage);
                break;

            case 'purchases':
                $data['purchases'] = $store->purchases()
                    ->with('supplier')
                    ->latest()
                    ->paginate($this->perPage);
                break;
        }

        return view('livewire.store.show', $data);
    }
}
