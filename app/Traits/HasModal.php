<?php

namespace App\Traits;

/**
 * Trait for Livewire components that use modals.
 *
 * Usage in Livewire component:
 *
 * use App\Traits\HasModal;
 *
 * class MyComponent extends Component
 * {
 *     use HasModal;
 *
 *     // For a single modal:
 *     public bool $showModal = false;
 *
 *     // Or for multiple modals:
 *     public bool $showCreateModal = false;
 *     public bool $showEditModal = false;
 *     public bool $showDeleteModal = false;
 * }
 *
 * In Blade:
 * <x-ui.form-modal show="showModal" :on-close="'closeModal'" :on-submit="'save'">
 *     <!-- Form content -->
 * </x-ui.form-modal>
 */
trait HasModal
{
    /**
     * Open a modal by name
     */
    public function openModal(string $modalName = 'showModal'): void
    {
        if (property_exists($this, $modalName)) {
            $this->{$modalName} = true;
        }
    }

    /**
     * Close a modal by name
     */
    public function closeModal(string $modalName = 'showModal'): void
    {
        if (property_exists($this, $modalName)) {
            $this->{$modalName} = false;
        }
    }

    /**
     * Toggle a modal by name
     */
    public function toggleModal(string $modalName = 'showModal'): void
    {
        if (property_exists($this, $modalName)) {
            $this->{$modalName} = !$this->{$modalName};
        }
    }

    /**
     * Open the create modal
     */
    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->openModal('showCreateModal');
    }

    /**
     * Close the create modal
     */
    public function closeCreateModal(): void
    {
        $this->closeModal('showCreateModal');
    }

    /**
     * Open the edit modal
     */
    public function openEditModal($id = null): void
    {
        if ($id) {
            $this->loadItem($id);
        }
        $this->openModal('showEditModal');
    }

    /**
     * Close the edit modal
     */
    public function closeEditModal(): void
    {
        $this->closeModal('showEditModal');
    }

    /**
     * Open the delete confirmation modal
     */
    public function openDeleteModal($id = null): void
    {
        if ($id && property_exists($this, 'deleteId')) {
            $this->deleteId = $id;
        }
        $this->openModal('showDeleteModal');
    }

    /**
     * Close the delete confirmation modal
     */
    public function closeDeleteModal(): void
    {
        $this->closeModal('showDeleteModal');
        if (property_exists($this, 'deleteId')) {
            $this->deleteId = null;
        }
    }

    /**
     * Reset form data - override in your component
     */
    protected function resetForm(): void
    {
        // Override this in your component to reset form fields
    }

    /**
     * Load item for editing - override in your component
     */
    protected function loadItem($id): void
    {
        // Override this in your component to load item data
    }
}
