<div class="min-h-screen flex flex-col bg-gradient-to-br from-gray-50 to-gray-100"
    x-data="cashRegister"
    wire:ignore.self>

    <!-- Top Bar -->
    @include('livewire.pos.partials.top-bar')

    <!-- Main Content -->
    <div class="flex-1 flex overflow-hidden">
        <!-- Products Section (Left) -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Search & Filters -->
            <div class="bg-white/80 backdrop-blur-sm border-b shadow-sm px-4 py-3 flex-shrink-0">
                <div class="grid grid-cols-12 gap-3">
                    <div class="col-span-7">
                        <div class="relative group">
                            <input wire:model.live.debounce.300ms="search" type="text"
                                placeholder="Rechercher un produit..."
                                class="w-full pl-10 pr-4 py-2.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200 shadow-sm text-sm">
                            <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <div wire:loading wire:target="search" class="absolute right-3 top-3">
                                <svg class="animate-spin h-4 w-4 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-5">
                        <select wire:model.live="categoryFilter"
                            class="w-full px-3 py-2.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200 shadow-sm bg-white appearance-none cursor-pointer text-sm"
                            style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2220%22%20height%3D%2220%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cpath%20d%3D%22M5%207l5%205%205-5%22%20stroke%3D%22%23666%22%20stroke-width%3D%222%22%20fill%3D%22none%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 0.75rem center; padding-right: 2.5rem;">
                            <option value="">🏷️ Toutes les catégories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Stats Panel -->
            @include('livewire.pos.partials.stats-panel')

            <!-- Products Grid -->
            <div class="flex-1 overflow-y-auto p-4" style="height: calc(100vh - 150px);">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-3 gap-4">
                    @forelse($products as $product)
                        @foreach($product->variants as $variant)
                            <div wire:key="variant-card-{{ $variant->id }}">
                                @include('livewire.pos.partials.product-card', ['product' => $product, 'variant' => $variant])
                            </div>
                        @endforeach
                    @empty
                        <div class="col-span-full text-center py-16">
                            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 mb-4">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <p class="text-lg font-semibold text-gray-700 mb-1">Aucun produit trouvé</p>
                            <p class="text-sm text-gray-500">Essayez de modifier vos critères de recherche</p>
                        </div>
                    @endforelse
                </div>

                @if($products->hasPages())
                    <div class="mt-6">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Cart Section (Right) -->
        <div class="w-[500px] bg-gradient-to-b from-white to-gray-50 border-l-2 border-gray-200 shadow-2xl overflow-y-auto custom-scrollbar" style="height: calc(100vh - 64px);">
            <!-- Cart Header Compact -->
            <div class="px-3 py-2 border-b border-gray-200 bg-white sticky top-0 z-10">
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
                        <button wire:click="previewReceipt" type="button"
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
                                class="px-2 py-1 text-xs text-red-600 hover:bg-red-50 font-semibold rounded transition-colors"
                                title="Vider le panier">
                                🗑️
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Cart Items -->
            <div class="px-2 py-2 space-y-1.5">
                @forelse($cart as $key => $item)
                    @include('livewire.pos.partials.cart-item', ['key' => $key, 'item' => $item])
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

            <!-- Payment Section -->
            @include('livewire.pos.partials.payment-section')
        </div>
    </div>

    <!-- Receipt Modal -->
    @if($showReceipt && $lastSale && $lastInvoice)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-md z-50 flex items-center justify-center p-4 animate-fade-in">
            <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full transform animate-scale-in" @click.stop>
                <div class="p-8">
                    <!-- Success Icon -->
                    <div class="text-center mb-6">
                        <div class="mx-auto w-20 h-20 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full flex items-center justify-center mb-4 shadow-xl animate-bounce-once">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-3xl font-black text-gray-900 mb-2">Paiement réussi ! 🎉</h3>
                        <p class="text-gray-600 font-medium">Vente enregistrée avec succès</p>
                    </div>

                    <!-- Receipt Preview -->
                    <div id="receipt-content" class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 mb-6 border-2 border-dashed border-gray-300 shadow-inner">
                        <div class="text-center mb-5 pb-4 border-b-2 border-gray-300">
                            <div class="inline-block px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg mb-2">
                                <h4 class="font-black text-lg tracking-wide">🧾 REÇU DE CAISSE</h4>
                            </div>
                            <p class="text-sm font-bold text-gray-700 mt-2">{{ $lastInvoice->invoice_number }}</p>
                            <p class="text-xs text-gray-500 font-medium">{{ $lastSale->sale_date->format('d/m/Y H:i:s') }}</p>
                        </div>

                        <div class="border-t border-b border-gray-300 py-4 mb-4 space-y-2.5">
                            @foreach($lastSale->items as $item)
                                <div class="flex justify-between text-sm">
                                    <span class="font-medium text-gray-700">
                                        {{ $item->productVariant->product->name }}
                                        <span class="text-indigo-600 font-bold">×{{ $item->quantity }}</span>
                                    </span>
                                    <span class="font-bold text-gray-900">{{ number_format($item->total_price, 0, ',', ' ') }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600 font-medium">Sous-total</span>
                                <span class="font-bold">@currency($lastSale->subtotal)</span>
                            </div>
                            @if($lastSale->discount > 0)
                                <div class="flex justify-between text-green-600">
                                    <span class="font-medium">Remise</span>
                                    <span class="font-bold">-@currency($lastSale->discount)</span>
                                </div>
                            @endif
                            <div class="flex justify-between font-black text-xl pt-3 border-t-2 border-gray-400">
                                <span class="text-gray-900">TOTAL</span>
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">
                                    {{ number_format($lastSale->total, 0, ',', ' ') }}
                                </span>
                            </div>
                            <div class="flex justify-between bg-indigo-50 -mx-2 px-2 py-2 rounded-lg">
                                <span class="text-gray-700 font-semibold">💰 Payé</span>
                                <span class="font-bold text-indigo-600">@currency($lastSale->paid_amount)</span>
                            </div>
                            @if($change > 0)
                                <div class="flex justify-between bg-green-50 -mx-2 px-2 py-2 rounded-lg">
                                    <span class="text-green-700 font-semibold">💵 Monnaie</span>
                                    <span class="font-bold text-green-600">@currency($change)</span>
                                </div>
                            @endif
                        </div>

                        <div class="text-center mt-5 pt-4 border-t-2 border-gray-300">
                            <p class="text-sm font-bold text-gray-700">✨ Merci de votre visite ! ✨</p>
                            <p class="text-xs text-gray-500 mt-1">À bientôt !</p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3">
                        <button wire:click="closeReceipt"
                            class="flex-1 px-5 py-4 bg-gradient-to-br from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 text-gray-700 font-bold rounded-xl transition-all shadow-md hover:shadow-lg transform hover:scale-105">
                            🔄 Nouvelle vente
                        </button>
                        <button wire:click="printReceipt"
                            class="flex-1 px-5 py-4 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 hover:from-indigo-700 hover:via-purple-700 hover:to-pink-700 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                            <span class="flex items-center justify-center gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                🖨️ Imprimer Ticket
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Flash Messages - Utilise Alpine pour éviter les re-renders -->
    <div
        x-data="{
            successMessage: '',
            errorMessage: '',
            showSuccess: false,
            showError: false
        }"
        x-on:cart-success.window="successMessage = $event.detail.message; showSuccess = true; setTimeout(() => { showSuccess = false }, 3000)"
        x-on:cart-error.window="errorMessage = $event.detail.message; showError = true; setTimeout(() => { showError = false }, 5000)"
        class="fixed bottom-6 right-6 z-50 space-y-2"
    >
        <!-- Success Message -->
        <div x-show="showSuccess" x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-x-full"
            x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-x-0"
            x-transition:leave-end="opacity-0 transform translate-x-full"
            class="bg-gradient-to-r from-green-500 to-emerald-500 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-bold" x-text="successMessage"></span>
        </div>

        <!-- Error Message -->
        <div x-show="showError" x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-x-full"
            x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-x-0"
            x-transition:leave-end="opacity-0 transform translate-x-full"
            class="bg-gradient-to-r from-red-500 to-pink-500 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-bold" x-text="errorMessage"></span>
        </div>
    </div>

    <!-- Keyboard Shortcuts -->
    @include('livewire.pos.partials.keyboard-shortcuts')

    <!-- Client Dialog -->
    @include('livewire.pos.partials.client-dialog')

    <!-- Receipt Preview Modal -->
    @include('livewire.pos.partials.receipt-preview-modal', [
        'lastSale' => $this->lastSale,
        'lastInvoice' => $this->lastInvoice,
    ])

    <!-- Styles -->
    @include('livewire.pos.partials.styles')

    <!-- Alpine Init -->
    @include('livewire.pos.partials.alpine-init')

    <!-- Printer Scripts -->
    @include('livewire.pos.partials.printer-scripts')
</div>
