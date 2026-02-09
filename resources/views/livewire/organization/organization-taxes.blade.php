<div x-data="{ showModal: false, isEditing: false, showDeleteModal: false, taxToDelete: null, taxName: '' }"
     @open-tax-modal.window="showModal = true"
     @close-tax-modal.window="showModal = false; isEditing = false">
    <x-slot name="header">
        <x-breadcrumb :items="[
            ['label' => 'Accueil', 'url' => route('dashboard')],
            ['label' => 'Organisations', 'url' => route('organizations.index')],
            ['label' => $organization->name, 'url' => route('organizations.show', $organization)],
            ['label' => 'Taxes']
        ]" />
    </x-slot>

    <!-- Toast -->
    <x-toast />

    <!-- Header -->
    <div class="flex items-center justify-between mt-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Gestion des Taxes</h1>
            <p class="text-sm text-gray-600 mt-1">
                Configurez les taxes applicables pour {{ $organization->name }}
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <x-form.button href="{{ route('organizations.show', $organization) }}" wire:navigate variant="secondary" icon="arrow-left">
                Retour
            </x-form.button>
            <x-form.button @click="isEditing = false; showModal = true; $wire.openModal()" icon="plus">
                Ajouter une taxe
            </x-form.button>
        </div>
    </div>

    <!-- Search -->
    <div class="mt-6">
        <div class="relative max-w-md">
            <input type="text"
                   wire:model.live.debounce.300ms="search"
                   placeholder="Rechercher une taxe..."
                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Taxes List -->
    <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($taxes->isEmpty())
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Aucune taxe configurée</h3>
                <p class="mt-2 text-sm text-gray-500">Commencez par ajouter une taxe pour votre organisation.</p>
                <div class="mt-6">
                    <x-form.button @click="isEditing = false; showModal = true; $wire.openModal()" icon="plus">
                        Ajouter une taxe
                    </x-form.button>
                </div>
            </div>
        @else
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Taxe</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Taux</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($taxes as $tax)
                        <tr class="hover:bg-gray-50" wire:key="tax-{{ $tax->id }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm font-medium text-gray-900">{{ $tax->name }}</span>
                                            @if($tax->is_default)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                                    Par défaut
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Code: <span class="font-mono">{{ $tax->code }}</span>
                                            @if($tax->authority)
                                                • {{ $tax->authority }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold text-gray-900">{{ $tax->formatted_rate }}</span>
                                @if($tax->is_compound)
                                    <span class="ml-1 text-xs text-orange-600">(composée)</span>
                                @endif
                                @if($tax->is_included_in_price)
                                    <span class="ml-1 text-xs text-blue-600">(TTC)</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $tax->type === 'percentage' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $tax->type === 'percentage' ? 'Pourcentage' : 'Montant fixe' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button wire:click="toggleActive({{ $tax->id }})"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium cursor-pointer transition-colors
                                        {{ $tax->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                    {{ $tax->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    @if(!$tax->is_default)
                                        <button wire:click="setAsDefault({{ $tax->id }})"
                                                class="text-indigo-600 hover:text-indigo-900"
                                                title="Définir par défaut">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                            </svg>
                                        </button>
                                    @endif
                                    <button wire:click="editTax({{ $tax->id }})"
                                            @click="isEditing = true"
                                            class="text-gray-600 hover:text-gray-900"
                                            title="Modifier">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button @click="showDeleteModal = true; taxToDelete = {{ $tax->id }}; taxName = '{{ addslashes($tax->name) }}'"
                                            class="text-red-600 hover:text-red-900"
                                            title="Supprimer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($taxes->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $taxes->links() }}
                </div>
            @endif
        @endif
    </div>

    <!-- Modal avec x-ui.alpine-modal -->
    <x-ui.alpine-modal
        name="tax"
        max-width="2xl"
        title="Nouvelle Taxe"
        edit-title="Modifier la Taxe"
        icon-bg="from-green-500 to-emerald-600">
        <x-slot:icon>
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
        </x-slot:icon>

        <form wire:submit="saveTax" wire:key="tax-form-{{ $editingTaxId ?? 'new' }}">
            <x-ui.alpine-modal-body>
                <div class="space-y-4">
                    <!-- Nom et Code -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-form.label for="name" :required="true">Nom</x-form.label>
                            <x-form.input type="text" wire:model="name" name="name" placeholder="TVA" />
                            <x-form.input-error :messages="$errors->get('name')" />
                        </div>
                        <div>
                            <x-form.label for="code" :required="true">Code</x-form.label>
                            <x-form.input type="text" wire:model="code" name="code" placeholder="TVA" class="font-mono uppercase" />
                            <x-form.input-error :messages="$errors->get('code')" />
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <x-form.label for="description">Description</x-form.label>
                        <x-form.textarea wire:model="description" name="description" rows="2" placeholder="Description de la taxe..." />
                    </div>

                    <!-- Type et Taux -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-form.label for="type" :required="true">Type</x-form.label>
                            <x-form.select wire:model.live="type" name="type">
                                <option value="percentage">Pourcentage (%)</option>
                                <option value="fixed">Montant fixe</option>
                            </x-form.select>
                        </div>
                        <div>
                            @if($type === 'percentage')
                                <x-form.label for="rate" :required="true">Taux (%)</x-form.label>
                                <x-form.input type="number" wire:model="rate" name="rate" step="0.01" min="0" max="100" placeholder="16" />
                                <x-form.input-error :messages="$errors->get('rate')" />
                            @else
                                <x-form.label for="fixedAmount" :required="true">Montant fixe</x-form.label>
                                <x-form.input type="number" wire:model="fixedAmount" name="fixedAmount" step="0.01" min="0" placeholder="1000" />
                                <x-form.input-error :messages="$errors->get('fixedAmount')" />
                            @endif
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-form.label for="priority">Priorité</x-form.label>
                            <x-form.input type="number" wire:model="priority" name="priority" min="0" placeholder="0" />
                            <p class="text-xs text-gray-500 mt-1">Ordre d'application (0 = premier)</p>
                        </div>
                        <div>
                            <x-form.label for="authority">Autorité fiscale</x-form.label>
                            <x-form.input type="text" wire:model="authority" name="authority" placeholder="DGI, Mairie..." />
                        </div>
                    </div>

                    <!-- Checkboxes -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <x-form.checkbox wire:model="isCompound" name="isCompound" size="sm" />
                                <div>
                                    <span class="text-sm text-gray-700">Taxe composée</span>
                                    <p class="text-xs text-gray-500">Calculée sur le montant + taxes précédentes</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3">
                                <x-form.checkbox wire:model="isIncludedInPrice" name="isIncludedInPrice" size="sm" />
                                <span class="text-sm text-gray-700">Incluse dans le prix (TTC)</span>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <x-form.checkbox wire:model="applyToAllProducts" name="applyToAllProducts" size="sm" />
                                <span class="text-sm text-gray-700">Appliquer à tous les produits</span>
                            </div>

                            <div class="flex items-center space-x-3">
                                <x-form.checkbox wire:model="isDefault" name="isDefault" size="sm" />
                                <span class="text-sm text-gray-700">Taxe par défaut</span>
                            </div>

                            <div class="flex items-center space-x-3">
                                <x-form.checkbox wire:model="isActive" name="isActive" size="sm" />
                                <span class="text-sm text-gray-700">Active</span>
                            </div>
                        </div>
                    </div>

                    <!-- Période de validité -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                        <div>
                            <x-form.label for="validFrom">Valide à partir du</x-form.label>
                            <x-form.input type="date" wire:model="validFrom" name="validFrom" />
                        </div>
                        <div>
                            <x-form.label for="validUntil">Valide jusqu'au</x-form.label>
                            <x-form.input type="date" wire:model="validUntil" name="validUntil" />
                        </div>
                    </div>

                    <!-- Numéro fiscal -->
                    <div>
                        <x-form.label for="taxNumber">Numéro d'identification fiscale</x-form.label>
                        <x-form.input type="text" wire:model="taxNumber" name="taxNumber" placeholder="NIF-XXXX-XXXX" class="font-mono" />
                    </div>
                </div>
            </x-ui.alpine-modal-body>

            <x-ui.alpine-modal-footer
                submit-text="Créer la taxe"
                edit-submit-text="Enregistrer les modifications"
                target="saveTax"
            />
        </form>
    </x-ui.alpine-modal>

    <!-- Delete Confirmation Modal -->
    <x-delete-confirmation-modal
        :show="'showDeleteModal'"
        :item-name="'taxName'"
        item-type="cette taxe"
        on-cancel="showDeleteModal = false; taxToDelete = null"
        on-confirm="$wire.deleteTax(taxToDelete); showDeleteModal = false; taxToDelete = null"
    />
</div>
