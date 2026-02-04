<?php

namespace App\Livewire\ProductAttribute;

use App\Models\ProductType;
use App\Models\ProductAttribute;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Illuminate\Support\Str;

class AttributeManager extends Component
{
    use WithPagination;

    // Filtres
    public $search = '';
    public $filterProductType = '';
    public $filterType = '';
    public $perPage = 25;

    // Formulaire
    public $showModal = false;
    public $editMode = false;
    public $attributeId = null;

    public $product_type_id = '';
    public $name = '';
    public $code = '';
    public $type = 'text';
    public $options = '';
    public $unit = '';
    public $default_value = '';
    public $is_required = false;
    public $is_variant_attribute = false;
    public $is_filterable = false;
    public $is_visible = true;
    public $display_order = 0;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterProductType' => ['except' => ''],
        'filterType' => ['except' => ''],
    ];

    public function mount()
    {
        $this->resetPage();
    }

    #[Computed]
    public function productTypes()
    {
        return ProductType::orderBy('name')->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterProductType()
    {
        $this->resetPage();
    }

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    #[Computed]
    public function attributes()
    {
        $query = ProductAttribute::query()
            ->with('productType');

        // Recherche par nom ou code
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('code', 'like', '%' . $this->search . '%');
            });
        }

        // Filtre par type de produit
        if ($this->filterProductType) {
            $query->where('product_type_id', $this->filterProductType);
        }

        // Filtre par type d'attribut
        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        return $query->orderBy('product_type_id')
            ->orderBy('display_order')
            ->orderBy('name')
            ->paginate($this->perPage);
    }

    public function openModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
        $this->dispatch('open-attribute-modal');
    }

    public function openEditModal($id)
    {
        $attribute = ProductAttribute::findOrFail($id);

        $this->attributeId = $attribute->id;
        $this->product_type_id = $attribute->product_type_id;
        $this->name = $attribute->name;
        $this->code = $attribute->code;
        $this->type = $attribute->type;

        // Gérer les options (peut être un array ou une string JSON)
        if ($attribute->options) {
            $opts = is_array($attribute->options) ? $attribute->options : json_decode($attribute->options, true);
            $this->options = implode(', ', $opts ?? []);
        } else {
            $this->options = '';
        }

        $this->unit = $attribute->unit ?? '';
        $this->default_value = $attribute->default_value ?? '';
        $this->is_required = (bool) $attribute->is_required;
        $this->is_variant_attribute = (bool) $attribute->is_variant_attribute;
        $this->is_filterable = (bool) $attribute->is_filterable;
        $this->is_visible = (bool) $attribute->is_visible;
        $this->display_order = $attribute->display_order;

        $this->editMode = true;
        $this->showModal = true;
        $this->dispatch('open-attribute-modal');
    }

    public function save()
    {
        $this->validate([
            'product_type_id' => 'required|exists:product_types,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:text,number,select,boolean,color,date,textarea',
            'display_order' => 'nullable|integer|min:0',
        ]);

        // Générer le code automatiquement si vide
        if (empty($this->code)) {
            $this->code = Str::slug($this->name, '_');
        }

        // Préparer les options si type select
        $optionsArray = null;
        if ($this->type === 'select' && !empty($this->options)) {
            $optionsArray = json_encode(
                array_map('trim', explode(',', $this->options))
            );
        }

        $data = [
            'product_type_id' => $this->product_type_id,
            'name' => $this->name,
            'code' => $this->code,
            'type' => $this->type,
            'options' => $optionsArray,
            'unit' => $this->unit ?: null,
            'default_value' => $this->default_value ?: null,
            'is_required' => $this->is_required,
            'is_variant_attribute' => $this->is_variant_attribute,
            'is_filterable' => $this->is_filterable,
            'is_visible' => $this->is_visible,
            'display_order' => $this->display_order ?: 0,
        ];

        try {
            if ($this->editMode) {
                $attribute = ProductAttribute::findOrFail($this->attributeId);
                $attribute->update($data);
                $message = 'Attribut mis à jour avec succès.';
            } else {
                ProductAttribute::create($data);
                $message = 'Attribut créé avec succès.';
            }

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => $message
            ]);

            $this->closeModal();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Erreur: ' . $e->getMessage()
            ]);
        }
    }

    public function delete($id)
    {
        try {
            $attribute = ProductAttribute::findOrFail($id);

            // Vérifier s'il y a des valeurs associées
            if ($attribute->values()->exists()) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'Impossible de supprimer cet attribut car il est utilisé par des produits.'
                ]);
                $this->dispatch('close-delete-modal');
                return;
            }

            $attribute->delete();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Attribut supprimé avec succès.'
            ]);

            $this->dispatch('close-delete-modal');
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ]);
            $this->dispatch('close-delete-modal');
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('close-attribute-modal');
    }

    private function resetForm()
    {
        $this->attributeId = null;
        $this->product_type_id = '';
        $this->name = '';
        $this->code = '';
        $this->type = 'text';
        $this->options = '';
        $this->unit = '';
        $this->default_value = '';
        $this->is_required = false;
        $this->is_variant_attribute = false;
        $this->is_filterable = false;
        $this->is_visible = true;
        $this->display_order = 0;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.product-attribute.attribute-manager', [
            'productTypes' => $this->productTypes(),
            'attributes' => $this->attributes(),
        ]);
    }
}
