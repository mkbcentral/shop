@if($showAdjustModal)
<div x-data="{ show: true }"
     x-show="show"
     x-cloak
     x-init="document.body.style.overflow = 'hidden'"
     x-on:keydown.escape.window="$wire.closeAdjustModal()"
     class="fixed inset-0 z-50 overflow-hidden"
     aria-labelledby="modal-title"
     role="dialog"
     aria-modal="true">

    <!-- Backdrop -->
    <div
        x-on:click="$wire.closeAdjustModal()"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm">
    </div>

    <!-- Modal Container -->
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div
            x-on:click.stop
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative bg-white rounded-2xl shadow-2xl transform transition-all w-full sm:max-w-2xl pointer-events-auto">

    <div class="bg-white rounded-xl shadow-xl">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Ajuster le Stock</h3>
            </div>
            <button
                wire:click="closeAdjustModal"
                class="text-gray-400 hover:text-gray-500 transition-colors"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-4" x-data="{
            selectedVariantId: @entangle('form.product_variant_id'),
            newQuantity: @entangle('form.new_quantity').live,
            variants: {{ Js::from($variants->mapWithKeys(fn($v) => [$v->id => $v->stock_quantity])) }},
            get currentStock() {
                return this.selectedVariantId ? (this.variants[this.selectedVariantId] ?? 0) : 0;
            },
            get difference() {
                if (this.newQuantity === null || this.newQuantity === '') return null;
                return parseInt(this.newQuantity) - this.currentStock;
            }
        }">
            <!-- Hidden field to persist movement_type -->
            <input type="hidden" wire:model="form.movement_type" value="adjustment">

            <!-- Product Variant Selection - Full Width -->
            <div>
                <x-form.label for="adjust-product-variant">Produit *</x-form.label>
                <select
                    id="adjust-product-variant"
                    x-model="selectedVariantId"
                    wire:model.live="form.product_variant_id"
                    class="mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition"
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
                    <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-800">
                            <span class="font-semibold">Stock actuel:</span>
                            <span class="text-lg font-bold" x-text="currentStock"></span> unités
                        </p>
                    </div>
                </template>
            </div>

            <!-- Row: New Quantity & Date -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-form.label for="adjust-new-quantity">Nouvelle quantité *</x-form.label>
                    <input
                        type="number"
                        id="adjust-new-quantity"
                        x-model.number="newQuantity"
                        wire:model.live="form.new_quantity"
                        min="0"
                        class="mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition"
                        placeholder="Ex: 100"
                    >
                    <x-form.error name="form.new_quantity" />

                    <template x-if="difference !== null">
                        <p class="mt-2 text-sm">
                            Différence:
                            <span
                                class="font-semibold"
                                :class="difference >= 0 ? 'text-green-600' : 'text-red-600'"
                                x-text="(difference >= 0 ? '+' : '') + difference + ' unités'"
                            ></span>
                        </p>
                    </template>
                </div>

                <div>
                    <x-form.label for="adjust-date">Date</x-form.label>
                    <input
                        type="date"
                        id="adjust-date"
                        wire:model="form.date"
                        class="mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition"
                    >
                    <x-form.error name="form.date" />
                </div>
            </div>

            <!-- Reference - Full Width -->
            <div>
                <x-form.label for="adjust-reference">Référence</x-form.label>
                <input
                    type="text"
                    id="adjust-reference"
                    wire:model="form.reference"
                    class="mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition"
                    placeholder="Ex: INV-2024-001"
                >
                <x-form.error name="form.reference" />
            </div>

            <!-- Reason - Full Width -->
            <div>
                <x-form.label for="adjust-reason">Raison de l'ajustement *</x-form.label>
                <textarea
                    id="adjust-reason"
                    wire:model.live="form.reason"
                    rows="2"
                    class="mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition resize-none"
                    placeholder="Ex: Inventaire physique, produits endommagés, erreur de saisie..."
                ></textarea>
                <x-form.error name="form.reason" />
            </div>

            <!-- Info Message -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Information</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>L'ajustement de stock permet de corriger le stock actuel. Utilisez cette fonction après un inventaire physique ou pour corriger une erreur.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-end space-x-3 px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-xl">
            <x-form.button
                type="button"
                wire:click="closeAdjustModal"
                variant="secondary"
            >
                Annuler
            </x-form.button>
            <x-form.button
                type="button"
                wire:click="adjustStock"
                variant="warning"
                wire:loading.attr="disabled"
            >
                <svg wire:loading.remove wire:target="adjustStock" class="w-5 h-5 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <svg wire:loading wire:target="adjustStock" class="animate-spin w-5 h-5 mr-2 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Ajuster le Stock
            </x-form.button>
        </div>
    </div>
        </div>
    </div>
</div>
@endif
