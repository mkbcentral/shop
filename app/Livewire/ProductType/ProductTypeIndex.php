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
    public ?int $editingProductTypeId = null;

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
        $this->editingProductTypeId = null;
    }

    public function openEditModal(ProductTypeService $service, int $id)
    {
        try {
            $productType = $service->getProductTypeById($id);
            if (!$productType) {
                $this->dispatch('show-toast', message: 'Type de produit introuvable.', type: 'error');
                return;
            }
            $this->form->reset();
            $this->form->setProductType($productType);
            $this->editingProductTypeId = $id;
            $this->dispatch('open-edit-modal');
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
            $data = $this->form->toArray();

            Log::info('Saving product type', [
                'editingProductTypeId' => $this->editingProductTypeId,
                'formProductTypeId' => $this->form->productTypeId,
                'data' => $data
            ]);

            if ($this->editingProductTypeId) {
                $productType = $service->updateProductType($this->editingProductTypeId, $data);
                $this->dispatch('show-toast',
                    message: "Type \"{$productType->name}\" mis à jour avec succès.",
                    type: 'success'
                );
            } else {
                $productType = $service->createProductType($data);
                $this->dispatch('show-toast',
                    message: "Type \"{$productType->name}\" créé avec succès.",
                    type: 'success'
                );
            }

            $this->dispatch('close-producttype-modal');
            $this->editingProductTypeId = null;
            $this->form->reset();

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error saving product type', [
                'product_type_id' => $this->form->productTypeId,
                'error' => $e->getMessage(),
            ]);
            $this->dispatch('show-toast',
                message: 'Erreur : ' . $e->getMessage(),
                type: 'error'
            );
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
