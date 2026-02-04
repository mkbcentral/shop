<div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Recherche de Produits</h2>
            </div>

            <button
                wire:click="toggleFilters"
                class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                {{ $showFilters ? 'Masquer' : 'Afficher' }} les filtres
            </button>
        </div>

        <!-- Search Bar -->
        <div class="mb-6">
            <div class="relative">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="searchTerm"
                    placeholder="Rechercher par nom, référence, marque, code-barres..."
                    class="w-full pl-12 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-200">
                <div class="absolute left-4 top-1/2 -translate-y-1/2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                @if($searchTerm)
                    <button
                        wire:click="$set('searchTerm', '')"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                @endif
            </div>
        </div>

        <!-- Filters Panel -->
        @if($showFilters)
            <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Product Type Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type de Produit</label>
                        <select wire:model.live="selectedProductType" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
                            <option value="">Tous les types</option>
                            @foreach($productTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->icon }} {{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                        <select wire:model.live="selectedCategory" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Brand Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Marque</label>
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="selectedBrand"
                            placeholder="Ex: Nike, Adidas..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
                    </div>
                </div>

                <!-- Price Range -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prix Minimum (FC)</label>
                        <input
                            type="number"
                            wire:model.live.debounce.300ms="minPrice"
                            placeholder="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prix Maximum (FC)</label>
                        <input
                            type="number"
                            wire:model.live.debounce.300ms="maxPrice"
                            placeholder="999999"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
                    </div>
                </div>

                <!-- Stock Filter -->
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        id="inStockOnly"
                        wire:model.live="inStockOnly"
                        class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <label for="inStockOnly" class="ml-2 text-sm font-medium text-gray-700">
                        Afficher uniquement les produits en stock
                    </label>
                </div>

                <!-- Variant Attribute Filters -->
                @if(!empty($availableFilterOptions))
                    <div class="border-t border-gray-200 pt-4">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Filtrer par attributs :</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($availableFilterOptions as $code => $attribute)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $attribute['name'] }}</label>
                                    <select wire:model.live="variantFilters.{{ $code }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
                                        <option value="">Tous</option>
                                        @foreach($attribute['values'] as $value)
                                            <option value="{{ $value }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Clear Filters Button -->
                <div class="flex justify-end pt-2">
                    <button
                        wire:click="clearFilters"
                        class="inline-flex items-center px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Effacer tous les filtres
                    </button>
                </div>
            </div>
        @endif

        <!-- Results -->
        <div class="space-y-4">
            <!-- Results Count -->
            <div class="flex items-center justify-between text-sm text-gray-600">
                <span>{{ $products->total() }} produit(s) trouvé(s)</span>
                <div class="flex items-center space-x-2">
                    <span>Trier par:</span>
                    <select wire:model.live="orderBy" class="px-3 py-1 border border-gray-300 rounded text-sm">
                        <option value="name">Nom</option>
                        <option value="price">Prix</option>
                        <option value="created_at">Date de création</option>
                    </select>
                    <select wire:model.live="orderDirection" class="px-3 py-1 border border-gray-300 rounded text-sm">
                        <option value="asc">↑ Croissant</option>
                        <option value="desc">↓ Décroissant</option>
                    </select>
                </div>
            </div>

            <!-- Products Grid -->
            @if($products->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($products as $product)
                        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow duration-200">
                            <!-- Product Image -->
                            <div class="aspect-square mb-3 bg-gray-100 rounded-lg overflow-hidden">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-1 truncate">{{ $product->name }}</h3>
                                @if($product->brand)
                                    <p class="text-xs text-gray-500 mb-2">{{ $product->brand }}</p>
                                @endif
                                <p class="text-lg font-bold text-indigo-600 mb-2">@currency($product->price)</p>

                                <!-- Variants Info -->
                                @if($product->variants->count() > 0)
                                    <div class="flex items-center space-x-2 text-xs text-gray-600 mb-2">
                                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded">
                                            {{ $product->variants->count() }} variante(s)
                                        </span>
                                        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded">
                                            Stock: {{ $product->variants->sum('stock_quantity') }}
                                        </span>
                                    </div>
                                @endif

                                <!-- Actions -->
                                <div class="flex space-x-2 mt-3">
                                    <button class="flex-1 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-lg transition-colors duration-200">
                                        Voir
                                    </button>
                                    <button class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun produit trouvé</h3>
                    <p class="text-gray-500 mb-4">Essayez de modifier vos critères de recherche</p>
                    <button
                        wire:click="clearFilters"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors duration-200">
                        Réinitialiser les filtres
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
