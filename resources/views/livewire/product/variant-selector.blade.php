<div>
    @if ($isOpen && $product)
        <div x-data="{ show: true }"
             x-show="show"
             x-init="$watch('show', value => { if(!value) { $wire.close(); } document.body.style.overflow = value ? 'hidden' : '' })"
             class="fixed inset-0 z-50 overflow-hidden"
             @keydown.escape.window="show = false"
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
                 class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm">
            </div>

            <!-- Modal Container -->
            <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
                <div x-show="show"
                     @click.stop
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                     class="relative bg-white rounded-2xl shadow-2xl transform transition-all w-full max-w-lg pointer-events-auto">

                    <!-- Header -->
                    <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-t-2xl">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Choisir une variante</h3>
                                <p class="text-sm text-gray-600">{{ $product->name }}</p>
                            </div>
                        </div>
                        <button @click="$wire.close()" type="button"
                            class="text-gray-400 hover:text-gray-600 transition-all duration-200 hover:scale-110">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-6 space-y-6">
                        <!-- Product Info -->
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200">
                            <div class="flex items-center space-x-4">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-20 h-20 object-cover rounded-lg shadow-md">
                                @else
                                    <div class="w-20 h-20 bg-gradient-to-br from-gray-300 to-gray-400 rounded-lg flex items-center justify-center shadow-md">
                                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">{{ $product->name }}</h4>
                                    @if($product->brand)
                                        <p class="text-sm text-gray-600">{{ $product->brand }}</p>
                                    @endif
                                    <p class="text-lg font-bold text-indigo-600 mt-1">@currency($product->price)</p>
                                </div>
                            </div>
                        </div>

                        @if($product->productType && $product->productType->has_variants)
                            <!-- Variant Attributes -->
                            <div class="space-y-4">
                                @foreach($product->productType->variantAttributes as $attribute)
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            {{ $attribute->name }} <span class="text-red-500">*</span>
                                        </label>

                                        @php
                                            $availableOptions = $this->getAvailableOptionsForAttribute($attribute->code);
                                        @endphp

                                        <div class="grid grid-cols-3 gap-2">
                                            @foreach($availableOptions as $option)
                                                <button
                                                    type="button"
                                                    wire:click="$set('selectedOptions.{{ $attribute->code }}', '{{ $option }}')"
                                                    class="px-4 py-3 text-sm font-medium rounded-lg border-2 transition-all duration-200
                                                        {{ ($selectedOptions[$attribute->code] ?? null) === $option
                                                            ? 'bg-indigo-600 text-white border-indigo-600 shadow-lg scale-105'
                                                            : 'bg-white text-gray-700 border-gray-300 hover:border-indigo-400 hover:bg-indigo-50' }}">
                                                    {{ $option }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Matching Variant Info -->
                            @if($matchingVariant)
                                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-300 rounded-xl p-4 shadow-sm animate-fade-in">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center shadow-md">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-bold text-green-800 text-lg mb-2">Variante disponible ✓</h4>
                                            <div class="space-y-2">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-sm font-medium text-green-700">Stock disponible:</span>
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-200 text-green-800">
                                                        {{ $matchingVariant->stock_quantity }} unités
                                                    </span>
                                                </div>
                                                <div class="flex items-center justify-between">
                                                    <span class="text-sm font-medium text-green-700">Prix:</span>
                                                    <span class="text-lg font-bold text-green-800">
                                                        @currency($product->price + $matchingVariant->additional_price)
                                                    </span>
                                                </div>
                                                @if($matchingVariant->additional_price != 0)
                                                    <div class="text-xs text-green-600">
                                                        (Prix de base: @currency($product->price)
                                                        + @currency($matchingVariant->additional_price))
                                                    </div>
                                                @endif
                                                <div class="text-xs text-green-600 mt-2">
                                                    <strong>SKU:</strong> {{ $matchingVariant->sku }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif(!empty(array_filter($selectedOptions)))
                                <div class="bg-yellow-50 border-2 border-yellow-300 rounded-xl p-4">
                                    <div class="flex items-start space-x-3">
                                        <svg class="w-6 h-6 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        <p class="text-sm text-yellow-700">
                                            Cette combinaison n'est pas disponible en stock. Veuillez choisir d'autres options.
                                        </p>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                <p class="text-sm text-blue-700">
                                    Ce produit n'a pas de variantes configurées.
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Footer -->
                    <div class="flex items-center justify-end space-x-3 p-6 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                        <button
                            type="button"
                            @click="$wire.close()"
                            class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border-2 border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm hover:shadow">
                            Annuler
                        </button>
                        <button
                            type="button"
                            wire:click="selectVariant"
                            @disabled(!$matchingVariant)
                            class="inline-flex items-center px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105 disabled:transform-none">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Ajouter au panier
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
