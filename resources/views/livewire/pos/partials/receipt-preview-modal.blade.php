<!-- Receipt Preview Modal -->
<div
    x-data="{ show: @entangle('showReceipt') }"
    x-show="show"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="receipt-modal-title"
    role="dialog"
    aria-modal="true"
>
    <!-- Backdrop -->
    <div
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="show = false"
    ></div>

    <!-- Modal Content -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-hidden"
            @click.stop
        >
            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white/20 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white" id="receipt-modal-title">Prévisualisation du Reçu</h3>
                            <p class="text-sm text-white/80">Vente #{{ $lastInvoice?->invoice_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <button @click="show = false" class="p-2 text-white/80 hover:text-white hover:bg-white/20 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Receipt Content -->
            <div class="p-6 overflow-y-auto max-h-[60vh]">
                @if($lastSale && $lastInvoice)
                    <!-- Receipt Paper Style -->
                    <div class="bg-gray-50 rounded-lg p-4 font-mono text-sm border-2 border-dashed border-gray-300">
                        <!-- Store Header -->
                        <div class="text-center border-b border-gray-300 pb-3 mb-3">
                            <h4 class="text-lg font-bold">{{ config('app.name', 'STOCK Manager') }}</h4>
                            <p class="text-xs text-gray-600">{{ $lastSale->store?->name ?? 'Magasin Principal' }}</p>
                            <p class="text-xs text-gray-600">{{ $lastSale->store?->address ?? '' }}</p>
                        </div>

                        <!-- Invoice Info -->
                        <div class="border-b border-gray-300 pb-3 mb-3">
                            <div class="flex justify-between text-xs">
                                <span>Reçu N°:</span>
                                <span class="font-bold">{{ $lastInvoice->invoice_number }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span>Date:</span>
                                <span>{{ $lastSale->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span>Caissier:</span>
                                <span>{{ $lastSale->user?->name ?? 'N/A' }}</span>
                            </div>
                            @if($lastSale->client)
                                <div class="flex justify-between text-xs">
                                    <span>Client:</span>
                                    <span>{{ $lastSale->client->name }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Items -->
                        <div class="border-b border-gray-300 pb-3 mb-3">
                            <div class="text-xs font-bold mb-2 flex justify-between">
                                <span>Article</span>
                                <span>Montant</span>
                            </div>
                            @foreach($lastSale->items as $item)
                                <div class="text-xs mb-1">
                                    <div class="flex justify-between">
                                        <span class="truncate" style="max-width: 180px;">
                                            {{ $item->productVariant?->product?->name ?? 'Produit' }}
                                        </span>
                                        <span class="font-bold">{{ number_format($item->quantity * $item->unit_price, 0, ',', ' ') }}</span>
                                    </div>
                                    <div class="text-gray-500 pl-2">
                                        {{ $item->quantity }} x @currency($item->unit_price)
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Totals -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-xs">
                                <span>Sous-total:</span>
                                <span>@currency($lastSale->subtotal)</span>
                            </div>
                            @if($lastSale->discount > 0)
                                <div class="flex justify-between text-xs text-green-600">
                                    <span>Remise:</span>
                                    <span>-@currency($lastSale->discount)</span>
                                </div>
                            @endif
                            @if($lastSale->tax > 0)
                                <div class="flex justify-between text-xs">
                                    <span>Taxe:</span>
                                    <span>+@currency($lastSale->tax)</span>
                                </div>
                            @endif
                            <div class="flex justify-between text-base font-black pt-2 border-t border-gray-300">
                                <span>TOTAL:</span>
                                <span>@currency($lastSale->total)</span>
                            </div>
                            <div class="flex justify-between text-xs pt-1">
                                <span>Payé:</span>
                                <span>@currency($lastSale->paid_amount)</span>
                            </div>
                            @if($change > 0)
                                <div class="flex justify-between text-xs text-green-600 font-bold">
                                    <span>Monnaie:</span>
                                    <span>@currency($change)</span>
                                </div>
                            @endif
                        </div>

                        <!-- Footer -->
                        <div class="text-center mt-4 pt-3 border-t border-gray-300">
                            <p class="text-xs text-gray-600">Merci pour votre achat !</p>
                            <p class="text-xs text-gray-500">{{ now()->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-gray-500">Aucun reçu à afficher</p>
                        <p class="text-sm text-gray-400">Validez une vente pour voir le reçu</p>
                    </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex gap-3">
                <button
                    @click="show = false"
                    class="flex-1 px-4 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition"
                >
                    Fermer
                </button>
                @if($lastSale)
                    <button
                        wire:click="printReceipt"
                        class="flex-1 px-4 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-lg hover:from-green-700 hover:to-emerald-700 transition flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Imprimer
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
