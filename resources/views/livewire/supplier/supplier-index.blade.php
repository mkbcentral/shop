<div x-data="{ showModal: false, showDeleteModal: false, supplierToDelete: null, supplierName: '' }"
     @open-supplier-modal.window="showModal = true"
     @open-edit-modal.window="showModal = true"
     @close-supplier-modal.window="showModal = false">
    <!-- Toast Notifications -->
    <x-toast />

    <x-slot name="header">
        <x-breadcrumb :items="[
            ['label' => 'Accueil', 'url' => route('dashboard')],
            ['label' => 'Fournisseurs']
        ]" />
    </x-slot>

    <div class="flex items-center justify-between mt-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestion des Fournisseurs</h1>
            <p class="text-gray-500 mt-1">Gérez vos fournisseurs et leurs informations</p>
        </div>
        <div class="flex items-center space-x-3">
            <button @click="showModal = true; $wire.openCreateModal()"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Nouveau Fournisseur
            </button>
        </div>
    </div>

    <div class="space-y-6 mt-6">
        <!-- Suppliers Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Liste des Fournisseurs ({{ $suppliers->total() }})</h2>
                    <div class="w-72">
                        <x-form.search-input
                            wire:model.live.debounce.300ms="search"
                            wireModel="search"
                            placeholder="Rechercher..."
                        />
                    </div>
                </div>
            </div>

            <x-table.table>
                <x-table.head>
                    <tr>
                        <x-table.header sortable wire:click="sortBy('name')" :sorted="$sortField === 'name'" :direction="$sortDirection">
                            Nom
                        </x-table.header>
                        <x-table.header>Téléphone</x-table.header>
                        <x-table.header>Email</x-table.header>
                        <x-table.header>Adresse</x-table.header>
                        <x-table.header sortable wire:click="sortBy('created_at')" :sorted="$sortField === 'created_at'" :direction="$sortDirection">
                            Date de création
                        </x-table.header>
                        <x-table.header align="right">Actions</x-table.header>
                    </tr>
                </x-table.head>

                <x-table.body>
                    @forelse ($suppliers as $supplier)
                        <x-table.row wire:key="supplier-{{ $supplier->id }}">
                            <x-table.cell>
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                        <span class="text-white font-bold text-sm">{{ strtoupper(substr($supplier->name, 0, 2)) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $supplier->name }}</div>
                                    </div>
                                </div>
                            </x-table.cell>
                            <x-table.cell>
                                <span class="text-sm text-gray-900">{{ $supplier->phone ?? '—' }}</span>
                            </x-table.cell>
                            <x-table.cell>
                                <span class="text-sm text-gray-900">{{ $supplier->email ?? '—' }}</span>
                            </x-table.cell>
                            <x-table.cell>
                                <span class="text-sm text-gray-900">{{ $supplier->address ? Str::limit($supplier->address, 50) : '—' }}</span>
                            </x-table.cell>
                            <x-table.cell>
                                <span class="text-sm text-gray-600">{{ $supplier->created_at->format('d/m/Y') }}</span>
                            </x-table.cell>
                            <x-table.cell align="right">
                                <div class="flex items-center justify-end space-x-3">
                                    <button @click="$wire.openEditModal({{ $supplier->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="openEditModal({{ $supplier->id }})"
                                        class="text-indigo-600 hover:text-indigo-900 transition-colors p-2 rounded-lg hover:bg-indigo-50 disabled:opacity-50"
                                        title="Modifier">
                                        <svg wire:loading.remove wire:target="openEditModal({{ $supplier->id }})" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        <svg wire:loading wire:target="openEditModal({{ $supplier->id }})" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </button>
                                    <button @click="showDeleteModal = true; supplierToDelete = {{ $supplier->id }}; supplierName = '{{ addslashes($supplier->name) }}'"
                                        class="text-red-600 hover:text-red-900 transition-colors p-2 rounded-lg hover:bg-red-50"
                                        title="Supprimer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </x-table.cell>
                        </x-table.row>
                    @empty
                        <x-table.empty-state colspan="6" title="Aucun fournisseur trouvé"
                            description="Commencez par créer votre premier fournisseur.">
                            <x-slot name="action">
                                <x-form.button wire:click="openCreateModal" icon="plus" size="sm">
                                    Nouveau Fournisseur
                                </x-form.button>
                            </x-slot>
                        </x-table.empty-state>
                    @endforelse
                </x-table.body>
            </x-table.table>

            @if ($suppliers->hasPages())
                <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <!-- Results Info -->
                        <div class="text-sm text-gray-700">
                            Affichage de
                            <span class="font-semibold text-indigo-600">{{ $suppliers->firstItem() ?? 0 }}</span>
                            à
                            <span class="font-semibold text-indigo-600">{{ $suppliers->lastItem() ?? 0 }}</span>
                            sur
                            <span class="font-semibold text-indigo-600">{{ $suppliers->total() }}</span>
                            résultats
                        </div>

                        <!-- Pagination Links -->
                        <div>
                            {{ $suppliers->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
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
                 class="relative bg-white rounded-2xl shadow-2xl transform w-full sm:max-w-lg flex flex-col pointer-events-auto"
                 style="max-height: 90vh;">

                <!-- Modal Header -->
                <div class="flex-shrink-0 flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white rounded-t-2xl">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">
                            {{ $isEditMode ? 'Modifier le Fournisseur' : 'Nouveau Fournisseur' }}
                        </h3>
                    </div>
                    <button @click="showModal = false" type="button"
                        class="rounded-lg p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" wire:key="supplier-form-{{ $form->supplierId ?? 'new' }}">
                    <!-- Modal Body -->
                    <div class="flex-1 overflow-y-auto p-6">
                        <div class="space-y-5">
                            <!-- Name Field -->
                            <x-form.form-group label="Nom du fournisseur" for="form.name" required>
                                <x-form.input wire:model.live="form.name" id="form.name" type="text" placeholder="Ex: Fournisseur ABC, Société XYZ..." icon="building" />
                                <x-form.input-error for="form.name" />
                            </x-form.form-group>

                            <!-- Phone & Email Fields -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Phone Field -->
                                <x-form.form-group label="Téléphone" for="form.phone">
                                    <x-form.input wire:model.live="form.phone" id="form.phone" type="text" placeholder="Numéro de téléphone" icon="phone" />
                                    <x-form.input-error for="form.phone" />
                                </x-form.form-group>

                                <!-- Email Field -->
                                <x-form.form-group label="Email" for="form.email">
                                    <x-form.input wire:model.live="form.email" id="form.email" type="email" placeholder="Adresse email" icon="mail" />
                                    <x-form.input-error for="form.email" />
                                </x-form.form-group>
                            </div>

                            <!-- Address Field -->
                            <x-form.form-group label="Adresse" for="form.address">
                                <textarea id="form.address" wire:model.live="form.address" rows="2"
                                    class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none"
                                    placeholder="Adresse complète du fournisseur..."></textarea>
                                <x-form.input-error for="form.address" />
                            </x-form.form-group>

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
                                            Les fournisseurs vous permettent de suivre vos achats et de gérer vos relations commerciales.
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
                            <span wire:loading.remove wire:target="save">
                                {{ $isEditMode ? 'Mettre à jour' : 'Enregistrer' }}
                            </span>
                            <span wire:loading wire:target="save">Enregistrement...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <x-delete-confirmation-modal show="showDeleteModal" itemName="supplierName" itemType="le fournisseur"
        onConfirm="$wire.delete(supplierToDelete); showDeleteModal = false; supplierToDelete = null; supplierName = ''"
        onCancel="showDeleteModal = false; supplierToDelete = null; supplierName = ''" />
</div>
