@if($showEditModal)
<div x-show="$wire.showEditModal"
     x-cloak
     x-on:keydown.escape.window="$wire.closeEditModal()"
     x-init="$watch('$wire.showEditModal', value => { document.body.style.overflow = value ? 'hidden' : '' })"
     class="fixed inset-0 z-50 overflow-hidden"
     aria-labelledby="modal-title"
     role="dialog"
     aria-modal="true">

    <!-- Backdrop -->
    <div x-show="$wire.showEditModal"
         x-on:click="$wire.closeEditModal()"
         x-transition.opacity.duration.150ms
         class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm">
    </div>

    <!-- Modal Container -->
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div x-show="$wire.showEditModal"
             x-on:click.stop
             x-transition:enter="ease-out duration-150"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-100"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative bg-white rounded-2xl shadow-2xl w-full sm:max-w-2xl flex flex-col pointer-events-auto"
             style="max-height: 90vh;">

            <div class="bg-white rounded-xl shadow-xl flex flex-col min-h-0 flex-1">
                <!-- Modal Header -->
                <div class="flex-shrink-0 flex items-center justify-between p-6 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Modifier le Mouvement</h3>
                    </div>
                    <button
                        wire:click="closeEditModal"
                        type="button"
                        class="rounded-lg p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                @if($editingMovement)
                <!-- Modal Body -->
                <div class="flex-1 overflow-y-auto p-6 space-y-4">
        <!-- Product Info (Read-only) -->
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm font-medium text-gray-700">Produit</p>
            <p class="text-lg font-semibold text-gray-900">
                {{ $editingMovement->productVariant->product->name ?? 'N/A' }}
            </p>
            <p class="text-sm text-gray-500">
                SKU: {{ $editingMovement->productVariant->sku ?? 'N/A' }}
                @if($editingMovement->productVariant->size || $editingMovement->productVariant->color)
                    - {{ $editingMovement->productVariant->size }} {{ $editingMovement->productVariant->color }}
                @endif
            </p>
            <div class="mt-2">
                @if($editingMovement->type === 'in')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Entrée de stock
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        Sortie de stock
                    </span>
                @endif
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-2">
                    {{ ucfirst(str_replace('_', ' ', $editingMovement->movement_type)) }}
                </span>
            </div>
        </div>

        <!-- Quantity -->
        <x-form.form-group label="Quantité" for="edit_quantity" required>
            <x-form.input
                type="number"
                wire:model="form.quantity"
                id="edit_quantity"
                min="1"
                placeholder="Quantité"
            />
            <x-form.input-error for="form.quantity" />
            <p class="text-xs text-gray-500 mt-1">
                Quantité actuelle: {{ $editingMovement->quantity }}
            </p>
        </x-form.form-group>

        <!-- Reason -->
        <x-form.form-group label="Raison / Notes" for="edit_reason">
            <x-form.textarea
                wire:model="form.reason"
                id="edit_reason"
                rows="2"
                placeholder="Motif de la modification..."
            />
            <x-form.input-error for="form.reason" />
        </x-form.form-group>

        <!-- Unit Price -->
        <x-form.form-group label="Prix unitaire (DH)" for="edit_unit_price">
            <x-form.input
                type="number"
                wire:model="form.unit_price"
                id="edit_unit_price"
                step="0.01"
                min="0"
                placeholder="0.00"
            />
            <x-form.input-error for="form.unit_price" />
        </x-form.form-group>

        <!-- Date -->
        <x-form.form-group label="Date" for="edit_date">
            <x-form.input
                type="date"
                wire:model="form.date"
                id="edit_date"
            />
            <x-form.input-error for="form.date" />
        </x-form.form-group>

        <!-- Warning -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex">
                <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        <strong>Attention:</strong> La modification de ce mouvement ajustera automatiquement le stock du produit.
                    </p>
                </div>
            </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex-shrink-0 flex justify-end space-x-3 p-6 border-t border-gray-200 bg-gray-50/50 rounded-b-2xl">
                    <button type="button" wire:click="closeEditModal"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Annuler
                    </button>
                    <button type="button" wire:click="updateMovement"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors disabled:opacity-50"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="updateMovement">Enregistrer</span>
                        <span wire:loading wire:target="updateMovement">Enregistrement...</span>
                    </button>
                </div>
                @else
                <div class="flex-1 overflow-y-auto p-6">
                    <div class="text-center py-8 text-gray-500">
                        Chargement...
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
