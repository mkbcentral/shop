<?php

namespace App\Livewire\Supplier;

use App\Actions\Supplier\CreateSupplierAction;
use App\Actions\Supplier\UpdateSupplierAction;
use App\Actions\Supplier\DeleteSupplierAction;
use App\Livewire\Forms\SupplierForm;
use App\Repositories\SupplierRepository;
use Livewire\Component;
use Livewire\WithPagination;

class SupplierIndex extends Component
{
    use WithPagination;

    public SupplierForm $form;

    public $search = '';
    public $perPage = 15;
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $selectedSupplierId = null;
    public $isEditMode = false;

    // Delete confirmation
    public $supplierToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
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
        $this->form->reset();
        $this->selectedSupplierId = null;
        $this->isEditMode = false;
    }

    public function openEditModal($id, SupplierRepository $repository)
    {
        $supplier = $repository->find($id);
        if (!$supplier) {
            $this->dispatch('show-toast', message: 'Fournisseur introuvable.', type: 'error');
            return;
        }
        $this->selectedSupplierId = $supplier->id;
        $this->isEditMode = true;
        $this->form->setSupplier($supplier);

        $this->dispatch('open-edit-modal');
    }

    public function save(CreateSupplierAction $createAction, UpdateSupplierAction $updateAction)
    {

        try {

            if ($this->isEditMode) {
                $updateAction->execute($this->selectedSupplierId, $this->form->toArray());
                $this->dispatch('show-toast', message: 'Fournisseur modifié avec succès.', type: 'success');
            } else {
                $createAction->execute($this->form->toArray());
                $this->dispatch('show-toast', message: 'Fournisseur créé avec succès.', type: 'success');
            }

            $this->dispatch('close-supplier-modal');
            $this->form->reset();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Erreur : ' . $e->getMessage(), type: 'error');
        }
    }

    public function delete($supplierId, DeleteSupplierAction $action, SupplierRepository $repository)
    {
        if (!$supplierId) {
            return;
        }

        try {
            $supplier = $repository->find($supplierId);

            if ($supplier) {
                $action->execute($supplier->id);
                $this->dispatch('show-toast', message: 'Fournisseur supprimé avec succès.', type: 'success');
            } else {
                $this->dispatch('show-toast', message: 'Fournisseur introuvable.', type: 'error');
            }
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Erreur : ' . $e->getMessage(), type: 'error');
        }

        $this->supplierToDelete = null;
    }

    public function render(SupplierRepository $repository)
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

        $suppliers = $query->paginate($this->perPage);

        return view('livewire.supplier.supplier-index', [
            'suppliers' => $suppliers,
        ]);
    }
}
