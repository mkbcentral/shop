@props(['adjustingVariant' => null, 'newQuantity' => null])

<!-- Adjust Stock Modal -->
<div
    x-data="{ show: @entangle('showAdjustModal') }"
    x-show="show"
    x-cloak
    class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
    @keydown.escape.window="$wire.closeAdjustModal()"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
>
    <!-- Background overlay -->
    <div
        x-on:click="$wire.closeAdjustModal()"
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"
    ></div>

    <!-- Modal Container -->
    <div class="flex min-h-full items-center justify-center p-4">
        <!-- Modal panel -->
        <div
            x-show="show"
            x-on:click.stop
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative bg-white rounded-2xl shadow-2xl transform transition-all w-full sm:max-w-lg"
        >
            <!-- Modal Content -->
            <div class="bg-white px-6 pt-5 pb-6 rounded-2xl">
                <!-- Header -->
                <div class="flex items-start justify-between mb-4 pb-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg leading-6 font-bold text-gray-900">
                                Ajuster le Stock
                            </h3>
                            @if($adjustingVariant)
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $adjustingVariant->product->name }}
                                    @if($adjustingVariant->size || $adjustingVariant->color)
                                        - {{ $adjustingVariant->getVariantName() }}
                                    @endif
                                </p>
                            @endif
                        </div>
                    </div>
                    <button
                        @click="$wire.closeAdjustModal()"
                        type="button"
                        class="text-gray-400 hover:text-gray-600 transition-all duration-200 hover:scale-110"
                    >
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Current Stock Info -->
                @if($adjustingVariant)
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500">Stock Actuel</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $adjustingVariant->stock_quantity }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Seuil d'Alerte</p>
                                <p class="text-lg font-semibold text-gray-700">{{ $adjustingVariant->low_stock_threshold }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Form -->
                <form wire:submit.prevent="adjustStock">
                    <div class="space-y-4">
                        <!-- New Quantity -->
                        <div>
                            <label for="newQuantity" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nouvelle Quantité <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="number"
                                id="newQuantity"
                                wire:model.live="newQuantity"
                                min="0"
                                class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400"
                                placeholder="0"
                                required
                            >
                            @error('newQuantity')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Reason -->
                        <div>
                            <label for="adjustReason" class="block text-sm font-semibold text-gray-700 mb-2">
                                Raison de l'Ajustement <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                id="adjustReason"
                                wire:model="adjustReason"
                                rows="3"
                                class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400 resize-none"
                                placeholder="Ex: Inventaire physique, casse, erreur de saisie..."
                                required
                            ></textarea>
                            @error('adjustReason')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Impact Preview -->
                        @if($adjustingVariant && $newQuantity !== null && $newQuantity != $adjustingVariant->stock_quantity)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                <p class="text-sm text-blue-800">
                                    <span class="font-semibold">Impact:</span>
                                    @if($newQuantity > $adjustingVariant->stock_quantity)
                                        <span class="text-green-600">+{{ $newQuantity - $adjustingVariant->stock_quantity }}</span>
                                    @else
                                        <span class="text-red-600">{{ $newQuantity - $adjustingVariant->stock_quantity }}</span>
                                    @endif
                                    unité(s)
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 flex gap-3">
                        <button
                            type="button"
                            @click="$wire.closeAdjustModal()"
                            class="flex-1 px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                        >
                            Annuler
                        </button>
                        <button
                            type="submit"
                            class="flex-1 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg font-medium hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all"
                        >
                            Confirmer l'Ajustement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
