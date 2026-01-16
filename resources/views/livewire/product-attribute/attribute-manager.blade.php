<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Gestion des Attributs de Produits</h2>
            <p class="mt-1 text-sm text-gray-600">Configurez les attributs pour chaque type de produit</p>
        </div>
        <x-form.button wire:click="openModal" icon="plus">
            Nouvel Attribut
        </x-form.button>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Recherche -->
            <div class="lg:col-span-2">
                <x-form.input
                    wire:model.live.debounce.300ms="search"
                    type="search"
                    placeholder="Rechercher par nom ou code..."
                    icon="search" />
            </div>

            <!-- Filtre Type de Produit -->
            <div>
                <select wire:model.live="filterProductType"
                    class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <option value="">Tous les types</option>
                    @foreach($productTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->icon ?? '📦' }} {{ $type->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filtre Type d'Attribut -->
            <div>
                <select wire:model.live="filterType"
                    class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <option value="">Tous les formats</option>
                    <option value="text">Texte</option>
                    <option value="number">Nombre</option>
                    <option value="select">Liste</option>
                    <option value="boolean">Oui/Non</option>
                    <option value="color">Couleur</option>
                    <option value="date">Date</option>
                    <option value="textarea">Zone de texte</option>
                </select>
            </div>

            <!-- Per Page -->
            <div>
                <select wire:model.live="perPage"
                    class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <option value="10">10 attributs</option>
                    <option value="25">25 attributs</option>
                    <option value="50">50 attributs</option>
                    <option value="100">100 attributs</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <x-table.table>
            <x-table.head>
                <tr>
                    <x-table.header>Attribut</x-table.header>
                    <x-table.header>Type de Produit</x-table.header>
                    <x-table.header>Format</x-table.header>
                    <x-table.header>Options/Unité</x-table.header>
                    <x-table.header>Propriétés</x-table.header>
                    <x-table.header>Ordre</x-table.header>
                    <x-table.header align="center">Actions</x-table.header>
                </tr>
            </x-table.head>

            <x-table.body>
                @forelse ($attributes as $attribute)
                    <x-table.row wire:key="attribute-{{ $attribute->id }}">
                        <x-table.cell>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $attribute->name }}</div>
                                <div class="text-xs text-gray-500">{{ $attribute->code }}</div>
                            </div>
                        </x-table.cell>

                        <x-table.cell>
                            <div class="flex items-center">
                                <span class="text-lg mr-2">{{ $attribute->productType->icon ?? '📦' }}</span>
                                <span class="text-sm text-gray-900">{{ $attribute->productType->name }}</span>
                            </div>
                        </x-table.cell>

                        <x-table.cell>
                            @php
                                $typeLabels = [
                                    'text' => ['Texte', 'bg-gray-100 text-gray-800'],
                                    'number' => ['Nombre', 'bg-blue-100 text-blue-800'],
                                    'select' => ['Liste', 'bg-purple-100 text-purple-800'],
                                    'boolean' => ['Oui/Non', 'bg-green-100 text-green-800'],
                                    'color' => ['Couleur', 'bg-pink-100 text-pink-800'],
                                    'date' => ['Date', 'bg-yellow-100 text-yellow-800'],
                                    'textarea' => ['Zone de texte', 'bg-gray-100 text-gray-800'],
                                ];
                                $typeInfo = $typeLabels[$attribute->type] ?? ['Autre', 'bg-gray-100 text-gray-800'];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $typeInfo[1] }}">
                                {{ $typeInfo[0] }}
                            </span>
                        </x-table.cell>

                        <x-table.cell>
                            @if($attribute->type === 'select' && $attribute->options)
                                @php
                                    $options = is_array($attribute->options) ? $attribute->options : json_decode($attribute->options, true);
                                @endphp
                                <div class="text-xs text-gray-600">
                                    {{ count($options) }} options: {{ implode(', ', array_slice($options, 0, 3)) }}{{ count($options) > 3 ? '...' : '' }}
                                </div>
                            @elseif($attribute->unit)
                                <div class="text-xs text-gray-600">
                                    Unité: <span class="font-medium">{{ $attribute->unit }}</span>
                                </div>
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </x-table.cell>

                        <x-table.cell>
                            <div class="flex flex-wrap gap-1">
                                @if($attribute->is_required)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-700"
                                        title="Obligatoire">
                                        ★
                                    </span>
                                @endif
                                @if($attribute->is_variant_attribute)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-700"
                                        title="Génère des variantes">
                                        V
                                    </span>
                                @endif
                                @if($attribute->is_filterable)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700"
                                        title="Filtrable">
                                        F
                                    </span>
                                @endif
                                @if(!$attribute->is_visible)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700"
                                        title="Caché">
                                        👁️
                                    </span>
                                @endif
                            </div>
                        </x-table.cell>

                        <x-table.cell>
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 text-sm font-medium text-gray-700">
                                {{ $attribute->display_order }}
                            </span>
                        </x-table.cell>

                        <x-table.cell align="center">
                            <x-table.actions>
                                <x-table.action-button wire:click="openEditModal({{ $attribute->id }})" color="indigo">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </x-table.action-button>
                                <x-table.action-button
                                    wire:click="delete({{ $attribute->id }})"
                                    wire:confirm="Êtes-vous sûr de vouloir supprimer cet attribut ?"
                                    color="red">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </x-table.action-button>
                            </x-table.actions>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="7">
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun attribut trouvé</h3>
                                <p class="mt-1 text-sm text-gray-500">Commencez par créer un nouvel attribut.</p>
                                <div class="mt-6">
                                    <x-form.button wire:click="openModal" icon="plus">
                                        Créer un attribut
                                    </x-form.button>
                                </div>
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @endforelse
            </x-table.body>
        </x-table.table>

        @if($attributes->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $attributes->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Formulaire -->
    <div wire:key="attribute-modal">
        <x-modal name="showModal" maxWidth="2xl" :showHeader="false">
            <div class="bg-white rounded-xl shadow-xl">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">
                            {{ $editMode ? 'Modifier l\'attribut' : 'Nouvel attribut' }}
                        </h3>
                    </div>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <form wire:submit.prevent="save">
                    <div class="p-6 space-y-5">
                    <div class="grid grid-cols-3 gap-4">
                        <!-- Type de Produit -->
                        <div>
                            <x-form.label for="product_type_id" required>Type de Produit</x-form.label>
                            <x-form.select wire:model.live="product_type_id" id="product_type_id" required>
                                <option value="">-- Sélectionner --</option>
                                @foreach($productTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->icon ?? '📦' }} {{ $type->name }}</option>
                                @endforeach
                            </x-form.select>
                            <x-form.error for="product_type_id" />
                        </div>

                        <!-- Nom -->
                        <div>
                            <x-form.label for="name" required>Nom de l'attribut</x-form.label>
                            <x-form.input wire:model="name" id="name" type="text" placeholder="Ex: Taille, Couleur..." />
                            <x-form.error for="name" />
                        </div>

                        <!-- Code -->
                        <div>
                            <x-form.label for="code">Code (auto-généré)</x-form.label>
                            <x-form.input wire:model="code" id="code" type="text" placeholder="Ex: taille..." />
                            <x-form.error for="code" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Type -->
                        <div>
                            <x-form.label for="type" required>Format de l'attribut</x-form.label>
                            <x-form.select wire:model.live="type" id="type" required>
                                <option value="text">Texte (input texte)</option>
                                <option value="number">Nombre</option>
                                <option value="select">Liste déroulante</option>
                                <option value="boolean">Oui/Non (case à cocher)</option>
                                <option value="color">Couleur (sélecteur)</option>
                                <option value="date">Date</option>
                                <option value="textarea">Zone de texte (multi-lignes)</option>
                            </x-form.select>
                            <x-form.error for="type" />
                        </div>

                        <!-- Ordre d'affichage -->
                        <div>
                            <x-form.label for="display_order">Ordre d'affichage</x-form.label>
                            <x-form.input wire:model="display_order" id="display_order" type="number" min="0" placeholder="0" />
                            <p class="mt-1 text-xs text-gray-500">Plus petit = affiché en premier</p>
                        </div>
                    </div>

                    <!-- Options (si select) -->
                    @if($type === 'select')
                        <div>
                            <x-form.label for="options">Options (séparées par des virgules)</x-form.label>
                            <x-form.input wire:model="options" id="options" type="text" placeholder="Ex: XS, S, M, L, XL, XXL" />
                            <p class="mt-1 text-xs text-gray-500">Entrez les options séparées par des virgules</p>
                            <x-form.error for="options" />
                        </div>
                    @endif

                    <!-- Unité (si number) -->
                    @if($type === 'number')
                        <div>
                            <x-form.label for="unit">Unité de mesure</x-form.label>
                            <x-form.input wire:model="unit" id="unit" type="text" placeholder="Ex: kg, cm, W, V..." />
                            <p class="mt-1 text-xs text-gray-500">Optionnel: kg, cm, litres, watts, etc.</p>
                            <x-form.error for="unit" />
                        </div>
                    @endif

                    <!-- Valeur par défaut -->
                    @if($type === 'boolean')
                        <div>
                            <x-form.label for="default_value">Texte du label (optionnel)</x-form.label>
                            <x-form.input wire:model="default_value" id="default_value" type="text" placeholder="Ex: Produit biologique, En promotion..." />
                            <x-form.error for="default_value" />
                        </div>
                    @endif

                    <!-- Propriétés -->
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                        <h4 class="text-sm font-medium text-gray-900">Propriétés de l'attribut</h4>

                        <label class="flex items-center">
                            <input type="checkbox" wire:model="is_required" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">
                                <span class="font-medium">Obligatoire</span>
                                <span class="text-gray-500"> - Doit être rempli lors de la création du produit</span>
                            </span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" wire:model="is_variant_attribute" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">
                                <span class="font-medium">Génère des variantes</span>
                                <span class="text-gray-500"> - Crée automatiquement des combinaisons (Ex: Taille × Couleur)</span>
                            </span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" wire:model="is_filterable" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">
                                <span class="font-medium">Filtrable</span>
                                <span class="text-gray-500"> - Peut être utilisé comme filtre dans les listes</span>
                            </span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" wire:model="is_visible" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">
                                <span class="font-medium">Visible</span>
                                <span class="text-gray-500"> - Affiché dans l'interface</span>
                            </span>
                        </label>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-200">
                        <x-form.button variant="secondary" type="button" wire:click="closeModal">
                            Annuler
                        </x-form.button>
                        <x-form.button type="submit" wire:loading.attr="disabled">
                            <svg wire:loading.remove wire:target="save" class="w-5 h-5 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <svg wire:loading wire:target="save" class="animate-spin w-5 h-5 mr-2 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="save">
                                {{ $editMode ? 'Mettre à jour' : 'Créer' }}
                            </span>
                            <span wire:loading wire:target="save">
                                Enregistrement...
                            </span>
                        </x-form.button>
                    </div>
                </form>
            </div>
        </x-modal>
    </div>
</div>
