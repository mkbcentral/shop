<!-- Keyboard Shortcuts Help -->
<div class="keyboard-hint" x-data="{ show: false }">
    <button @click="show = !show"
        class="bg-gray-800 text-white px-4 py-2 rounded-lg shadow-xl hover:bg-gray-700 transition-all flex items-center gap-2 font-semibold text-sm">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Raccourcis
    </button>

    <div x-show="show" x-transition x-cloak
        class="absolute bottom-full mb-2 bg-white rounded-xl shadow-2xl border-2 border-gray-200 p-4 w-80">
        <h3 class="font-bold text-gray-900 mb-3 text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
            </svg>
            Raccourcis Clavier
        </h3>
        <div class="space-y-2 text-xs">
            <div class="flex justify-between items-center py-1.5 px-2 bg-gray-50 rounded">
                <span class="text-gray-700">Valider la vente</span>
                <kbd class="px-2 py-1 bg-gray-800 text-white rounded font-mono font-bold">F9</kbd>
            </div>
            <div class="flex justify-between items-center py-1.5 px-2 bg-gray-50 rounded">
                <span class="text-gray-700">Vider le panier</span>
                <kbd class="px-2 py-1 bg-gray-800 text-white rounded font-mono font-bold">F4</kbd>
            </div>
            <div class="flex justify-between items-center py-1.5 px-2 bg-gray-50 rounded">
                <span class="text-gray-700">Rechercher produit</span>
                <kbd class="px-2 py-1 bg-gray-800 text-white rounded font-mono font-bold">F2</kbd>
            </div>
            <div class="flex justify-between items-center py-1.5 px-2 bg-gray-50 rounded">
                <span class="text-gray-700">Fermer modal</span>
                <kbd class="px-2 py-1 bg-gray-800 text-white rounded font-mono font-bold">Esc</kbd>
            </div>
            <div class="pt-2 mt-2 border-t border-gray-200">
                <p class="text-gray-600 italic">💡 Scannez directement un code-barres pour ajouter un produit</p>
            </div>
        </div>
    </div>
</div>

<style>
    .keyboard-hint {
        position: fixed;
        bottom: 20px;
        left: 20px;
        z-index: 40;
    }
</style>
