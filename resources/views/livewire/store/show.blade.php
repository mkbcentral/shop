<div x-data="{ activeTab: @entangle('activeTab').live, showModal: false, isEditing: true }"
     @open-store-edit-modal.window="showModal = true"
     @close-store-edit-modal.window="showModal = false">
    <x-slot name="header">
        <x-breadcrumb :items="[
            ['label' => 'Accueil', 'url' => route('dashboard')],
            ['label' => 'Magasins', 'url' => route('stores.index')],
            ['label' => $store->name],
        ]" />
    </x-slot>

    <!-- Toast Notifications -->
    <x-toast />

    <!-- Header -->
    <div class="flex items-center justify-between mt-4 mb-6">
        <div class="flex items-center space-x-4">
            <div
                class="flex-shrink-0 h-16 w-16 bg-gradient-to-br {{ $store->is_main ? 'from-indigo-500 to-purple-600' : 'from-gray-500 to-gray-600' }} rounded-xl flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $store->name }}</h1>
                <div class="flex items-center space-x-3 mt-1">
                    @if($store->code)
                        <span class="text-sm text-gray-500">Code: {{ $store->code }}</span>
                    @endif
                    @if($store->is_main)
                        <span
                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            Principal
                        </span>
                    @endif
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $store->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $store->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <button @click="activeTab = 'overview'"
                :class="activeTab === 'overview' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Vue d'ensemble
            </button>
            <button @click="activeTab = 'stock'"
                :class="activeTab === 'stock' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Stock
            </button>
            <button @click="activeTab = 'transfers'"
                :class="activeTab === 'transfers' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Transferts
            </button>
            <button @click="activeTab = 'users'"
                :class="activeTab === 'users' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Utilisateurs
            </button>
            <button @click="activeTab = 'sales'"
                :class="activeTab === 'sales' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Ventes
            </button>
            <button @click="activeTab = 'purchases'"
                :class="activeTab === 'purchases' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Achats
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div>
        <!-- Overview Tab -->
        <div x-show="activeTab === 'overview'" x-transition>
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <x-stat-card
                    :title="products_label() . ' en Stock'"
                    :value="$statistics['total_products']"
                    color="blue"
                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />'
                />
                <x-stat-card
                    title="Ventes Totales"
                    :value="$statistics['total_sales']"
                    color="green"
                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />'
                />
                <x-stat-card
                    title="Valeur du Stock"
                    :value="format_currency($statistics['total_stock_value'])"
                    color="purple"
                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'
                />
                <x-stat-card
                    title="Alertes Stock"
                    :value="$statistics['low_stock_count'] + $statistics['out_of_stock_count']"
                    color="red"
                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />'
                />
            </div>

            <!-- Store Information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations du magasin</h3>
                    <dl class="space-y-3">
                        @if($store->organization)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Organisation</dt>
                                <dd class="mt-1">
                                    <a href="{{ route('organizations.show', $store->organization) }}"
                                        class="inline-flex items-center gap-2 text-sm text-indigo-600 hover:text-indigo-800 font-medium transition"
                                        wire:navigate>
                                        <span class="flex items-center justify-center w-6 h-6 bg-indigo-100 rounded-md">
                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        </span>
                                        {{ $store->organization->name }}
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                </dd>
                            </div>
                        @endif
                        @if($store->address)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Adresse</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $store->address }}@if($store->city), {{ $store->city }}@endif
                                </dd>
                            </div>
                        @endif
                        @if($store->phone)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Téléphone</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $store->phone }}</dd>
                            </div>
                        @endif
                        @if($store->email)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $store->email }}</dd>
                            </div>
                        @endif
                        @if($store->description)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Description</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $store->description }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Date de création</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $store->created_at->format('d/m/Y à H:i') }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistiques détaillées</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Stock faible</dt>
                            <dd class="mt-1 text-sm text-yellow-600 font-semibold">
                                {{ $statistics['low_stock_count'] }} {{ strtolower(products_label()) }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Rupture de stock</dt>
                            <dd class="mt-1 text-sm text-red-600 font-semibold">
                                {{ $statistics['out_of_stock_count'] }} {{ strtolower(products_label()) }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total ventes</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-semibold">
                                @currency($statistics['total_sales_amount'])
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Stock Tab -->
        <div x-show="activeTab === 'stock'" x-cloak>
            @if(isset($stockItems))
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <x-table.table>
                        <x-table.head>
                            <tr>
                                <x-table.header>Produit</x-table.header>
                                <x-table.header>SKU</x-table.header>
                                <x-table.header>Quantité</x-table.header>
                                <x-table.header>Statut</x-table.header>
                            </tr>
                        </x-table.head>
                        <x-table.body>
                            @forelse ($stockItems as $item)
                                @if($item->variant && $item->variant->product)
                                <x-table.row>
                                    <x-table.cell>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $item->variant->product->name }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $item->variant->name }}</div>
                                        </div>
                                    </x-table.cell>
                                    <x-table.cell>{{ $item->variant->sku }}</x-table.cell>
                                    <x-table.cell>
                                        <span class="font-semibold">{{ $item->quantity }}</span>
                                    </x-table.cell>
                                    <x-table.cell>
                                        @if($item->quantity == 0)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Rupture
                                            </span>
                                        @elseif($item->quantity < 10)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Faible
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Disponible
                                            </span>
                                        @endif
                                    </x-table.cell>
                                </x-table.row>
                                @endif
                            @empty
                                <x-table.row>
                                    <x-table.cell colspan="4">
                                        <div class="text-center py-8 text-gray-500">
                                            Aucun produit en stock
                                        </div>
                                    </x-table.cell>
                                </x-table.row>
                            @endforelse
                        </x-table.body>
                    </x-table.table>
                </div>

                @if($stockItems->hasPages())
                    <div class="mt-6">
                        {{ $stockItems->links() }}
                    </div>
                @endif
            @endif
        </div>

        <!-- Transfers Tab -->
        <div x-show="activeTab === 'transfers'" x-cloak>
            @if(isset($outgoingTransfers) && isset($incomingTransfers))
                <div class="space-y-6">
                    <!-- Outgoing Transfers -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Transferts sortants</h3>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <x-table.table>
                                <x-table.head>
                                    <tr>
                                        <x-table.header>Référence</x-table.header>
                                        <x-table.header>Vers</x-table.header>
                                        <x-table.header>Statut</x-table.header>
                                        <x-table.header>Date</x-table.header>
                                    </tr>
                                </x-table.head>
                                <x-table.body>
                                    @forelse ($outgoingTransfers as $transfer)
                                        <x-table.row>
                                            <x-table.cell>
                                                <a href="{{ route('transfers.show', $transfer->id) }}" wire:navigate
                                                    class="text-indigo-600 hover:text-indigo-800 font-medium">
                                                    {{ $transfer->reference }}
                                                </a>
                                            </x-table.cell>
                                            <x-table.cell>{{ $transfer->toStore->name }}</x-table.cell>
                                            <x-table.cell>
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($transfer->status === 'completed') bg-green-100 text-green-800
                                                    @elseif($transfer->status === 'in_transit') bg-blue-100 text-blue-800
                                                    @elseif($transfer->status === 'cancelled') bg-red-100 text-red-800
                                                    @else bg-yellow-100 text-yellow-800 @endif">
                                                    {{ ucfirst($transfer->status) }}
                                                </span>
                                            </x-table.cell>
                                            <x-table.cell>{{ $transfer->created_at->format('d/m/Y') }}</x-table.cell>
                                        </x-table.row>
                                    @empty
                                        <x-table.row>
                                            <x-table.cell colspan="4">
                                                <div class="text-center py-8 text-gray-500">
                                                    Aucun transfert sortant
                                                </div>
                                            </x-table.cell>
                                        </x-table.row>
                                    @endforelse
                                </x-table.body>
                            </x-table.table>
                        </div>
                    </div>

                    <!-- Incoming Transfers -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Transferts entrants</h3>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <x-table.table>
                                <x-table.head>
                                    <tr>
                                        <x-table.header>Référence</x-table.header>
                                        <x-table.header>Depuis</x-table.header>
                                        <x-table.header>Statut</x-table.header>
                                        <x-table.header>Date</x-table.header>
                                    </tr>
                                </x-table.head>
                                <x-table.body>
                                    @forelse ($incomingTransfers as $transfer)
                                        <x-table.row>
                                            <x-table.cell>
                                                <a href="{{ route('transfers.show', $transfer->id) }}" wire:navigate
                                                    class="text-indigo-600 hover:text-indigo-800 font-medium">
                                                    {{ $transfer->reference }}
                                                </a>
                                            </x-table.cell>
                                            <x-table.cell>{{ $transfer->fromStore->name }}</x-table.cell>
                                            <x-table.cell>
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($transfer->status === 'completed') bg-green-100 text-green-800
                                                    @elseif($transfer->status === 'in_transit') bg-blue-100 text-blue-800
                                                    @elseif($transfer->status === 'cancelled') bg-red-100 text-red-800
                                                    @else bg-yellow-100 text-yellow-800 @endif">
                                                    {{ ucfirst($transfer->status) }}
                                                </span>
                                            </x-table.cell>
                                            <x-table.cell>{{ $transfer->created_at->format('d/m/Y') }}</x-table.cell>
                                        </x-table.row>
                                    @empty
                                        <x-table.row>
                                            <x-table.cell colspan="4">
                                                <div class="text-center py-8 text-gray-500">
                                                    Aucun transfert entrant
                                                </div>
                                            </x-table.cell>
                                        </x-table.row>
                                    @endforelse
                                </x-table.body>
                            </x-table.table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Users Tab -->
        <div x-show="activeTab === 'users'" x-cloak>
            @if(isset($users))
                <div class="mb-4 flex justify-end">
                    <x-form.button wire:click="openAssignModal" icon="plus">
                        Assigner un utilisateur
                    </x-form.button>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <x-table.table>
                        <x-table.head>
                            <tr>
                                <x-table.header>Utilisateur</x-table.header>
                                <x-table.header>Rôle</x-table.header>
                                <x-table.header>Par défaut</x-table.header>
                                <x-table.header align="center">Actions</x-table.header>
                            </tr>
                        </x-table.head>
                        <x-table.body>
                            @forelse ($users as $user)
                                <x-table.row>
                                    <x-table.cell>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                        </div>
                                    </x-table.cell>
                                    <x-table.cell>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            {{ ucfirst($user->pivot->role) }}
                                        </span>
                                    </x-table.cell>
                                    <x-table.cell>
                                        @if($user->pivot->is_default)
                                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </x-table.cell>
                                    <x-table.cell align="center">
                                        <button wire:click="removeUser({{ $user->id }})"
                                            class="text-red-600 hover:text-red-800">
                                            Retirer
                                        </button>
                                    </x-table.cell>
                                </x-table.row>
                            @empty
                                <x-table.row>
                                    <x-table.cell colspan="4">
                                        <div class="text-center py-8 text-gray-500">
                                            Aucun utilisateur assigné
                                        </div>
                                    </x-table.cell>
                                </x-table.row>
                            @endforelse
                        </x-table.body>
                    </x-table.table>
                </div>

                <!-- Assign User Modal -->
                @if($showAssignModal)
                    <x-modal :show="$showAssignModal" @close="closeAssignModal" maxWidth="md">
                        <div class="px-6 py-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Assigner un utilisateur</h3>

                            <form wire:submit="assignUser">
                                <div class="space-y-4">
                                    <div>
                                        <label for="user" class="block text-sm font-medium text-gray-700 mb-1">
                                            Utilisateur
                                        </label>
                                        <select id="user" wire:model="selectedUserId"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                            <option value="">Sélectionner un utilisateur</option>
                                            @foreach($availableUsers as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('selectedUserId')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                                            Rôle
                                        </label>
                                        <select id="role" wire:model="selectedRole"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                            <option value="staff">Staff</option>
                                            <option value="cashier">Cashier</option>
                                            <option value="manager">Manager</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                    </div>

                                    <div class="flex items-center">
                                        <input type="checkbox" id="is_default" wire:model="isDefaultStore"
                                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="is_default" class="ml-2 block text-sm text-gray-900">
                                            Magasin par défaut
                                        </label>
                                    </div>
                                </div>

                                <div class="mt-6 flex items-center justify-end space-x-3">
                                    <button type="button" @click="closeAssignModal"
                                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                                        Annuler
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700">
                                        Assigner
                                    </button>
                                </div>
                            </form>
                        </div>
                    </x-modal>
                @endif
            @endif
        </div>

        <!-- Sales Tab -->
        <div x-show="activeTab === 'sales'" x-cloak>
            @if(isset($sales))
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <x-table.table>
                        <x-table.head>
                            <tr>
                                <x-table.header>Référence</x-table.header>
                                <x-table.header>Client</x-table.header>
                                <x-table.header>Montant</x-table.header>
                                <x-table.header>Date</x-table.header>
                            </tr>
                        </x-table.head>
                        <x-table.body>
                            @forelse ($sales as $sale)
                                <x-table.row>
                                    <x-table.cell>{{ $sale->sale_number }}</x-table.cell>
                                    <x-table.cell>{{ $sale->client->name ?? 'Client anonyme' }}</x-table.cell>
                                    <x-table.cell>@currency($sale->total)</x-table.cell>
                                    <x-table.cell>{{ $sale->sale_date->format('d/m/Y') }}</x-table.cell>
                                </x-table.row>
                            @empty
                                <x-table.row>
                                    <x-table.cell colspan="4">
                                        <div class="text-center py-8 text-gray-500">
                                            Aucune vente
                                        </div>
                                    </x-table.cell>
                                </x-table.row>
                            @endforelse
                        </x-table.body>
                    </x-table.table>
                </div>

                @if($sales->hasPages())
                    <div class="mt-6">
                        {{ $sales->links() }}
                    </div>
                @endif
            @endif
        </div>

        <!-- Purchases Tab -->
        <div x-show="activeTab === 'purchases'" x-cloak>
            @if(isset($purchases))
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <x-table.table>
                        <x-table.head>
                            <tr>
                                <x-table.header>Référence</x-table.header>
                                <x-table.header>Fournisseur</x-table.header>
                                <x-table.header>Montant</x-table.header>
                                <x-table.header>Date</x-table.header>
                            </tr>
                        </x-table.head>
                        <x-table.body>
                            @forelse ($purchases as $purchase)
                                <x-table.row>
                                    <x-table.cell>{{ $purchase->reference }}</x-table.cell>
                                    <x-table.cell>{{ $purchase->supplier->name }}</x-table.cell>
                                    <x-table.cell>@currency($purchase->total_amount)
                                    </x-table.cell>
                                    <x-table.cell>{{ $purchase->created_at->format('d/m/Y') }}</x-table.cell>
                                </x-table.row>
                            @empty
                                <x-table.row>
                                    <x-table.cell colspan="4">
                                        <div class="text-center py-8 text-gray-500">
                                            Aucun achat
                                        </div>
                                    </x-table.cell>
                                </x-table.row>
                            @endforelse
                        </x-table.body>
                    </x-table.table>
                </div>

                @if($purchases->hasPages())
                    <div class="mt-6">
                        {{ $purchases->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
