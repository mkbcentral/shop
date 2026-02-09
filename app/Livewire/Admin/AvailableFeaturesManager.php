<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\AvailableFeature;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Gestion des fonctionnalités disponibles
 *
 * Interface admin pour gérer le catalogue des fonctionnalités
 * qui peuvent être activées/désactivées par plan d'abonnement.
 */
class AvailableFeaturesManager extends Component
{
    use WithPagination;

    // Modal d'édition
    public bool $showModal = false;
    public ?int $editingId = null;

    // Formulaire
    public string $key = '';
    public string $label = '';
    public string $description = '';
    public string $category = 'modules';
    public string $icon = '';
    public bool $is_active = true;
    public int $sort_order = 0;

    // Filtres
    public string $search = '';
    public string $filterCategory = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterCategory' => ['except' => ''],
    ];

    protected function rules(): array
    {
        $uniqueRule = $this->editingId
            ? 'unique:available_features,key,' . $this->editingId
            : 'unique:available_features,key';

        return [
            'key' => ['required', 'string', 'max:100', 'regex:/^[a-z_]+$/', $uniqueRule],
            'label' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'category' => 'required|string|in:' . implode(',', array_keys(AvailableFeature::CATEGORIES)),
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ];
    }

    protected function messages(): array
    {
        return [
            'key.required' => 'La clé technique est requise.',
            'key.regex' => 'La clé doit contenir uniquement des lettres minuscules et underscores.',
            'key.unique' => 'Cette clé existe déjà.',
            'label.required' => 'Le libellé est requis.',
            'category.required' => 'La catégorie est requise.',
            'category.in' => 'Catégorie invalide.',
        ];
    }

    public function mount(): void
    {
        $this->authorize('viewAny', \App\Models\Organization::class);
    }

    /**
     * Ouvrir le modal pour créer une nouvelle fonctionnalité
     */
    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    /**
     * Ouvrir le modal pour éditer une fonctionnalité
     */
    public function openEditModal(int $id): void
    {
        $feature = AvailableFeature::find($id);

        if (!$feature) {
            $this->dispatch('show-toast', message: 'Fonctionnalité introuvable', type: 'error');
            return;
        }

        $this->editingId = $id;
        $this->key = $feature->key;
        $this->label = $feature->label;
        $this->description = $feature->description ?? '';
        $this->category = $feature->category;
        $this->icon = $feature->icon ?? '';
        $this->is_active = $feature->is_active;
        $this->sort_order = $feature->sort_order;

        $this->showModal = true;
    }

    /**
     * Fermer le modal
     */
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    /**
     * Réinitialiser le formulaire
     */
    private function resetForm(): void
    {
        $this->editingId = null;
        $this->key = '';
        $this->label = '';
        $this->description = '';
        $this->category = 'modules';
        $this->icon = '';
        $this->is_active = true;
        $this->sort_order = 0;
        $this->resetValidation();
    }

    /**
     * Sauvegarder (créer ou mettre à jour)
     */
    public function save(): void
    {
        $this->validate();

        $data = [
            'key' => $this->key,
            'label' => $this->label,
            'description' => $this->description ?: null,
            'category' => $this->category,
            'icon' => $this->icon ?: null,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
        ];

        if ($this->editingId) {
            $feature = AvailableFeature::find($this->editingId);
            $feature->update($data);
            $message = 'Fonctionnalité mise à jour avec succès.';
        } else {
            AvailableFeature::create($data);
            $message = 'Fonctionnalité créée avec succès.';
        }

        $this->closeModal();
        $this->dispatch('show-toast', message: $message, type: 'success');
    }

    /**
     * Basculer l'état actif/inactif
     */
    public function toggleActive(int $id): void
    {
        $feature = AvailableFeature::find($id);

        if (!$feature) {
            return;
        }

        $feature->update(['is_active' => !$feature->is_active]);

        $status = $feature->is_active ? 'activée' : 'désactivée';
        $this->dispatch('show-toast', message: "Fonctionnalité {$status}.", type: 'success');
    }

    /**
     * Supprimer une fonctionnalité
     */
    public function delete(int $id): void
    {
        $feature = AvailableFeature::find($id);

        if (!$feature) {
            $this->dispatch('show-toast', message: 'Fonctionnalité introuvable', type: 'error');
            return;
        }

        $feature->delete();
        $this->dispatch('show-toast', message: 'Fonctionnalité supprimée.', type: 'success');
    }

    /**
     * Générer une clé à partir du label
     */
    public function updatedLabel(string $value): void
    {
        // Ne générer la clé que si on crée une nouvelle fonctionnalité
        if (!$this->editingId && empty($this->key)) {
            $this->key = $this->generateKey($value);
        }
    }

    /**
     * Générer une clé technique à partir d'un label
     */
    private function generateKey(string $label): string
    {
        // Convertir en minuscules, remplacer espaces par underscore, garder que lettres
        $key = strtolower($label);
        $key = preg_replace('/[^a-z0-9\s]/', '', $key);
        $key = preg_replace('/\s+/', '_', trim($key));

        return $key;
    }

    public function render()
    {
        $query = AvailableFeature::query()->ordered();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('key', 'like', "%{$this->search}%")
                    ->orWhere('label', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%");
            });
        }

        if ($this->filterCategory) {
            $query->where('category', $this->filterCategory);
        }

        return view('livewire.admin.available-features-manager', [
            'features' => $query->paginate(20),
            'categories' => AvailableFeature::CATEGORIES,
            'totalCount' => AvailableFeature::count(),
            'activeCount' => AvailableFeature::where('is_active', true)->count(),
        ]);
    }
}
