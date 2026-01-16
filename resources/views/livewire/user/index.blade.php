<div x-data="{ showModal: false, showAssignModal: false, showDeleteModal: false, userToDelete: null, userName: '', isEditing: false }"
     @open-user-modal.window="showModal = true"
     @open-edit-modal.window="isEditing = true; showModal = true"
     @close-user-modal.window="showModal = false; isEditing = false"
     @open-assign-modal.window="showAssignModal = true"
     @close-assign-modal.window="showAssignModal = false">
    <x-slot name="header">
        <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('dashboard')], ['label' => 'Utilisateurs']]" />
    </x-slot>

    <div class="flex items-center justify-between mt-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestion des Utilisateurs</h1>
            <p class="text-gray-500 mt-1">Gérez les utilisateurs, leurs rôles et leurs affectations aux magasins</p>
        </div>
        <button @click="isEditing = false; showModal = true; $wire.openCreateModal()"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition duration-150">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Nouvel Utilisateur
        </button>
    </div>

    <!-- Toast Notifications -->
    <x-toast />

    <!-- Search and Filters -->
    <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <x-form.search-input
                wire:model.live.debounce.300ms="search"
                wireModel="search"
                placeholder="Rechercher un utilisateur..."
            />

            <!-- Role Filter -->
            <div class="relative">
                <select wire:model.live="roleFilter"
                    class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <option value="">Tous les rôles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->slug }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Store Filter -->
            <div class="relative">
                <select wire:model.live="storeFilter"
                    class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <option value="">Tous les magasins</option>
                    @foreach($stores as $store)
                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <x-table.table>
            <x-table.head>
                <tr>
                    <x-table.header>Utilisateur</x-table.header>
                    <x-table.header>Rôles</x-table.header>
                    <x-table.header>Magasins</x-table.header>
                    <x-table.header>Dernière connexion</x-table.header>
                    <x-table.header>Statut</x-table.header>
                    <x-table.header align="center">Actions</x-table.header>
                </tr>
            </x-table.head>

            <x-table.body>
                @forelse ($users as $user)
                    <x-table.row wire:key="user-{{ $user->id }}">
                        <x-table.cell>
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">{{ $user->initials() }}</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </x-table.cell>
                        <x-table.cell>
                            <div class="flex flex-wrap gap-1">
                                @forelse($user->roles as $role)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ $role->name }}
                                    </span>
                                @empty
                                    <span class="text-sm text-gray-400">Aucun rôle</span>
                                @endforelse
                            </div>
                        </x-table.cell>
                        <x-table.cell>
                            @if($user->stores->count() > 0)
                                <span class="text-sm text-gray-900"><span class="font-medium">{{ $user->stores->count() }}</span> magasin(s)</span>
                            @else
                                <span class="text-sm text-gray-400">Aucun</span>
                            @endif
                        </x-table.cell>
                        <x-table.cell>
                            @if($user->last_login_at)
                                <span class="text-sm text-gray-600">{{ $user->last_login_at->diffForHumans() }}</span>
                            @else
                                <span class="text-sm text-gray-400">Jamais</span>
                            @endif
                        </x-table.cell>
                        <x-table.cell>
                            <div class="flex items-center space-x-2">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox"
                                        wire:click="toggleUserStatus({{ $user->id }})"
                                        {{ ($user->is_active ?? true) ? 'checked' : '' }}
                                        class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                                @if($user->is_active ?? true)
                                    <span class="text-xs font-medium text-blue-700">Actif</span>
                                @else
                                    <span class="text-xs font-medium text-gray-500">Inactif</span>
                                @endif
                            </div>
                        </x-table.cell>
                        <x-table.cell align="center">
                            <div class="flex items-center justify-center space-x-2">
                                <!-- Gérer rôles et magasins -->
                                <button @click="$wire.openAssignModal({{ $user->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="openAssignModal({{ $user->id }})"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-purple-700 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors border border-purple-200 hover:border-purple-300 disabled:opacity-50"
                                    title="Gérer les rôles et magasins">
                                    <svg wire:loading.remove wire:target="openAssignModal({{ $user->id }})" class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                    </svg>
                                    <svg wire:loading wire:target="openAssignModal({{ $user->id }})" class="w-4 h-4 mr-1.5 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="hidden sm:inline">Accès</span>
                                </button>

                                <!-- Modifier infos utilisateur -->
                                <button @click="$wire.openEditModal({{ $user->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="openEditModal({{ $user->id }})"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors border border-indigo-200 hover:border-indigo-300 disabled:opacity-50"
                                    title="Modifier les informations de l'utilisateur">
                                    <svg wire:loading.remove wire:target="openEditModal({{ $user->id }})" class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <svg wire:loading wire:target="openEditModal({{ $user->id }})" class="w-4 h-4 mr-1.5 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="hidden sm:inline">Modifier</span>
                                </button>

                                @if (!$user->hasRole('super-admin'))
                                    <!-- Supprimer -->
                                    <button type="button"
                                        @click="showDeleteModal = true; userToDelete = {{ $user->id }}; userName = '{{ addslashes($user->name) }}'"
                                        class="text-red-600 hover:text-red-900 transition-colors p-2 rounded-lg hover:bg-red-50"
                                        title="Supprimer l'utilisateur">
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
                    <x-table.empty-state colspan="6" title="Aucun utilisateur trouvé"
                        description="Commencez par créer votre premier utilisateur.">
                        <x-slot name="action">
                            <button @click="isEditing = false; showModal = true; $wire.openCreateModal()"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition duration-150">
                                <svg class="w-4 h-4 mr-2 inline-block" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Créer un utilisateur
                            </button>
                        </x-slot>
                    </x-table.empty-state>
                @endforelse
            </x-table.body>
        </x-table.table>

        <!-- Pagination -->
        @if ($users->hasPages())
            <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <!-- Results Info -->
                    <div class="text-sm text-gray-700">
                        Affichage de
                        <span class="font-semibold text-indigo-600">{{ $users->firstItem() ?? 0 }}</span>
                        à
                        <span class="font-semibold text-indigo-600">{{ $users->lastItem() ?? 0 }}</span>
                        sur
                        <span class="font-semibold text-indigo-600">{{ $users->total() }}</span>
                        résultats
                    </div>

                    <!-- Pagination Links -->
                    <div>
                        {{ $users->links('vendor.livewire.tailwind') }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal Création/Edition Utilisateur -->
    <x-ui.alpine-modal
        name="user"
        max-width="2xl"
        title="Nouvel utilisateur"
        edit-title="Modifier l'utilisateur"
        icon-bg="from-indigo-500 to-purple-600">
        <x-slot:icon>
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </x-slot:icon>

        <form wire:submit.prevent="save">
            <x-ui.alpine-modal-body>
                <div class="space-y-5">
                    <!-- Name & Email -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom complet <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <input type="text" id="name" wire:model="name"
                                    placeholder="Ex: Jean Dupont"
                                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" />
                            </div>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input type="email" id="email" wire:model="email"
                                    placeholder="jean.dupont@example.com"
                                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" />
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <template x-if="!isEditing">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        Le mot de passe par défaut sera : <strong class="font-semibold">Password123!</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Statut du compte -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <label for="isActive" class="text-sm font-medium text-gray-700 cursor-pointer">
                                    Compte actif
                                </label>
                                <p class="text-xs text-gray-500">L'utilisateur peut se connecter à l'application</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="isActive" id="isActive" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>

                    <!-- Roles et Magasins -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Roles -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Rôles <span class="text-red-500">*</span>
                            </label>
                            <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-3 bg-gray-50 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                                @foreach ($roles as $role)
                                    <label class="flex items-center p-2 hover:bg-white rounded transition-colors cursor-pointer">
                                        <input type="checkbox" wire:model="selectedRoles" value="{{ $role->id }}"
                                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">{{ $role->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('selectedRoles')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Stores -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Magasins
                            </label>
                            <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-3 bg-gray-50 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                                @foreach ($stores as $store)
                                    <div class="flex items-center justify-between p-2 hover:bg-white rounded transition-colors">
                                        <label class="flex items-center flex-1 cursor-pointer">
                                            <input type="checkbox" wire:model.live="selectedStores"
                                                value="{{ $store->id }}"
                                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-700">{{ $store->name }}</span>
                                        </label>
                                        @if (in_array($store->id, $selectedStores))
                                            <div class="flex items-center space-x-2 ml-4">
                                                <select wire:model="storeRoles.{{ $store->id }}"
                                                    class="text-xs rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 py-1">
                                                    <option value="staff">Staff</option>
                                                    <option value="manager">Manager</option>
                                                </select>
                                                <label class="flex items-center text-xs whitespace-nowrap">
                                                    <input type="radio" wire:model="defaultStore"
                                                        value="{{ $store->id }}"
                                                        class="text-indigo-600 focus:ring-indigo-500">
                                                    <span class="ml-1">Par défaut</span>
                                                </label>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            @error('selectedStores')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-indigo-700">
                                    Assignez des rôles et des magasins à cet utilisateur pour définir ses permissions et son accès.
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

    <!-- Modal Assignation Rôles & Magasins -->
    <div x-show="showAssignModal"
         x-cloak
         x-on:keydown.escape.window="showAssignModal = false"
         x-init="$watch('showAssignModal', value => { document.body.style.overflow = value ? 'hidden' : '' })"
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-assign-title"
         role="dialog"
         aria-modal="true">

        <!-- Backdrop -->
        <div @click="showAssignModal = false; $wire.closeAssignModal()"
             x-show="showAssignModal"
             x-transition.opacity.duration.100ms
             class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm">
        </div>

        <!-- Modal Container -->
        <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
            <div x-show="showAssignModal"
                 @click.stop
                 x-transition:enter="ease-out duration-100"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative bg-white rounded-2xl shadow-2xl transform w-full sm:max-w-3xl flex flex-col pointer-events-auto"
                 style="max-height: 90vh;">

                <!-- Modal Header -->
                <div class="flex-shrink-0 flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-t-2xl">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900" id="modal-assign-title">Gérer les accès</h3>
                            @if($assignUser)
                                <p class="text-sm text-gray-600">{{ $assignUser->name }}</p>
                            @endif
                        </div>
                    </div>
                    <button @click="showAssignModal = false; $wire.closeAssignModal()" type="button"
                        class="rounded-lg p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <form wire:submit.prevent="updateAssignments" class="flex flex-col flex-1 overflow-hidden">
                    <div class="p-6 space-y-5 overflow-y-auto flex-1 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Roles -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Rôles <span class="text-red-500">*</span>
                                </label>
                                <div class="space-y-2 max-h-52 overflow-y-auto border border-gray-200 rounded-lg p-3 bg-gray-50 scrollbar-thin scrollbar-thumb-purple-300 scrollbar-track-gray-100">
                                    @foreach ($roles as $role)
                                        <label class="flex items-center p-3 hover:bg-white rounded-lg transition-colors cursor-pointer border border-transparent hover:border-purple-200">
                                            <input type="checkbox" wire:model="selectedRoles" value="{{ $role->id }}"
                                                class="rounded border-gray-300 text-purple-600 focus:ring-purple-500 w-5 h-5">
                                            <span class="ml-3 text-sm font-medium text-gray-700">{{ $role->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('selectedRoles')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Stores -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Magasins
                                </label>
                                <div class="space-y-2 max-h-52 overflow-y-auto border border-gray-200 rounded-lg p-3 bg-gray-50 scrollbar-thin scrollbar-thumb-indigo-300 scrollbar-track-gray-100">
                                    @foreach ($stores as $store)
                                        <div class="p-3 hover:bg-white rounded-lg transition-colors border border-transparent hover:border-indigo-200">
                                            <label class="flex items-center cursor-pointer">
                                                <input type="checkbox" wire:model.live="selectedStores"
                                                    value="{{ $store->id }}"
                                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-5 h-5">
                                                <span class="ml-3 text-sm font-medium text-gray-700">{{ $store->name }}</span>
                                            </label>
                                            @if (in_array($store->id, $selectedStores))
                                                <div class="mt-3 ml-8 flex items-center space-x-3">
                                                    <select wire:model="storeRoles.{{ $store->id }}"
                                                        class="text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 py-1.5">
                                                        <option value="staff">Staff</option>
                                                        <option value="manager">Manager</option>
                                                    </select>
                                                    <label class="flex items-center text-sm whitespace-nowrap">
                                                        <input type="radio" wire:model="defaultStore"
                                                            value="{{ $store->id }}"
                                                            class="text-indigo-600 focus:ring-indigo-500">
                                                        <span class="ml-2">Par défaut</span>
                                                    </label>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                @error('selectedStores')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Info Box -->
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-purple-700">
                                        Définissez les rôles et les magasins auxquels cet utilisateur aura accès.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex-shrink-0 bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-200 rounded-b-2xl">
                        <button type="button" @click="showAssignModal = false; $wire.closeAssignModal()"
                            class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Annuler
                        </button>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-purple-600 to-indigo-600 border border-transparent rounded-lg hover:from-purple-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition-all">
                            <svg wire:loading.remove wire:target="updateAssignments" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <svg wire:loading wire:target="updateAssignments" class="animate-spin w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="updateAssignments">Enregistrer</span>
                            <span wire:loading wire:target="updateAssignments">Enregistrement...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <x-delete-confirmation-modal show="showDeleteModal" itemName="userName" itemType="l'utilisateur"
        onConfirm="$wire.deleteUser(userToDelete); showDeleteModal = false; userToDelete = null; userName = ''"
        onCancel="showDeleteModal = false; userToDelete = null; userName = ''" />
</div>
