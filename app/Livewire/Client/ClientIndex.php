<?php

namespace App\Livewire\Client;

use App\Actions\Client\CreateClientAction;
use App\Actions\Client\UpdateClientAction;
use App\Actions\Client\DeleteClientAction;
use App\Repositories\ClientRepository;
use Livewire\Component;
use Livewire\WithPagination;

class ClientIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 15;
    public $sortField = 'name';
    public $sortDirection = 'asc';

    // Modal properties
    public $editMode = false;
    public $clientId = null;

    // Form properties
    public $name = '';
    public $phone = '';
    public $email = '';
    public $address = '';

    // Delete confirmation
    public $clientToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'phone' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
        'address' => 'nullable|string|max:500',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->editMode = false;
    }

    public function openEditModal($id, ClientRepository $repository)
    {
        $client = $repository->find($id);

        if (!$client) {
            $this->dispatch('show-toast', message: 'Client introuvable.', type: 'error');
            return;
        }

        $this->resetForm();
        $this->editMode = true;
        $this->clientId = $client->id;
        $this->name = $client->name;
        $this->phone = $client->phone ?? '';
        $this->email = $client->email ?? '';
        $this->address = $client->address ?? '';
        $this->dispatch('open-edit-modal');
    }

    public function save(CreateClientAction $createAction, UpdateClientAction $updateAction)
    {
        $this->validate();

        try {
            $data = [
                'name' => $this->name,
                'phone' => $this->phone ?: null,
                'email' => $this->email ?: null,
                'address' => $this->address ?: null,
            ];

            if ($this->editMode) {
                $updateAction->execute($this->clientId, $data);
                $this->dispatch('show-toast', message: 'Client modifié avec succès.', type: 'success');
            } else {
                $createAction->execute($data);
                $this->dispatch('show-toast', message: 'Client créé avec succès.', type: 'success');
            }

            $this->dispatch('close-client-modal');
            $this->resetForm();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Erreur : ' . $e->getMessage(), type: 'error');
        }
    }

    public function delete($clientId, DeleteClientAction $action, ClientRepository $repository)
    {
        if (!$clientId) {
            return;
        }

        try {
            $client = $repository->find($clientId);

            if ($client) {
                $action->execute($client->id);
                $this->dispatch('show-toast', message: 'Client supprimé avec succès.', type: 'success');
            } else {
                $this->dispatch('show-toast', message: 'Client introuvable.', type: 'error');
            }
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Erreur : ' . $e->getMessage(), type: 'error');
        }

        $this->clientToDelete = null;
    }

    private function resetForm()
    {
        $this->clientId = null;
        $this->editMode = false;
        $this->name = '';
        $this->phone = '';
        $this->email = '';
        $this->address = '';
        $this->resetValidation();
    }

    public function render(ClientRepository $repository)
    {
        $query = $repository->query();

        // Apply search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $clients = $query->paginate($this->perPage);

        return view('livewire.client.client-index', [
            'clients' => $clients,
        ]);
    }
}
