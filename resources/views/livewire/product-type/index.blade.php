<div x-data="{ showModal: false, showDeleteModal: false, typeToDelete: null, typeName: '', isEditing: false }"
     @open-producttype-modal.window="showModal = true"
     @open-edit-modal.window="isEditing = true; showModal = true"
     @close-producttype-modal.window="showModal = false; isEditing = false">
    <x-slot name="header">
        <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('dashboard')], ['label' => 'Types de Produits']]" />
    </x-slot>

    <div class="flex items-center justify-between mt-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Types de Produits</h1>
            <p class="text-gray-500 mt-1">Gérez les types de produits et leurs caractéristiques</p>
        </div>
        <button @click="isEditing = false; showModal = true; $wire.openCreateModal()"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition duration-150">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Nouveau Type
        </button>
    </div>

    <!-- Toast Notifications -->
    <x-toast />

    <!-- Search and Filters -->
    <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Search -->
            <x-form.search-input
                wire:model.live.debounce.300ms="search"
                wireModel="search"
                placeholder="Rechercher un type de produit..."
            />

            <!-- Per Page Selector -->
            <div class="flex items-center space-x-2">
                <label for="perPage" class="text-sm font-medium text-gray-700 whitespace-nowrap">
                    Afficher :
                </label>
                <select id="perPage" wire:model.live="perPage"
                    class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <option value="5">5 types</option>
                    <option value="10">10 types</option>
                    <option value="25">25 types</option>
                    <option value="50">50 types</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <x-table.table>
            <x-table.head>
                <tr>
                    <x-table.header>Type</x-table.header>
                    <x-table.header>Description</x-table.header>
                    <x-table.header>Caractéristiques</x-table.header>
                    <x-table.header>Produits</x-table.header>
                    <x-table.header>Statut</x-table.header>
                    <x-table.header align="center">Actions</x-table.header>
                </tr>
            </x-table.head>

            <x-table.body>
                @forelse ($productTypes as $type)
                    <x-table.row wire:key="type-{{ $type->id }}">
                        <x-table.cell>
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg flex items-center justify-center">
                                    <span class="text-xl">{{ $type->icon ?? '📦' }}</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $type->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $type->slug }}</div>
                                </div>
                            </div>
                        </x-table.cell>
                        <x-table.cell>
                            <span class="text-sm text-gray-600">
                                {{ $type->description ? Str::limit($type->description, 40) : '—' }}
                            </span>
                        </x-table.cell>
                        <x-table.cell>
                            <div class="flex flex-wrap gap-1">
                                @if($type->has_variants)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        Variants
                                    </span>
                                @endif
                                @if($type->has_expiry_date)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                        Expiration
                                    </span>
                                @endif
                                @if($type->has_weight)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        Poids
                                    </span>
                                @endif
                                @if($type->has_dimensions)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                        Dimensions
                                    </span>
                                @endif
                                @if($type->has_serial_number)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                        N° Série
                                    </span>
                                @endif
                                @if(!$type->has_variants && !$type->has_expiry_date && !$type->has_weight && !$type->has_dimensions && !$type->has_serial_number)
                                    <span class="text-xs text-gray-400">Aucune</span>
                                @endif
                            </div>
                        </x-table.cell>
                        <x-table.cell>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                {{ $type->products_count }}
                                {{ $type->products_count > 1 ? 'produits' : 'produit' }}
                            </span>
                        </x-table.cell>
                        <x-table.cell>
                            <button wire:click="toggleActive({{ $type->id }})"
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium transition-colors cursor-pointer
                                {{ $type->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                <span class="w-2 h-2 rounded-full mr-1.5 {{ $type->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                {{ $type->is_active ? 'Actif' : 'Inactif' }}
                            </button>
                        </x-table.cell>
                        <x-table.cell align="center">
                            <div class="flex items-center justify-center space-x-3">
                                <button @click="$wire.openEditModal({{ $type->id }})"
                                    class="text-indigo-600 hover:text-indigo-900 transition-colors p-2 rounded-lg hover:bg-indigo-50"
                                    title="Modifier">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                @if ($type->products_count == 0)
                                    <button type="button"
                                        @click="showDeleteModal = true; typeToDelete = {{ $type->id }}; typeName = '{{ addslashes($type->name) }}'"
                                        class="text-red-600 hover:text-red-900 transition-colors p-2 rounded-lg hover:bg-red-50"
                                        title="Supprimer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.empty-state colspan="6" title="Aucun type de produit trouvé"
                        description="Commencez par créer votre premier type de produit.">
                        <x-slot name="action">
                            <x-form.button wire:click="openCreateModal" size="sm">
                                <svg class="w-4 h-4 mr-2 inline-block" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Créer un type
                            </x-form.button>
                        </x-slot>
                    </x-table.empty-state>
                @endforelse
            </x-table.body>
        </x-table.table>
    </div>

    <!-- Modal - Géré uniquement par Alpine.js -->
    <div x-show="showModal"
         x-cloak
         x-on:keydown.escape.window="showModal = false"
         x-init="$watch('showModal', value => { document.body.style.overflow = value ? 'hidden' : '' })"
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true">

        <!-- Backdrop -->
        <div @click="showModal = false"
             x-show="showModal"
             x-transition.opacity.duration.150ms
             class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm">
        </div>

        <!-- Modal Container -->
        <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
            <div x-show="showModal"
                 @click.stop
                 x-transition:enter="ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-100"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative bg-white rounded-2xl shadow-2xl transform w-full sm:max-w-xl flex flex-col pointer-events-auto"
                 style="max-height: 90vh;">

                <!-- Modal Header -->
                <div class="flex-shrink-0 flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white rounded-t-2xl">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900" x-text="isEditing ? 'Modifier le type' : 'Nouveau type de produit'"></h3>
                    </div>
                    <button @click="showModal = false" type="button"
                        class="rounded-lg p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" wire:key="form-{{ $editingProductTypeId ?? 'create' }}" class="flex flex-col overflow-hidden flex-1">
                    <!-- Modal Body avec scroll -->
                    <div class="flex-1 overflow-y-auto p-6">
                        <div class="space-y-6">
                            <!-- Informations de base -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Nom -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nom du type <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="name" wire:model="form.name"
                                        placeholder="Ex: Électronique, Alimentaire..."
                                        class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" />
                                    @error('form.name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Icône -->
                                <div x-data="{ showEmojiPicker: false }">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Icône (emoji)
                                    </label>
                                    <div class="relative">
                                        <button type="button"
                                            @click="showEmojiPicker = !showEmojiPicker"
                                            class="flex items-center justify-between w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition hover:bg-gray-50">
                                            <span class="flex items-center gap-2">
                                                <span class="text-2xl">{{ $form->icon ?: '📦' }}</span>
                                                <span class="text-gray-500">Cliquez pour choisir</span>
                                            </span>
                                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="showEmojiPicker ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>

                                        <!-- Emoji Picker Dropdown -->
                                        <div x-show="showEmojiPicker"
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 translate-y-1"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            x-transition:leave="transition ease-in duration-150"
                                            x-transition:leave-start="opacity-100 translate-y-0"
                                            x-transition:leave-end="opacity-0 translate-y-1"
                                            @click.outside="showEmojiPicker = false"
                                            class="absolute z-50 mt-2 w-full bg-white rounded-xl shadow-xl border border-gray-200 p-4 max-h-80 overflow-y-auto">

                                            <!-- Categories -->
                                            <div class="space-y-3">
                                                @foreach([
                                                    ['Commerce', ['📦', '🛒', '🛍️', '🏪', '🏬', '💼', '📋', '🏷️', '💳', '💰', '💵', '🧾', '📊', '📈', '🛡️', '✨']],
                                                    ['Alimentation', ['🍎', '🥦', '🥩', '🍞', '🧀', '🥛', '🍺', '🍷', '☕', '🍰', '🍫', '🍬', '🥤', '🍿', '🧁', '🍪']],
                                                    ['Électronique', ['📱', '💻', '🖥️', '⌨️', '🖨️', '📷', '🎧', '🔌', '💡', '🔋', '📺', '🎮', '🕹️', '📡', '🔊', '⌚']],
                                                    ['Mode', ['👕', '👖', '👗', '👠', '👟', '👜', '🎒', '👒', '🧢', '👔', '🧥', '🧤', '🧣', '👓', '💍', '⌚']],
                                                    ['Maison', ['🏠', '🛋️', '🛏️', '🚿', '🧹', '🔧', '🔨', '🪛', '🔩', '🧰', '🪣', '🧴', '🧼', '🪥', '🧽', '🗑️']],
                                                    ['Santé', ['💊', '💉', '🩺', '🩹', '🧪', '💄', '🧴', '🪮', '🧻', '🫧', '🧫', '🩼', '🦷', '👁️', '💅', '🧖']],
                                                    ['Sport', ['⚽', '🏀', '🎾', '🏈', '⚾', '🎿', '🚴', '🏋️', '🎣', '🎯', '🎱', '🏓', '🎨', '🎭', '🎪', '🎢']],
                                                    ['Transport', ['🚗', '🚕', '🚙', '🚌', '🚎', '🏎️', '🚓', '🚑', '🚒', '🛵', '🏍️', '🚲', '✈️', '🚀', '🛳️', '⛽']],
                                                ] as $category)
                                                    <div>
                                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">{{ $category[0] }}</p>
                                                        <div class="grid grid-cols-8 gap-1">
                                                            @foreach($category[1] as $emoji)
                                                                <button type="button"
                                                                    wire:click="setIcon('{{ $emoji }}')"
                                                                    @click="showEmojiPicker = false"
                                                                    class="p-2 text-xl hover:bg-indigo-100 rounded-lg transition-colors flex items-center justify-center {{ $form->icon === $emoji ? 'bg-indigo-100 ring-2 ring-indigo-500' : '' }}">
                                                                    {{ $emoji }}
                                                                </button>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Description
                                </label>
                                <textarea id="description" wire:model="form.description" rows="3"
                                    class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none"
                                    placeholder="Décrivez ce type de produit..."></textarea>
                            </div>

                            <!-- Caractéristiques -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Caractéristiques du type
                                </label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach([
                                        ['has_variants', 'Variants', 'Taille, couleur...', 'blue'],
                                        ['has_expiry_date', 'Expiration', 'Date de péremption', 'red'],
                                        ['has_weight', 'Poids', 'Kilogrammes', 'green'],
                                        ['has_dimensions', 'Dimensions', 'L x l x H', 'purple'],
                                        ['has_serial_number', 'N° Série', 'Numéro unique', 'gray'],
                                        ['is_active', 'Actif', 'Disponible', 'green'],
                                    ] as $checkbox)
                                        <label class="flex items-center gap-3 p-3 rounded-lg border-2 cursor-pointer transition-all {{ $form->{$checkbox[0]} ? 'border-'.$checkbox[3].'-400 bg-'.$checkbox[3].'-50' : 'border-gray-200 bg-gray-50 hover:border-'.$checkbox[3].'-200' }}">
                                            <input type="checkbox" wire:model.live="form.{{ $checkbox[0] }}"
                                                class="w-5 h-5 text-{{ $checkbox[3] }}-600 border-gray-300 rounded focus:ring-{{ $checkbox[3] }}-500">
                                            <div>
                                                <span class="block text-sm font-medium text-gray-900">{{ $checkbox[1] }}</span>
                                                <span class="block text-xs text-gray-500">{{ $checkbox[2] }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Info Box -->
                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-amber-700">
                                            Les types de produits définissent les caractéristiques disponibles lors de la création de produits.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex-shrink-0 flex justify-end space-x-3 p-6 border-t border-gray-200 bg-gray-50/50 rounded-b-2xl">
                        <button type="button" @click="showModal = false"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Annuler
                        </button>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors disabled:opacity-50"
                            wire:loading.attr="disabled">
                            <svg wire:loading.remove wire:target="save" class="w-5 h-5 mr-2 inline-block" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <svg wire:loading wire:target="save" class="animate-spin w-5 h-5 mr-2 inline-block"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span wire:loading.remove wire:target="save" x-text="isEditing ? 'Mettre à jour' : 'Créer'"></span>
                            <span wire:loading wire:target="save">Enregistrement...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <x-delete-confirmation-modal show="showDeleteModal" itemName="typeName" itemType="le type de produit"
        on-confirm="$wire.delete(typeToDelete); showDeleteModal = false; typeToDelete = null; typeName = ''"
        on-cancel="showDeleteModal = false; typeToDelete = null; typeName = ''" />
</div>
