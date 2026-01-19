<?php

namespace App\Livewire\Category;

use App\Actions\Category\CreateCategoryAction;
use App\Actions\Category\UpdateCategoryAction;
use App\Actions\Category\DeleteCategoryAction;
use App\Exceptions\Category\CategoryHasProductsException;
use App\Exceptions\Category\CategoryNotFoundException;
use App\Livewire\Forms\CategoryForm;
use App\Repositories\CategoryRepository;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class CategoryIndex extends Component
{
    use WithPagination;

    public CategoryForm $form;

    public $search = '';
    public $perPage = 10;
    public $selectedCategoryId = null;
    public $isEditMode = false;

    public function mount() {}

    public function render(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->paginate($this->search, $this->perPage);

        return view('livewire.category.index', [
            'categories' => $categories,
        ]);
    }

    public function openCreateModal()
    {
        $this->form->reset();
        $this->selectedCategoryId = null;
        $this->isEditMode = false;
    }

    public function openEditModal(CategoryRepository $categoryRepository, $id)
    {
        try {
            $category = $categoryRepository->findOrFail($id);
            
            // Vérifier si l'utilisateur peut modifier cette catégorie
            if (!$category->canBeModifiedBy(auth()->user())) {
                $this->dispatch('show-toast', 
                    message: 'Vous ne pouvez pas modifier cette catégorie car elle appartient à une autre organisation.', 
                    type: 'warning'
                );
                return;
            }
            
            $this->form->reset();
            $this->selectedCategoryId = $category->id;
            $this->isEditMode = true;
            $this->form->setCategory($category);
            $this->dispatch('open-edit-modal');

        } catch (CategoryNotFoundException $e) {
            $this->dispatch('show-toast', message: 'Catégorie introuvable.', type: 'error');
            Log::warning('Category not found for edit', ['category_id' => $id]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Une erreur est survenue.', type: 'error');
            Log::error('Error opening edit modal', [
                'category_id' => $id,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function save(CreateCategoryAction $createAction, UpdateCategoryAction $updateAction)
    {
        $this->form->validate();

        try {
            if ($this->isEditMode) {
                // Mise à jour
                $category = $updateAction->execute($this->selectedCategoryId, $this->form->toArray());

                $this->dispatch('show-toast',
                    message: "Catégorie \"{$category->name}\" mise à jour avec succès.",
                    type: 'success'
                );
            } else {
                // Création
                $category = $createAction->execute($this->form->toArray());

                $this->dispatch('show-toast',
                    message: "Catégorie \"{$category->name}\" créée avec succès.",
                    type: 'success'
                );
            }

            // Fermer le modal via Alpine.js
            $this->dispatch('close-category-modal');
            $this->form->reset();

        } catch (CategoryNotFoundException $e) {
            $this->dispatch('show-toast', message: 'Catégorie introuvable.', type: 'error');
            Log::warning('Category not found during save', [
                'category_id' => $this->form->categoryId
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Re-throw validation exceptions so Livewire can handle them
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error saving category', [
                'category_id' => $this->form->categoryId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->dispatch('show-toast',
                message: 'Une erreur est survenue lors de la sauvegarde: ' . $e->getMessage(),
                type: 'error'
            );
        }
    }

    public function delete(CategoryRepository $categoryRepository, DeleteCategoryAction $deleteAction, $id)
    {
        try {
            $category = $categoryRepository->findOrFail($id);
            $categoryName = $category->name;

            // Vérifier si l'utilisateur peut supprimer cette catégorie
            if (!$category->canBeModifiedBy(auth()->user())) {
                $this->dispatch('show-toast', 
                    message: "Vous ne pouvez pas supprimer la catégorie \"{$categoryName}\" car elle appartient à une autre organisation.", 
                    type: 'warning'
                );
                return;
            }

            // Use the model method for checking
            if (!$category->canBeDeleted()) {
                $productsCount = $category->getProductsCount();
                $this->dispatch('show-toast',
                    message: "Impossible de supprimer la catégorie \"{$categoryName}\". Elle contient {$productsCount} produit(s).",
                    type: 'warning'
                );
                return;
            }

            $deleteAction->execute($id);

            $this->dispatch('show-toast',
                message: "Catégorie \"{$categoryName}\" supprimée avec succès.",
                type: 'success'
            );

        } catch (CategoryNotFoundException $e) {
            $this->dispatch('show-toast', message: 'Catégorie introuvable.', type: 'error');
            Log::warning('Category not found for deletion', ['category_id' => $id]);
        } catch (CategoryHasProductsException $e) {
            $this->dispatch('show-toast', message: $e->getMessage(), type: 'warning');
            Log::info('Cannot delete category with products', [
                'category_id' => $id,
                'message' => $e->getMessage()
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting category', [
                'category_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->dispatch('show-toast',
                message: 'Une erreur est survenue lors de la suppression.',
                type: 'error'
            );
        }
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
