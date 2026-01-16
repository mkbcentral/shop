<div x-data="{ showModal: false, showDeleteModal: false, categoryToDelete: null, categoryName: '', isEditing: false }"
     @open-category-modal.window="showModal = true"
     @open-edit-modal.window="isEditing = true; showModal = true"
     @close-category-modal.window="showModal = false; isEditing = false">
    <x-slot name="header">
        <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('dashboard')], ['label' => 'Catégories']]" />
    </x-slot>

    <div class="flex items-center justify-between mt-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Catégories</h1>
            <p class="text-gray-500 mt-1">Gérez les catégories de produits</p>
        </div>
        <button @click="isEditing = false; showModal = true; $wire.openCreateModal()"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition duration-150">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Nouvelle Catégorie
        </button>
    </div>

    <!-- Toast Notifications -->
    <x-toast />

    <!-- Search and Filters -->
    <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Search -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search"
                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition"
                    placeholder="Rechercher une catégorie...">
            </div>

            <!-- Per Page Selector -->
            <div class="flex items-center space-x-2">
                <label for="perPage" class="text-sm font-medium text-gray-700 whitespace-nowrap">
                    Afficher :
                </label>
                <select id="perPage" wire:model.live="perPage"
                    class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <option value="5">5 catégories</option>
                    <option value="10">10 catégories</option>
                    <option value="25">25 catégories</option>
                    <option value="50">50 catégories</option>
                    <option value="100">100 catégories</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <x-table.table>
            <x-table.head>
                <tr>
                    <x-table.header>Nom</x-table.header>
                    <x-table.header>Description</x-table.header>
                    <x-table.header>Nombre de Produits</x-table.header>
                    <x-table.header>Date de création</x-table.header>
                    <x-table.header align="center">Actions</x-table.header>
                </tr>
            </x-table.head>

            <x-table.body>
                @forelse ($categories as $category)
                    <x-table.row wire:key="category-{{ $category->id }}">
                        <x-table.cell>
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                    <span
                                        class="text-white font-bold text-sm">{{ strtoupper(substr($category->name, 0, 2)) }}</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $category->slug }}</div>
                                </div>
                            </div>
                        </x-table.cell>
                        <x-table.cell>
                            <span class="text-sm text-gray-600">
                                {{ $category->description ? Str::limit($category->description, 50) : '—' }}
                            </span>
                        </x-table.cell>
                        <x-table.cell>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                {{ $category->products_count }}
                                {{ $category->products_count > 1 ? 'produits' : 'produit' }}
                            </span>
                        </x-table.cell>
                        <x-table.cell>
                            <span class="text-sm text-gray-600">{{ $category->created_at->format('d/m/Y') }}</span>
                        </x-table.cell>
                        <x-table.cell align="center">
                            <div class="flex items-center justify-center space-x-3">
                                <button @click="$wire.openEditModal({{ $category->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="openEditModal({{ $category->id }})"
                                    class="text-indigo-600 hover:text-indigo-900 transition-colors p-2 rounded-lg hover:bg-indigo-50 disabled:opacity-50"
                                    title="Modifier">
                                    <svg wire:loading.remove wire:target="openEditModal({{ $category->id }})" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <svg wire:loading wire:target="openEditModal({{ $category->id }})" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </button>
                                @if ($category->products_count == 0)
                                    <button type="button"
                                        @click="showDeleteModal = true; categoryToDelete = {{ $category->id }}; categoryName = '{{ addslashes($category->name) }}'"
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
                    <x-table.empty-state colspan="5" title="Aucune catégorie trouvée"
                        description="Commencez par créer votre première catégorie de produits.">
                        <x-slot name="action">
                            <x-form.button @click="isEditing = false; showModal = true; $wire.openCreateModal()" size="sm">
                                <svg class="w-4 h-4 mr-2 inline-block" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Créer une catégorie
                            </x-form.button>
                        </x-slot>
                    </x-table.empty-state>
                @endforelse
            </x-table.body>
        </x-table.table>

        <!-- Pagination -->
        @if ($categories->hasPages())
            <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <!-- Results Info -->
                    <div class="text-sm text-gray-700">
                        Affichage de
                        <span class="font-semibold text-indigo-600">{{ $categories->firstItem() ?? 0 }}</span>
                        à
                        <span class="font-semibold text-indigo-600">{{ $categories->lastItem() ?? 0 }}</span>
                        sur
                        <span class="font-semibold text-indigo-600">{{ $categories->total() }}</span>
                        résultats
                    </div>

                    <!-- Pagination Links -->
                    <div>
                        {{ $categories->links('vendor.livewire.tailwind') }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal utilisant le composant réutilisable -->
    <x-ui.alpine-modal
        name="category"
        max-width="lg"
        title="Nouvelle catégorie"
        edit-title="Modifier la catégorie"
        icon-bg="from-indigo-500 to-purple-600">
        <x-slot:icon>
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
            </svg>
        </x-slot:icon>

        <form wire:submit.prevent="save" wire:key="category-form-{{ $form->categoryId ?? 'new' }}">
            <x-ui.alpine-modal-body>
                <div class="space-y-5">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom de la catégorie <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                            <input type="text" id="name" wire:model="form.name"
                                placeholder="Ex: Électronique, Vêtements, Alimentation..."
                                class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" />
                        </div>
                        @error('form.name')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea id="description" wire:model.live="form.description" rows="4"
                            class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none"
                            placeholder="Décrivez brièvement cette catégorie..."></textarea>
                        @error('form.description')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Info Box -->
                    <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-indigo-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-indigo-700">
                                    Les catégories vous permettent d'organiser vos produits et de faciliter la
                                    navigation dans votre inventaire.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </x-ui.alpine-modal-body>

            <x-ui.alpine-modal-footer
                submit-text="Créer"
                edit-submit-text="Mettre à jour"
                target="save"
            />
        </form>
    </x-ui.alpine-modal>

    <!-- Delete Confirmation Modal -->
    <x-delete-confirmation-modal show="showDeleteModal" itemName="categoryName" itemType="la catégorie"
        onConfirm="$wire.delete(categoryToDelete); showDeleteModal = false; categoryToDelete = null; categoryName = ''"
        onCancel="showDeleteModal = false; categoryToDelete = null; categoryName = ''" />
</div>
