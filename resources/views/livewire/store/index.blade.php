<div x-data="{ showModal: false, showDeleteModal: false, storeToDelete: null, storeName: '', isEditing: false, showToggleModal: false, storeToToggle: null, toggleStoreName: '', toggleStoreStatus: false }"
     @open-store-modal.window="showModal = true"
     @open-edit-modal.window="isEditing = true; showModal = true"
     @close-store-modal.window="showModal = false; isEditing = false">
    <x-slot name="header">
        <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('dashboard')], ['label' => 'Magasins']]" />
    </x-slot>

    <div class="flex items-center justify-between mt-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestion des Magasins</h1>
            <p class="text-gray-500 mt-1">Gérez vos points de vente et entrepôts</p>
        </div>
        @if($canCreateStore)
            <button @click="isEditing = false; showModal = true; $wire.openCreateModal()"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Nouveau Magasin
            </button>
        @else
            <div class="inline-flex items-center px-4 py-2 bg-amber-100 text-amber-800 font-semibold rounded-lg border border-amber-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>Limite atteinte ({{ $storesUsage['current'] ?? 0 }}/{{ $storesUsage['max'] ?? '∞' }})</span>
                @if($organization)
                    <a href="{{ route('organizations.subscription', $organization) }}" class="ml-2 text-amber-900 underline hover:no-underline">Upgrader</a>
                @endif
            </div>
        @endif
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
                placeholder="Rechercher un magasin..."
            />

            <!-- Per Page Selector -->
            <div class="flex items-center space-x-2">
                <label for="perPage" class="text-sm font-medium text-gray-700 whitespace-nowrap">
                    Afficher :
                </label>
                <select id="perPage" wire:model.live="perPage"
                    class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <option value="5">5 magasins</option>
                    <option value="10">10 magasins</option>
                    <option value="25">25 magasins</option>
                    <option value="50">50 magasins</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Stores Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($stores as $store)
            <div wire:key="store-{{ $store->id }}"
                class="group bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg hover:border-indigo-200 transition-all duration-200">
                <!-- Header -->
                <div class="px-6 py-5 border-b border-gray-100">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-3 flex-1 min-w-0">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $store->is_main ? 'from-indigo-500 to-purple-600' : 'from-gray-400 to-gray-600' }} flex items-center justify-center shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="text-lg font-bold text-gray-900 truncate">{{ $store->name }}</h3>
                                    @if($store->is_main)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            Principal
                                        </span>
                                    @endif
                                </div>
                                @if($store->code)
                                    <p class="text-xs font-mono text-gray-500 bg-gray-50 inline-block px-2 py-0.5 rounded">{{ $store->code }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Body -->
                <div class="px-6 py-4 space-y-3">
                    <!-- Address -->
                    @if($store->address || $store->city)
                        <div class="flex items-start space-x-2 text-sm">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="text-gray-600">
                                {{ $store->address }}@if($store->address && $store->city), @endif{{ $store->city }}
                            </span>
                        </div>
                    @endif

                    <!-- Phone -->
                    @if($store->phone)
                        <div class="flex items-center space-x-2 text-sm">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span class="text-gray-600">{{ $store->phone }}</span>
                        </div>
                    @endif

                    <!-- Statistics -->
                    @if(isset($statistics[$store->id]))
                        <div class="grid grid-cols-2 gap-4 pt-3 mt-3 border-t border-gray-100">
                            <div class="bg-indigo-50 rounded-lg p-3 text-center">
                                <p class="text-2xl font-bold text-indigo-600">
                                    {{ $statistics[$store->id]['total_products'] ?? 0 }}
                                </p>
                                <p class="text-xs text-indigo-600 font-medium mt-1">{{ products_label() }}</p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-3 text-center">
                                <p class="text-xl font-bold text-green-600">
                                    {{ number_format($statistics[$store->id]['total_stock_value'] ?? 0, 0, ',', ' ') }}
                                </p>
                                <p class="text-xs text-green-600 font-medium mt-1">Valeur Stock</p>
                            </div>
                        </div>
                    @endif

                    <!-- Status -->
                    <div class="flex items-center justify-between pt-3 mt-3 border-t border-gray-100">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $store->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $store->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                            {{ $store->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                        <button @click="showToggleModal = true; storeToToggle = {{ $store->id }}; toggleStoreName = '{{ addslashes($store->name) }}'; toggleStoreStatus = {{ $store->is_active ? 'true' : 'false' }}"
                            class="text-sm text-indigo-600 hover:text-indigo-800 font-medium hover:underline transition">
                            {{ $store->is_active ? 'Désactiver' : 'Activer' }}
                        </button>
                    </div>
                </div>

                <!-- Actions -->
                <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                    <a href="{{ route('stores.show', $store->id) }}" wire:navigate
                        class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800 font-semibold group-hover:underline transition">
                        <span>Voir détails</span>
                        <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                    <div class="flex items-center space-x-1">
                        <button @click="$wire.openEditModal({{ $store->id }})"
                            wire:loading.attr="disabled"
                            wire:target="openEditModal({{ $store->id }})"
                            class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors disabled:opacity-50"
                            title="Modifier">
                            <svg wire:loading.remove wire:target="openEditModal({{ $store->id }})" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <svg wire:loading wire:target="openEditModal({{ $store->id }})" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                        @if(!$store->is_main)
                            <button
                                @click="showDeleteModal = true; storeToDelete = {{ $store->id }}; storeName = '{{ $store->name }}'"
                                class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                title="Supprimer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12 bg-white rounded-xl shadow-sm border border-gray-200">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun magasin</h3>
                    <p class="mt-1 text-sm text-gray-500">Commencez par créer votre premier magasin.</p>
                    <div class="mt-6">
                        <button @click="isEditing = false; showModal = true; $wire.openCreateModal()"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Nouveau Magasin
                        </button>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($stores->hasPages())
        <div class="mt-6">
            {{ $stores->links() }}
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    <x-delete-confirmation-modal
        :show="'showDeleteModal'"
        :item-name="'storeName'"
        item-type="ce magasin"
        on-cancel="showDeleteModal = false; storeToDelete = null"
        on-confirm="$wire.deleteStore(storeToDelete); showDeleteModal = false; storeToDelete = null"
    />

    <!-- Toggle Status Confirmation Modal -->
    <div x-show="showToggleModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div x-show="showToggleModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="showToggleModal = false; storeToToggle = null; toggleStoreName = ''"
            class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>

        <!-- Modal -->
        <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
            <div x-show="showToggleModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
                @click.stop
                @keydown.escape.window="showToggleModal = false"
                class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md pointer-events-auto p-6">

                <!-- Icon -->
                <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full mb-5"
                    :class="toggleStoreStatus ? 'bg-amber-100' : 'bg-green-100'">
                    <!-- Deactivate icon -->
                    <svg x-show="toggleStoreStatus" class="h-7 w-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                    <!-- Activate icon -->
                    <svg x-show="!toggleStoreStatus" class="h-7 w-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <!-- Content -->
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2" x-text="toggleStoreStatus ? 'Désactiver ce magasin' : 'Activer ce magasin'"></h3>
                    <div class="text-sm text-gray-600">
                        <p x-show="toggleStoreStatus">
                            Êtes-vous sûr de vouloir désactiver le magasin <strong x-text="toggleStoreName"></strong> ?
                            Les utilisateurs ne pourront plus y accéder.
                        </p>
                        <p x-show="!toggleStoreStatus">
                            Êtes-vous sûr de vouloir activer le magasin <strong x-text="toggleStoreName"></strong> ?
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 justify-center mt-6">
                    <button type="button"
                        @click="showToggleModal = false; storeToToggle = null; toggleStoreName = ''"
                        class="px-5 py-2.5 bg-white text-gray-700 font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                        Annuler
                    </button>
                    <button type="button"
                        @click="$wire.toggleStatus(storeToToggle); showToggleModal = false; storeToToggle = null; toggleStoreName = ''"
                        class="px-5 py-2.5 text-white font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2"
                        :class="toggleStoreStatus ? 'bg-amber-600 hover:bg-amber-700 focus:ring-amber-500' : 'bg-green-600 hover:bg-green-700 focus:ring-green-500'"
                        x-text="toggleStoreStatus ? 'Désactiver' : 'Activer'">
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Store -->
    <x-ui.alpine-modal name="store" max-width="2xl"
        title="Nouveau Magasin"
        edit-title="Modifier le Magasin"
        icon-bg="from-indigo-500 to-purple-600">
        <x-slot:icon>
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
        </x-slot:icon>

        <form wire:submit="save" wire:key="store-form-{{ $selectedStoreId ?? 'new' }}">
            <x-ui.alpine-modal-body>
                <x-store.form
                    :is-editing="$isEditMode"
                    submit-action="save"
                    cancel-action="closeModal"
                    form-prefix="form"
                />
            </x-ui.alpine-modal-body>

            <x-ui.alpine-modal-footer
                submit-text="Créer le magasin"
                edit-submit-text="Enregistrer les modifications"
                target="save"
            />
        </form>
    </x-ui.alpine-modal>
</div>
