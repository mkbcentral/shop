<!-- Payment Section - Compact -->
<div class="border-t-2 border-gray-200 bg-white px-3 py-2 space-y-2">
    <!-- Discount & Tax en ligne compacte -->
    <div class="flex gap-2">
        <div class="flex-1 flex items-center gap-1">
            <label class="text-xs font-semibold text-gray-600 whitespace-nowrap">Remise</label>
            <input type="number" wire:model.blur="discount" placeholder="0"
                class="w-full px-2 py-1 text-xs border border-gray-200 rounded focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 font-semibold"
                min="0" step="100">
        </div>
        <div class="flex-1 flex items-center gap-1">
            <label class="text-xs font-semibold text-gray-600 whitespace-nowrap">TVA</label>
            <input type="number" wire:model.blur="tax" placeholder="0"
                class="w-full px-2 py-1 text-xs border border-gray-200 rounded focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 font-semibold"
                min="0" step="100">
        </div>
    </div>

    <!-- Totals ultra-compact -->
    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-2 space-y-1">
        <div class="flex justify-between text-xs">
            <span class="text-gray-600">Sous-total</span>
            <span class="font-bold text-gray-900">{{ number_format($subtotal, 0, ',', ' ') }}</span>
        </div>
        @if($discount > 0)
            <div class="flex justify-between text-xs">
                <span class="text-green-600">Remise</span>
                <span class="font-bold text-green-600">-{{ number_format($discount, 0, ',', ' ') }}</span>
            </div>
        @endif
        @if($tax > 0)
            <div class="flex justify-between text-xs">
                <span class="text-gray-600">Taxe</span>
                <span class="font-bold text-gray-900">+{{ number_format($tax, 0, ',', ' ') }}</span>
            </div>
        @endif
        <div class="flex justify-between text-lg font-black pt-1 border-t border-gray-300">
            <span class="text-gray-900">TOTAL</span>
            <span class="text-indigo-600">{{ number_format($total, 0, ',', ' ') }}</span>
        </div>
    </div>

    <!-- Change compact -->
    @if($change > 0)
        <div class="bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg p-2">
            <div class="flex justify-between items-center">
                <span class="text-xs font-bold text-white">💰 Monnaie</span>
                <span class="text-xl font-black text-white">{{ number_format($change, 0, ',', ' ') }}</span>
            </div>

            <!-- Décomposition compacte -->
            @if(count($changeBreakdown) > 0 && count($changeBreakdown) <= 4)
            <div class="mt-1 pt-1 border-t border-white/30">
                <div class="flex flex-wrap gap-1 text-xs">
                    @foreach(array_slice($changeBreakdown, 0, 4) as $item)
                    <span class="bg-white/20 rounded px-1.5 py-0.5 text-white font-medium text-xs">{{ $item['count'] }}×{{ $item['label'] }}</span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    @endif

    <!-- Boutons d'action -->
    <div class="flex gap-2">
        <!-- Bouton Valider seul -->
        <button wire:click="processPaymentOnly" wire:loading.attr="disabled" wire:target="processPaymentOnly" {{ empty($cart) ? 'disabled' : '' }}
            class="flex-1 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-bold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all shadow disabled:opacity-50 disabled:cursor-not-allowed">
            <span class="flex items-center justify-center gap-1">
                <!-- Loading spinner -->
                <svg wire:loading wire:target="processPaymentOnly" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <!-- Normal icon -->
                <svg wire:loading.remove wire:target="processPaymentOnly" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 13l4 4L19 7" />
                </svg>
                <span wire:loading.remove wire:target="processPaymentOnly">VALIDER</span>
                <span wire:loading wire:target="processPaymentOnly">EN COURS...</span>
            </span>
        </button>

        <!-- Bouton Valider & Imprimer -->
        <button wire:click="processPayment" wire:loading.attr="disabled" wire:target="processPayment" {{ empty($cart) ? 'disabled' : '' }}
            class="flex-[2] py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-black rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
            <span class="flex items-center justify-center gap-1">
                <!-- Loading spinner -->
                <svg wire:loading wire:target="processPayment" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <!-- Normal icon -->
                <svg wire:loading.remove wire:target="processPayment" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span wire:loading.remove wire:target="processPayment">IMPRIMER</span>
                <span wire:loading wire:target="processPayment">EN COURS...</span>
            </span>
        </button>
    </div>
</div>
