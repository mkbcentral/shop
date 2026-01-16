@props([
    'show' => 'showReceiveModal',
    'itemName' => '',
    'itemType' => 'achat',
    'onConfirm' => '',
    'onCancel' => ''
])

<div
    x-show="{{ $show }}"
    style="display: none;"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm"
    @click.self="{{ $onCancel }}"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
>
    <div
        class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6"
        @click.stop
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    >
        <!-- Icon -->
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <!-- Message -->
        <div class="text-center mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-2">
                Confirmer la réception
            </h3>
            <p class="text-sm text-gray-600 mb-3">
                Voulez-vous vraiment marquer {{ $itemType }}
            </p>
            <p class="text-lg font-bold text-green-600" x-text="{{ $itemName }}"></p>
            <p class="text-xs text-gray-500 mt-2">
                comme réceptionné ? Cette action ajoutera les produits au stock.
            </p>
        </div>

        <!-- Boutons -->
        <div class="flex gap-3 justify-center">
            <button
                type="button"
                @click="{{ $onCancel }}"
                class="px-6 py-2.5 bg-gray-200 text-gray-800 font-medium rounded-lg hover:bg-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2"
            >
                Annuler
            </button>
            <button
                type="button"
                @click="{{ $onConfirm }}"
                class="px-6 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
            >
                Réceptionner
            </button>
        </div>
    </div>
</div>
