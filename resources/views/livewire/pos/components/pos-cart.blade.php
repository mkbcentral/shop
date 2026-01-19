<div class="flex flex-col bg-white" x-data="{ showClientModal: false }">
    <!-- Cart Header Compact -->
    <div class="px-3 py-2 border-b border-gray-200 bg-white sticky top-0 z-10 flex-shrink-0">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="p-1 bg-indigo-100 rounded">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-gray-900">Panier</h2>
                    <p class="text-xs text-gray-500">{{ count($cart) }} article(s)</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <!-- Client Button Compact -->
                <button @click="showClientModal = true" type="button"
                    class="px-2 py-1.5 bg-gradient-to-r from-indigo-50 to-purple-50 hover:from-indigo-100 hover:to-purple-100 border border-indigo-200 rounded-lg transition-all flex items-center gap-2 group">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    @if($this->selectedClient)
                        <span class="text-xs font-bold text-indigo-900 max-w-[80px] truncate">{{ $this->selectedClient->name }}</span>
                    @else
                        <span class="text-xs font-medium text-gray-600">Client</span>
                    @endif
                </button>
                <!-- View Receipt Button -->
                <button wire:click="requestReceiptPreview" type="button"
                    class="px-2 py-1.5 bg-gradient-to-r {{ $lastSaleId ? 'from-purple-50 to-pink-50 hover:from-purple-100 hover:to-pink-100 border-purple-200' : 'from-gray-50 to-gray-100 border-gray-200 opacity-50 cursor-not-allowed' }} border rounded-lg transition-all flex items-center gap-1.5 group"
                    title="Voir le reçu"
                    {{ $lastSaleId ? '' : 'disabled' }}>
                    <svg class="w-4 h-4 {{ $lastSaleId ? 'text-purple-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span class="text-xs font-medium {{ $lastSaleId ? 'text-purple-600' : 'text-gray-400' }}">Reçu</span>
                </button>
                @if(!empty($cart))
                    <button wire:click="clearCart"
                        wire:confirm="Êtes-vous sûr de vouloir vider le panier ?"
                        class="px-2 py-1 text-xs text-red-600 hover:bg-red-50 font-semibold rounded transition-colors"
                        title="Vider le panier">
                        🗑️
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Client Selection Modal -->
    <div x-show="showClientModal"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
        @click="showClientModal = false">

        <div @click.stop
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="bg-white rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] flex flex-col overflow-hidden">

            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-white/20 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-white">Sélectionner un client</h3>
                        <p class="text-xs text-indigo-100">Optionnel - Laissez vide pour vente comptant</p>
                    </div>
                </div>
                <button @click="showClientModal = false" class="p-2 hover:bg-white/20 rounded-lg transition-colors flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="p-6 space-y-4 overflow-y-auto flex-1">
                <!-- Client sélectionné actuel -->
                @if($this->selectedClient)
                    <div class="bg-indigo-50 border-2 border-indigo-200 rounded-lg p-3 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-xs text-indigo-600 font-semibold">Client actuel</p>
                                <p class="text-sm font-bold text-indigo-900">{{ $this->selectedClient->name }}</p>
                            </div>
                        </div>
                        <button wire:click="$set('selectedClientId', null)" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                @else
                    <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-3 text-center">
                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <p class="text-sm font-semibold text-gray-600">Aucun client sélectionné</p>
                        <p class="text-xs text-gray-500">Vente comptant (Walk-in)</p>
                    </div>
                @endif

                <!-- Liste des clients -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Choisir un client</label>
                    <select wire:model.live="selectedClientId"
                        class="w-full px-4 py-3 text-sm border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all bg-white">
                        <option value="">👤 Vente comptant (Walk-in)</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 flex gap-3 flex-shrink-0">
                <button @click="showClientModal = false"
                    class="flex-1 py-2.5 border-2 border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-100 transition-colors">
                    Annuler
                </button>
                <button @click="showClientModal = false"
                    class="flex-1 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-colors shadow-lg">
                    Confirmer
                </button>
            </div>
        </div>
    </div>

    <!-- Cart Items -->
    <div class="px-2 py-2 space-y-1.5 flex-1 overflow-y-auto">
        @forelse($cart as $index => $item)
            <!-- Cart Item - Ultra Compact -->
            <div class="bg-white rounded-lg px-2.5 py-2 shadow-sm hover:shadow-md transition-all duration-200 border border-gray-100" wire:key="cart-{{ $item['variant_id'] ?? $index }}"
                x-data="{ showPriceEdit: false }">
                <div class="flex items-center gap-2">
                    <!-- Nom et variantes -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-baseline gap-1.5">
                            <h3 class="font-bold text-gray-900 text-xs truncate">{{ $item['product_name'] }}</h3>
                            @if(!empty($item['variant_size']) || !empty($item['variant_color']))
                                <span class="text-xs text-gray-400 flex-shrink-0">
                                    @if(!empty($item['variant_size'])){{ $item['variant_size'] }}@endif
                                    @if(!empty($item['variant_size']) && !empty($item['variant_color']))/@endif
                                    @if(!empty($item['variant_color'])){{ $item['variant_color'] }}@endif
                                </span>
                            @endif
                        </div>
                        <!-- Prix avec indicateur de négociation -->
                        <div class="flex items-center gap-1">
                            <button @click="showPriceEdit = !showPriceEdit"
                                class="text-xs text-gray-500 hover:text-indigo-600 transition-colors flex items-center gap-0.5"
                                title="Cliquez pour négocier le prix">
                                @if(isset($item['original_price']) && $item['price'] < $item['original_price'])
                                    <span class="line-through text-gray-400">@currency($item['original_price'])</span>
                                    <span class="text-green-600 font-semibold">@currency($item['price'])</span>
                                    <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                @else
                                    <span>@currency($item['price'])</span>
                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                @endif
                            </button>
                        </div>
                    </div>

                    <!-- Quantité -->
                    <div class="flex items-center gap-1">
                        <button wire:click="decrementQuantity('{{ $index }}')"
                            class="w-6 h-6 flex items-center justify-center bg-gray-100 hover:bg-indigo-100 rounded transition-all group"
                            {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                            <svg class="w-3 h-3 text-gray-600 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4" />
                            </svg>
                        </button>
                        <input type="number"
                            wire:change="updateQuantity('{{ $index }}', $event.target.value)"
                            value="{{ $item['quantity'] }}"
                            class="w-10 text-center text-sm font-bold border border-gray-200 rounded py-0.5 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                            min="1" max="{{ $item['stock'] ?? 999 }}">
                        <button wire:click="incrementQuantity('{{ $index }}')"
                            class="w-6 h-6 flex items-center justify-center bg-gray-100 hover:bg-indigo-100 rounded transition-all group"
                            {{ ($item['quantity'] >= ($item['stock'] ?? 999)) ? 'disabled' : '' }}>
                            <svg class="w-3 h-3 text-gray-600 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v12m6-6H6" />
                            </svg>
                        </button>
                    </div>

                    <!-- Total -->
                    <div class="text-right w-20 flex-shrink-0">
                        <div class="text-sm font-black text-indigo-600">
                            {{ number_format($item['price'] * $item['quantity'], 0, ',', ' ') }}
                        </div>
                        <div class="text-xs text-gray-400">{{ current_currency() }}</div>
                    </div>

                    <!-- Supprimer -->
                    <button wire:click="removeFromCart('{{ $index }}')"
                        class="p-1 text-red-500 hover:bg-red-50 rounded transition-colors flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Zone de négociation du prix (collapsible) -->
                <div x-show="showPriceEdit" x-collapse x-cloak class="mt-2 pt-2 border-t border-gray-100">
                    <div class="flex items-center gap-2">
                        <div class="flex-1">
                            <label class="text-xs text-gray-500 mb-1 block">Prix négocié</label>
                            <div class="flex items-center gap-1">
                                <input type="number"
                                    x-ref="priceInput{{ $index }}"
                                    value="{{ $item['price'] }}"
                                    class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-green-500 focus:border-green-500"
                                    min="0"
                                    max="{{ $item['original_price'] ?? $item['price'] }}"
                                    step="0.01"
                                    @keydown.enter="$wire.updatePrice('{{ $index }}', $el.value); showPriceEdit = false">
                                <span class="text-xs text-gray-400">{{ current_currency() }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 pt-4">
                            <button @click="$wire.updatePrice('{{ $index }}', $refs['priceInput{{ $index }}'].value); showPriceEdit = false"
                                class="p-1.5 bg-green-500 hover:bg-green-600 text-white rounded transition-colors"
                                title="Appliquer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </button>
                            @if(isset($item['original_price']) && $item['price'] < $item['original_price'])
                                <button wire:click="resetPrice('{{ $index }}')" @click="showPriceEdit = false"
                                    class="p-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded transition-colors"
                                    title="Rétablir le prix original">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </button>
                            @endif
                            <button @click="showPriceEdit = false"
                                class="p-1.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded transition-colors"
                                title="Annuler">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">
                        Prix original: <span class="font-semibold">@currency($item['original_price'] ?? $item['price'])</span>
                    </p>
                </div>
            </div>
        @empty
            <div class="text-center py-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 mb-3">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <p class="text-sm font-bold text-gray-400">Panier vide</p>
                <p class="text-xs text-gray-400">Ajoutez des produits</p>
            </div>
        @endforelse
    </div>
</div>
