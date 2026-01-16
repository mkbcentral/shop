# Système de Modals Réutilisables

## Vue d'ensemble

Le système de modals fournit des composants Blade réutilisables pour créer des fenêtres modales cohérentes dans toute l'application.

## ✅ Fichiers Migrés

Les fichiers suivants ont été migrés vers le nouveau système de modals :

| Fichier | Status |
|---------|--------|
| `livewire/category/index.blade.php` | ✅ Migré vers `x-ui.modal` |
| `livewire/supplier/supplier-index.blade.php` | ✅ Migré vers `x-ui.modal` |
| `livewire/client/client-index.blade.php` | ✅ Migré vers `x-ui.modal` |
| `livewire/product-type/index.blade.php` | ✅ Migré vers `x-ui.modal` |
| `components/delete-confirmation-modal.blade.php` | ✅ Wrapper vers `x-ui.confirm-modal` |
| `livewire/user/index.blade.php` | ✅ Utilise `x-modal` existant |
| `livewire/store/index.blade.php` | ✅ Utilise `x-modal` existant |

## Composants disponibles

### 1. Modal de base (`x-ui.modal`)

Le composant de base pour créer des modals personnalisées.

```blade
<x-ui.modal 
    name="myModal"
    show="showModal"           {{-- Variable Livewire pour contrôler l'affichage --}}
    max-width="2xl"            {{-- sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl, full --}}
    :closeable="true"          {{-- Affiche le bouton fermer --}}
    :close-on-click-outside="true"
    :close-on-escape="true"
    :persistent="false"        {{-- Si true, la modal ne peut pas être fermée --}}
>
    <x-ui.modal-header 
        title="Titre" 
        subtitle="Sous-titre optionnel"
        icon-bg="from-indigo-500 to-purple-600"
    >
        <x-slot:icon>
            <svg class="w-6 h-6 text-white">...</svg>
        </x-slot:icon>
    </x-ui.modal-header>
    
    <x-ui.modal-body>
        <!-- Contenu -->
    </x-ui.modal-body>
    
    <x-ui.modal-footer align="right"> {{-- left, center, right, between --}}
        <button>Annuler</button>
        <button>Confirmer</button>
    </x-ui.modal-footer>
</x-ui.modal>
```

### 2. Modal de formulaire (`x-ui.form-modal`)

Pour les formulaires avec validation et soumission.

```blade
<x-ui.form-modal
    show="showCreateModal"
    title="Créer un produit"
    subtitle="Remplissez les informations du produit"
    max-width="3xl"
    submit-text="Enregistrer"
    cancel-text="Annuler"
    :on-submit="'save'"        {{-- Méthode Livewire à appeler --}}
    :on-close="'closeModal'"
    :loading="true"            {{-- Affiche spinner lors de la soumission --}}
>
    <div class="space-y-4">
        <x-form.form-group label="Nom" for="name" required>
            <x-form.input wire:model="form.name" />
            <x-form.input-error for="form.name" />
        </x-form.form-group>
        
        <!-- Autres champs... -->
    </div>
    
    {{-- Optionnel: contenu à gauche du footer --}}
    <x-slot:footerLeft>
        <button type="button" wire:click="reset">Réinitialiser</button>
    </x-slot:footerLeft>
</x-ui.form-modal>
```

### 3. Modal de confirmation (`x-ui.confirm-modal`)

Pour les confirmations de suppression ou actions importantes.

```blade
<x-ui.confirm-modal
    show="showDeleteModal"
    type="danger"              {{-- danger, warning, info, success --}}
    title="Supprimer le produit"
    message="Êtes-vous sûr de vouloir supprimer ce produit ?"
    details="Produit XYZ"      {{-- Texte mis en évidence --}}
    confirm-text="Supprimer"
    cancel-text="Annuler"
    :on-confirm="'confirmDelete'"
    :loading="true"
/>
```

### 4. Modal d'alerte (`x-ui.alert-modal`)

Pour afficher des messages de succès, erreur, ou information.

```blade
<x-ui.alert-modal
    show="showSuccessModal"
    type="success"             {{-- success, error, warning, info --}}
    title="Succès !"
    message="Le produit a été créé avec succès."
    button-text="Fermer"
    :auto-close="true"         {{-- Ferme automatiquement --}}
    :auto-close-delay="3000"   {{-- Délai en ms --}}
/>
```

