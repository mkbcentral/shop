{{-- Transaction List Item Partial --}}
<div class="px-5 py-4 hover:bg-gradient-to-r hover:from-indigo-50/80 hover:to-purple-50/80 transition-all duration-300 group relative">
    <!-- Hover indicator -->
    <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-indigo-500 to-purple-600 rounded-r opacity-0 group-hover:opacity-100 transition-opacity"></div>

    <div class="flex items-center gap-4">
        <!-- Icon badge -->
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center flex-shrink-0 shadow-lg group-hover:shadow-xl group-hover:scale-105 transition-all duration-300">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>

        <!-- Details -->
        <div class="flex-1 min-w-0">
            <div class="flex justify-between items-start gap-3">
                <div>
                    <p class="font-bold text-gray-900 text-base group-hover:text-indigo-600 transition-colors">
                        {{ $transaction['invoice_number'] ?? 'N/A' }}
                    </p>
                    <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                        <span class="text-sm text-gray-500 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $transaction['time'] ?? '' }}
                        </span>
                        @if(isset($transaction['client']) && $transaction['client'] !== 'Comptant')
                            <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded-full text-xs font-semibold">
                                {{ $transaction['client'] }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="font-black text-xl bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                        {{ number_format($transaction['total'] ?? 0, 0, ',', ' ') }}
                        <span class="text-sm">{{ current_currency() }}</span>
                    </p>
                    <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-lg bg-gray-100 text-gray-600 mt-1.5 font-medium">
                        @if(($transaction['payment_method'] ?? 'cash') === 'cash')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        @else
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        @endif
                        {{ ucfirst($transaction['payment_method'] ?? 'cash') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Actions buttons -->
        <div class="opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0 flex-shrink-0 flex items-center gap-2">
            <!-- Voir (sans impression) -->
            <button wire:click="viewTransaction({{ $transaction['id'] }})"
                class="p-3 bg-white border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50 rounded-xl text-indigo-600 shadow-sm hover:shadow-md transition-all duration-200"
                title="Voir les détails">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </button>

            <!-- Imprimer -->
            <button wire:click="reprintTransaction({{ $transaction['id'] }})"
                class="p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl text-white shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200"
                title="Réimprimer la facture">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
            </button>
        </div>
    </div>
</div>
