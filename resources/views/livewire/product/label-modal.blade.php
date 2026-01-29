<div>
    @if ($isOpen)
    <div x-data="{ show: true }"
         x-show="show"
         x-init="$watch('show', value => { if(!value) { $wire.close(); } document.body.style.overflow = value ? 'hidden' : '' })"
         x-on:livewire:navigating.window="document.body.style.overflow = ''"
         class="fixed inset-0 z-50 overflow-y-auto"
         @keydown.escape.window="show = false"
         aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true">

        <!-- Backdrop -->
        <div @click="show = false"
             x-show="show"
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
            <div x-show="show"
                 @click.stop
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg pointer-events-auto">

            <!-- Header -->
            <div class="bg-white px-6 pt-5 pb-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900" id="modal-title">
                        Générer des Étiquettes
                    </h3>
                    <button @click="$wire.close()" class="text-gray-400 hover:text-gray-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <p class="text-sm text-gray-600 mb-4">
                    {{ count($productIds) }} produit(s) sélectionné(s)
                </p>
            </div>

            <!-- Content -->
            <div class="bg-white px-6 pb-4">
                <!-- Format Selection -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Format d'étiquette</label>
                    <div class="grid grid-cols-3 gap-3" wire:loading.class="opacity-50" wire:target="generate">
                        <button wire:click="$set('format', 'small')" type="button" wire:loading.attr="disabled" wire:target="generate"
                                class="relative flex flex-col items-center justify-center px-4 py-3 border-2 rounded-lg transition {{ $format === 'small' ? 'border-indigo-600 bg-indigo-50' : 'border-gray-300 hover:border-gray-400' }}">
                            <span class="text-sm font-medium {{ $format === 'small' ? 'text-indigo-700' : 'text-gray-700' }}">Petite</span>
                            <span class="text-xs text-gray-500 mt-1">80×50mm</span>
                            @if($format === 'small')
                                <svg class="absolute top-2 right-2 w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </button>

                        <button wire:click="$set('format', 'medium')" type="button" wire:loading.attr="disabled" wire:target="generate"
                                class="relative flex flex-col items-center justify-center px-4 py-3 border-2 rounded-lg transition {{ $format === 'medium' ? 'border-indigo-600 bg-indigo-50' : 'border-gray-300 hover:border-gray-400' }}">
                            <span class="text-sm font-medium {{ $format === 'medium' ? 'text-indigo-700' : 'text-gray-700' }}">Moyenne</span>
                            <span class="text-xs text-gray-500 mt-1">100×70mm</span>
                            @if($format === 'medium')
                                <svg class="absolute top-2 right-2 w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </button>

                        <button wire:click="$set('format', 'large')" type="button" wire:loading.attr="disabled" wire:target="generate"
                                class="relative flex flex-col items-center justify-center px-4 py-3 border-2 rounded-lg transition {{ $format === 'large' ? 'border-indigo-600 bg-indigo-50' : 'border-gray-300 hover:border-gray-400' }}">
                            <span class="text-sm font-medium {{ $format === 'large' ? 'text-indigo-700' : 'text-gray-700' }}">Grande</span>
                            <span class="text-xs text-gray-500 mt-1">A4</span>
                            @if($format === 'large')
                                <svg class="absolute top-2 right-2 w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </button>
                    </div>
                </div>

                <!-- Columns Selection -->
                <div class="mb-4" wire:loading.class="opacity-50" wire:target="generate">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Colonnes par page</label>
                    <x-form.select
                        wire:model="columns"
                        name="columns"
                        wire:loading.attr="disabled"
                        wire:target="generate"
                        class="mt-1 sm:text-sm">
                        <option value="1">1 colonne</option>
                        <option value="2">2 colonnes</option>
                        <option value="3">3 colonnes</option>
                        <option value="4">4 colonnes</option>
                    </x-form.select>
                </div>

                <!-- Options -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Options d'affichage</label>
                    <div class="space-y-2" wire:loading.class="opacity-50" wire:target="generate">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="showPrice" wire:loading.attr="disabled" wire:target="generate" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Afficher le prix</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="showBarcode" wire:loading.attr="disabled" wire:target="generate" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Afficher le code-barres</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="showQrCode" wire:loading.attr="disabled" wire:target="generate" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Afficher le QR code</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-3 sm:flex sm:flex-row-reverse">
                <button wire:click="generate" type="button" wire:loading.attr="disabled" wire:target="generate"
                        class="inline-flex w-full justify-center items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-75 disabled:cursor-not-allowed">
                    <!-- Spinner pendant le chargement -->
                    <svg wire:loading wire:target="generate" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <!-- Icône document par défaut -->
                    <svg wire:loading.remove wire:target="generate" class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <!-- Texte dynamique -->
                    <span wire:loading wire:target="generate">Génération en cours...</span>
                    <span wire:loading.remove wire:target="generate">Générer</span>
                </button>
                <button @click="$wire.close()" type="button" wire:loading.attr="disabled" wire:target="generate"
                        class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    Annuler
                </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