### 5. Panneau latéral (`x-ui.slide-over`)

Pour les panneaux qui glissent depuis le côté.

```blade
<x-ui.slide-over
    show="showDetailsPanel"
    title="Détails du produit"
    subtitle="Informations complètes"
    position="right"           {{-- left, right --}}
    width="lg"                 {{-- sm, md, lg, xl, 2xl --}}
>
    <!-- Contenu -->
    
    <x-slot:footer>
        <button>Fermer</button>
    </x-slot:footer>
</x-ui.slide-over>
```

## Utilisation avec Livewire

### Trait HasModal

Utilisez le trait `HasModal` pour simplifier la gestion des modals :

```php
<?php

namespace App\Livewire;

use App\Traits\HasModal;
use Livewire\Component;

class ProductManager extends Component
{
    use HasModal;
    
    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public bool $showDeleteModal = false;
    
    public ?int $deleteId = null;
    public array $form = [];
    
    protected function resetForm(): void
    {
        $this->form = [
            'name' => '',
            'price' => 0,
        ];
    }
    
    protected function loadItem($id): void
    {
        $product = Product::find($id);
        $this->form = $product->toArray();
    }
    
    public function save()
    {
        // Validation et sauvegarde...
        $this->closeCreateModal();
    }
    
    public function confirmDelete()
    {
        Product::find($this->deleteId)->delete();
        $this->closeDeleteModal();
    }
}
```

### Dans la vue Blade :

```blade
<div>
    <!-- Bouton pour ouvrir -->
    <button wire:click="openCreateModal">Nouveau produit</button>
    
    <!-- Modal de création -->
    <x-ui.form-modal
        show="showCreateModal"
        title="Nouveau produit"
        :on-submit="'save'"
        :on-close="'closeCreateModal'"
    >
        <!-- Formulaire -->
    </x-ui.form-modal>
    
    <!-- Modal de suppression -->
    <x-ui.confirm-modal
        show="showDeleteModal"
        type="danger"
        title="Supprimer"
        message="Cette action est irréversible."
        :on-confirm="'confirmDelete'"
        :on-cancel="'closeDeleteModal'"
    />
</div>
```

## Utilisation avec Alpine.js uniquement

```blade
<div x-data="{ showModal: false }">
    <button @click="showModal = true">Ouvrir</button>
    
    <x-ui.modal 
        name="example"
        :show="false"
        x-model="showModal"
    >
        <x-ui.modal-header title="Exemple" />
        <x-ui.modal-body>
            Contenu...
        </x-ui.modal-body>
        <x-ui.modal-footer>
            <button @click="showModal = false">Fermer</button>
        </x-ui.modal-footer>
    </x-ui.modal>
</div>
```

## Personnalisation

### Tailles disponibles

| Valeur | Classe CSS |
|--------|------------|
| `sm` | `max-w-sm` (24rem) |
| `md` | `max-w-md` (28rem) |
| `lg` | `max-w-lg` (32rem) |
| `xl` | `max-w-xl` (36rem) |
| `2xl` | `max-w-2xl` (42rem) |
| `3xl` | `max-w-3xl` (48rem) |
| `4xl` | `max-w-4xl` (56rem) |
| `5xl` | `max-w-5xl` (64rem) |
| `6xl` | `max-w-6xl` (72rem) |
| `7xl` | `max-w-7xl` (80rem) |
| `full` | `max-w-full` |

### Couleurs d'icône (icon-bg)

- `from-indigo-500 to-purple-600` - Défaut
- `from-green-500 to-emerald-600` - Succès
- `from-red-500 to-rose-600` - Danger
- `from-yellow-500 to-orange-600` - Warning
- `from-blue-500 to-cyan-600` - Info

## Bonnes pratiques

1. **Utilisez les composants spécialisés** quand possible (`form-modal`, `confirm-modal`, `alert-modal`)

2. **Préférez le trait HasModal** pour une gestion cohérente

3. **Fermez les modals après les actions** pour une bonne UX

4. **Utilisez loading** pour montrer le traitement

5. **Validez côté serveur** avant de fermer les modals de formulaire

## Migration depuis l'ancien système

Pour migrer vos modals existantes :

1. Remplacez les modals manuelles par les composants `x-ui.*`
2. Utilisez le trait `HasModal` dans vos composants Livewire
3. Adaptez les noms des propriétés (`showModal`, `showCreateModal`, etc.)
