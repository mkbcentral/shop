# Composant Table Réutilisable

## Composants disponibles

Le système de table est composé de plusieurs composants modulaires :

- `<x-table.table>` - Conteneur principal de la table
- `<x-table.head>` - En-tête de la table
- `<x-table.header>` - Colonne d'en-tête individuelle
- `<x-table.body>` - Corps de la table
- `<x-table.row>` - Ligne de la table
- `<x-table.cell>` - Cellule de la table
- `<x-table.empty-state>` - État vide
- `<x-table.badge>` - Badge pour les statuts
- `<x-table.actions>` - Conteneur d'actions
- `<x-table.action-button>` - Bouton d'action

## Exemples d'utilisation

### Exemple simple

```blade
<x-table.table>
    <x-table.head>
        <tr>
            <x-table.header>Nom</x-table.header>
            <x-table.header>Email</x-table.header>
            <x-table.header align="right">Actions</x-table.header>
        </tr>
    </x-table.head>
    <x-table.body>
        @forelse($users as $user)
            <x-table.row>
                <x-table.cell>{{ $user->name }}</x-table.cell>
                <x-table.cell>{{ $user->email }}</x-table.cell>
                <x-table.cell align="right">
                    <x-table.actions>
                        <x-table.action-button href="{{ route('users.edit', $user) }}" color="indigo">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </x-table.action-button>
                        <x-table.action-button wire:click="delete({{ $user->id }})" color="red">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </x-table.action-button>
                    </x-table.actions>
                </x-table.cell>
            </x-table.row>
        @empty
            <x-table.empty-state 
                colspan="3"
                title="Aucun utilisateur"
                description="Commencez par créer un nouvel utilisateur."
            />
        @endforelse
    </x-table.body>
</x-table.table>
```

### Exemple avec badges et tri

```blade
<x-table.table>
    <x-table.head>
        <tr>
            <x-table.header sortable sort-key="name">Produit</x-table.header>
            <x-table.header>Catégorie</x-table.header>
            <x-table.header sortable sort-key="price">Prix</x-table.header>
            <x-table.header>Stock</x-table.header>
            <x-table.header>Statut</x-table.header>
            <x-table.header align="right">Actions</x-table.header>
        </tr>
    </x-table.head>
    <x-table.body>
        @foreach($products as $product)
            <x-table.row>
                <x-table.cell>
                    <div class="flex items-center">
                        @if($product->image)
                            <img src="{{ $product->image }}" class="w-10 h-10 rounded-lg object-cover mr-3">
                        @endif
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                            <div class="text-xs text-gray-500">{{ $product->reference }}</div>
                        </div>
                    </div>
                </x-table.cell>
                <x-table.cell>
                    <x-table.badge color="purple">{{ $product->category->name }}</x-table.badge>
                </x-table.cell>
                <x-table.cell>
                    <div class="text-sm font-semibold">{{ number_format($product->price) }} CDF</div>
                </x-table.cell>
                <x-table.cell>
                    <x-table.badge :color="$product->stock > 10 ? 'green' : 'red'">
                        {{ $product->stock }} unités
                    </x-table.badge>
                </x-table.cell>
                <x-table.cell>
                    <x-table.badge :color="$product->status === 'active' ? 'green' : 'gray'" dot>
                        {{ $product->status === 'active' ? 'Actif' : 'Inactif' }}
                    </x-table.badge>
                </x-table.cell>
                <x-table.cell align="right">
                    <x-table.actions>
                        <x-table.action-button href="{{ route('products.edit', $product) }}" wire:navigate>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </x-table.action-button>
                        <x-table.action-button wire:click="deleteProduct({{ $product->id }})" color="red">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </x-table.action-button>
                    </x-table.actions>
                </x-table.cell>
            </x-table.row>
        @endforeach
    </x-table.body>
</x-table.table>
```

### Exemple avec état vide personnalisé et action

```blade
<x-table.table>
    <x-table.head>
        <tr>
            <x-table.header>Client</x-table.header>
            <x-table.header>Téléphone</x-table.header>
            <x-table.header align="right">Actions</x-table.header>
        </tr>
    </x-table.head>
    <x-table.body>
        @forelse($clients as $client)
            <x-table.row>
                <x-table.cell>{{ $client->name }}</x-table.cell>
                <x-table.cell>{{ $client->phone }}</x-table.cell>
                <x-table.cell align="right">
                    <x-table.actions>
                        <x-table.action-button href="{{ route('clients.show', $client) }}">
                            Voir
                        </x-table.action-button>
                    </x-table.actions>
                </x-table.cell>
            </x-table.row>
        @empty
            <x-table.empty-state 
                colspan="3"
                title="Aucun client"
                description="Vous n'avez pas encore de clients."
            >
                <x-slot name="icon">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </x-slot>
                <x-slot name="action">
                    <a href="{{ route('clients.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Créer un client
                    </a>
                </x-slot>
            </x-table.empty-state>
        @endforelse
    </x-table.body>
</x-table.table>
```

## Props disponibles

### `<x-table.table>`
- `striped` (boolean, défaut: true) - Affiche des rayures alternées
- `hoverable` (boolean, défaut: true) - Active l'effet de survol

### `<x-table.header>`
- `align` (string, défaut: 'left') - Alignement du texte : 'left', 'center', 'right'
- `sortable` (boolean, défaut: false) - Active le tri
- `sort-key` (string) - Clé utilisée pour le tri

### `<x-table.row>`
- `hoverable` (boolean, défaut: true) - Active l'effet de survol sur la ligne

### `<x-table.cell>`
- `align` (string, défaut: 'left') - Alignement du texte : 'left', 'center', 'right'

### `<x-table.empty-state>`
- `colspan` (integer, défaut: 1) - Nombre de colonnes à fusionner
- `icon` (slot) - Icône personnalisée
- `title` (string) - Titre de l'état vide
- `description` (string) - Description de l'état vide
- `action` (slot) - Action à afficher (bouton, lien, etc.)

### `<x-table.badge>`
- `color` (string, défaut: 'gray') - Couleur : 'green', 'red', 'yellow', 'blue', 'indigo', 'purple', 'pink', 'gray'
- `dot` (boolean, défaut: false) - Affiche un point avant le texte

### `<x-table.actions>`
- `align` (string, défaut: 'right') - Alignement : 'left', 'center', 'right'

### `<x-table.action-button>`
- `color` (string, défaut: 'indigo') - Couleur : 'indigo', 'red', 'green', 'blue', 'yellow'
- `icon` (slot) - Icône personnalisée
- `href` (string) - URL pour créer un lien
- `wire:click` - Action Livewire

## Notes d'implémentation

Pour le tri avec Livewire, ajoutez cette méthode dans votre composant :

```php
public $sortField = '';
public $sortDirection = 'asc';

public function sortBy($field)
{
    if ($this->sortField === $field) {
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    } else {
        $this->sortField = $field;
        $this->sortDirection = 'asc';
    }
}
```

Et dans votre requête :

```php
$query = Product::query();

if ($this->sortField) {
    $query->orderBy($this->sortField, $this->sortDirection);
}

$products = $query->paginate(10);
```
