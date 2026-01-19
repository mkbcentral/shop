<?php

namespace App\Livewire\ProductType;

use App\Livewire\Forms\ProductTypeForm;
use App\Services\ProductTypeService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class ProductTypeIndex extends Component
{
    use WithPagination;

    public ProductTypeForm $form;

    public string $search = '';
    public int $perPage = 10;
    public $selectedProductTypeId = null;
    public $isEditMode = false;

    public function render(ProductTypeService $service)
    {
        $query = $service->getAllProductTypes();

        // Filter by search
        if ($this->search) {
            $query = $query->filter(function ($item) {
                return str_contains(strtolower($item->name), strtolower($this->search)) ||
                       str_contains(strtolower($item->description ?? ''), strtolower($this->search));
            });
        }

        return view('livewire.product-type.index', [
            'productTypes' => $query,
        ]);
    }

    public function openCreateModal()
    {
        $this->form->reset();
        $this->selectedProductTypeId = null;
        $this->isEditMode = false;
        $this->dispatch('open-producttype-modal');
    }

    public function openEditModal(int $id, ProductTypeService $service)
    {
        try {
            $productType = $service->getProductTypeById($id);
            if (!$productType) {
                $this->dispatch('show-toast', message: 'Type de produit introuvable.', type: 'error');
                return;
            }
            
            // Vérifier si l'utilisateur peut modifier ce type
            if (!$productType->canBeModifiedBy(auth()->user())) {
                $this->dispatch('show-toast', 
                    message: 'Vous ne pouvez pas modifier ce type car il appartient à une autre organisation.', 
                    type: 'warning'
                );
                return;
            }
            
            $this->form->reset();
            $this->selectedProductTypeId = $productType->id;
            $this->isEditMode = true;
            $this->form->setProductType($productType);
            
            // Le modal est ouvert via Alpine.js après le retour de cette méthode
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Une erreur est survenue.', type: 'error');
            Log::error('Error opening edit modal', [
                'product_type_id' => $id,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function save(ProductTypeService $service)
    {
        $this->form->validate();

        try {
            if ($this->isEditMode) {
                Log::info('Updating product type', ['id' => $this->selectedProductTypeId]);
                $productType = $service->updateProductType($this->selectedProductTypeId, $this->form->toArray());
                $this->dispatch(
                    'show-toast',
                    message: "Type \"{$productType->name}\" mis à jour avec succès.",
                    type: 'success'
                );
            } else {
                Log::info('Creating product type');
                $productType = $service->createProductType($this->form->toArray());
                $this->dispatch(
                    'show-toast',
                    message: "Type \"{$productType->name}\" créé avec succès.",
                    type: 'success'
                );
            }

            $this->dispatch('close-producttype-modal');
            $this->form->reset();
        } catch (\Exception $e) {
            Log::error('Error in save', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            $this->dispatch('show-toast', message: 'Erreur : ' . $e->getMessage(), type: 'error');
        }
    }

    public function delete(ProductTypeService $service, int $id)
    {
        try {
            $productType = $service->getProductTypeById($id);

            if (!$productType) {
                $this->dispatch('show-toast', message: 'Type de produit introuvable.', type: 'error');
                return;
            }

            // Vérifier si l'utilisateur peut supprimer ce type
            if (!$productType->canBeModifiedBy(auth()->user())) {
                $this->dispatch('show-toast', 
                    message: "Vous ne pouvez pas supprimer \"{$productType->name}\" car il appartient à une autre organisation.", 
                    type: 'warning'
                );
                return;
            }

            $productName = $productType->name;
            $productsCount = $productType->products_count ?? $productType->products()->count();

            if ($productsCount > 0) {
                $this->dispatch('show-toast',
                    message: "Impossible de supprimer \"{$productName}\". Ce type contient {$productsCount} produit(s).",
                    type: 'warning'
                );
                return;
            }

            $service->deleteProductType($id);

            $this->dispatch('show-toast',
                message: "Type \"{$productName}\" supprimé avec succès.",
                type: 'success'
            );

        } catch (\Exception $e) {
            Log::error('Error deleting product type', [
                'product_type_id' => $id,
                'error' => $e->getMessage(),
            ]);
            $this->dispatch('show-toast',
                message: 'Erreur lors de la suppression.',
                type: 'error'
            );
        }
    }

    public function toggleActive(ProductTypeService $service, int $id)
    {
        try {
            $service->toggleActive($id);
            $this->dispatch('show-toast', message: 'Statut modifié avec succès.', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Erreur : ' . $e->getMessage(), type: 'error');
        }
    }

    public function setIcon(string $icon)
    {
        $this->form->icon = $icon;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }
}
