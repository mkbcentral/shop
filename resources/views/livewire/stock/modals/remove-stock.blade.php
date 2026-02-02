@if($showRemoveModal)
<div x-show="$wire.showRemoveModal"
     x-cloak
     x-on:keydown.escape.window="$wire.closeRemoveModal()"
     x-init="$watch('$wire.showRemoveModal', value => { document.body.style.overflow = value ? 'hidden' : '' })"
     class="fixed inset-0 z-50 overflow-hidden"
     aria-labelledby="modal-title"
     role="dialog"
     aria-modal="true">

    <!-- Backdrop -->
    <div x-show="$wire.showRemoveModal"
         x-on:click="$wire.closeRemoveModal()"
         x-transition.opacity.duration.150ms
         class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm">
    </div>

    <!-- Modal Container -->
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div x-show="$wire.showRemoveModal"
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
                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Retirer du Stock</h3>
                    </div>
                    <button
                        wire:click="closeRemoveModal"
                        type="button"
                        class="text-gray-400 hover:text-gray-500 transition-colors"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <form wire:submit.prevent="removeStock" class="flex flex-col flex-1 min-h-0">
                    <div class="p-6 space-y-4 overflow-y-auto flex-1" x-data="{
                        selectedVariantId: @entangle('form.product_variant_id'),
                        variants: {{ Js::from($variants->mapWithKeys(fn($v) => [$v->id => $v->stock_quantity])) }},
                        get currentStock() {
                            return this.selectedVariantId ? (this.variants[this.selectedVariantId] ?? 0) : 0;
                        }
                    }">
                        <!-- Product Variant Selection - Full Width -->
                        <div>
                            <x-form.label for="remove-product-variant">Produit *</x-form.label>
                            <select
                                id="remove-product-variant"
                                x-model="selectedVariantId"
                                wire:model.live="form.product_variant_id"
                                class="mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"
                            >
                                <option value="">Sélectionnez un produit</option>
                                @foreach($variants as $variant)
                                    <option value="{{ $variant->id }}">
                                        {{ $variant->product->name }} - {{ $variant->sku }}
                                        @if($variant->size || $variant->color)
                                            ({{ $variant->size }} {{ $variant->color }})
                                        @endif
                                        - Stock actuel: {{ $variant->stock_quantity }}
                                    </option>
                                @endforeach
                            </select>
                            <x-form.error name="form.product_variant_id" />

                            <template x-if="selectedVariantId">
                                <p class="mt-2 text-sm text-gray-600">
                                    Stock disponible:
                                    <span class="font-semibold text-indigo-600" x-text="currentStock + ' unités'"></span>
                                </p>
                            </template>
                        </div>

                        <!-- Row: Quantity & Movement Type -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-form.label for="remove-quantity">Quantité *</x-form.label>
                                <input
                                    type="number"
                                    id="remove-quantity"
                                    wire:model="form.quantity"
                                    min="1"
                                    :max="currentStock || 9999"
                                    class="mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"
                                    placeholder="Ex: 10"
                                >
                                <x-form.error name="form.quantity" />
                            </div>

                            <div>
                                <x-form.label for="remove-movement-type">Type de mouvement</x-form.label>
                                <select
                                    id="remove-movement-type"
                                    wire:model.live="form.movement_type"
                                    class="mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"
                                >
                                    <option value="sale">Vente</option>
                                    <option value="adjustment">Ajustement</option>
                                    <option value="transfer">Transfert</option>
                                    <option value="return">Retour fournisseur</option>
                                </select>
                                <x-form.error name="form.movement_type" />
                            </div>
                        </div>

                        <!-- Row: Reference & Date -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-form.label for="remove-reference">Référence</x-form.label>
                                <input
                                    type="text"
                                    id="remove-reference"
                                    wire:model="form.reference"
                                    class="mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"
                                    placeholder="Ex: VENTE-2024-001"
                                >
                                <x-form.error name="form.reference" />
                            </div>

                            <div>
                                <x-form.label for="remove-date">Date</x-form.label>
                                <input
                                    type="date"
                                    id="remove-date"
                                    wire:model="form.date"
                                    class="mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"
                                >
                                <x-form.error name="form.date" />
                            </div>
                        </div>

                        <!-- Reason - Full Width -->
                        <div>
                            <x-form.label for="remove-reason">Raison / Notes *</x-form.label>
                            <textarea
                                id="remove-reason"
                                wire:model="form.reason"
                                rows="2"
                                class="mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition resize-none"
                                placeholder="Expliquez la raison du retrait de stock..."
                            ></textarea>
                            <x-form.error name="form.reason" />
                        </div>

                        <!-- Warning Message -->
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Attention</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <p>Cette action va réduire le stock du produit. Assurez-vous que les informations sont correctes.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex-shrink-0 flex items-center justify-end space-x-3 px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-xl">
                        <x-form.button
                            type="button"
                            wire:click="closeRemoveModal"
                            variant="secondary"
                        >
                            Annuler
                        </x-form.button>
                        <x-form.button
                            type="submit"
                            variant="danger"
                            wire:loading.attr="disabled"
                        >
                            <svg wire:loading.remove class="w-5 h-5 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <svg wire:loading class="animate-spin w-5 h-5 mr-2 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Retirer le Stock
                        </x-form.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
