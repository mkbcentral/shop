@props(['products', 'densityMode', 'selectAll'])

<x-table.table>
    <x-table.head>
        <tr class="{{ $densityMode === 'compact' ? 'text-xs' : ($densityMode === 'spacious' ? 'text-base' : 'text-sm') }}">
            <x-table.header class="w-8">
                <x-form.checkbox wire:model.live="selectAll" />
            </x-table.header>
            <x-table.header sortable :sortKey="'name'">Produit</x-table.header>
            <x-table.header sortable :sortKey="'reference'">Référence</x-table.header>
            <x-table.header sortable :sortKey="'price'">Prix</x-table.header>
            <x-table.header sortable :sortKey="'stock'">Stock</x-table.header>
            <x-table.header sortable :sortKey="'status'">Statut</x-table.header>
            <x-table.header align="right">Actions</x-table.header>
        </tr>
    </x-table.head>
    <x-table.body>
        @forelse($products as $product)
            @php
                // Use the model's getTotalStockAttribute which handles store-specific stock
                $totalStock = $product->total_stock;
                $stockClass = $totalStock == 0 ? 'bg-red-100 text-red-800 border-red-300' :
                             ($totalStock <= $product->stock_alert_threshold ? 'bg-orange-100 text-orange-800 border-orange-300' :
                             'bg-green-100 text-green-800 border-green-300');
                $paddingClass = $densityMode === 'compact' ? 'py-1.5' : ($densityMode === 'spacious' ? 'py-8' : 'py-4');
                $imageSize = $densityMode === 'compact' ? 'w-8 h-8' : ($densityMode === 'spacious' ? 'w-16 h-16' : 'w-10 h-10');
                $textSize = $densityMode === 'compact' ? 'text-xs' : ($densityMode === 'spacious' ? 'text-base' : 'text-sm');
                $iconSize = $densityMode === 'compact' ? 'w-4 h-4' : ($densityMode === 'spacious' ? 'w-8 h-8' : 'w-6 h-6');
            @endphp
            <x-table.row class="{{ $paddingClass }}">
                <x-table.cell>
                    <x-form.checkbox value="{{ $product->id }}" wire:model.live="selected" size="sm" />
                </x-table.cell>
                <x-table.cell>
                    <div class="flex items-center">
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                class="{{ $imageSize }} rounded-lg object-cover mr-3">
                        @else
                            <div
                                class="{{ $imageSize }} bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                                <svg class="{{ $iconSize }} text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                        @endif
                        <div>
                            <div class="{{ $textSize }} font-medium text-gray-900">{{ $product->name }}</div>
                            <div class="mt-1 flex items-center gap-2">
                                @if($product->productType)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $product->productType->icon }} {{ $product->productType->name }}
                                    </span>
                                @endif
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ $product->category->name ?? 'N/A' }}
                                </span>
                            </div>
                            @if ($product->description)
                                <div class="text-xs text-gray-500 truncate max-w-xs mt-1">
                                    {{ Str::limit($product->description, 50) }}
                                </div>
                            @endif
                        </div>
                    </div>
                </x-table.cell>
                <x-table.cell>
                    <span class="{{ $textSize }} text-gray-900">{{ $product->reference }}</span>
                </x-table.cell>
                <x-table.cell>
                    <div class="{{ $textSize }} font-semibold text-gray-900">
                        {{ format_currency($product->price) }}
                    </div>
                    @if ($product->cost_price)
                        <div class="{{ $densityMode === 'compact' ? 'text-[10px]' : 'text-xs' }} text-gray-500">
                            Coût: {{ format_currency($product->cost_price) }}
                        </div>
                    @endif
                </x-table.cell>
                <x-table.cell>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border {{ $stockClass }}">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        {{ $totalStock }} unités
                    </span>
                    @if($totalStock == 0)
                        <span class="ml-2 text-xs text-red-600 font-medium">Rupture</span>
                    @elseif($totalStock <= $product->stock_alert_threshold)
                        <span class="ml-2 text-xs text-orange-600 font-medium">Alerte</span>
                    @endif
                </x-table.cell>
                <x-table.cell>
                    <x-table.badge :color="$product->status === 'active' ? 'green' : 'gray'" dot>
                        {{ $product->status === 'active' ? 'Actif' : 'Inactif' }}
                    </x-table.badge>
                </x-table.cell>
                <x-table.cell align="right">
                    <x-table.actions>
                        @permission('products.edit')
                        <button type="button"
                            wire:click="$dispatch('editProduct', { productId: {{ $product->id }}, product: {{ json_encode($product) }} })"
                            class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        @endpermission
                        <button type="button"
                            wire:click="$dispatch('openLabelModal', [[{{ $product->id }}]])"
                            class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium text-green-600 bg-green-50 hover:bg-green-100 rounded-lg transition-colors"
                            title="Générer étiquette">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </button>
                        @permission('products.delete')
                        <x-table.action-button type="button"
                            @click="showDeleteModal = true; productToDelete = {{ $product->id }}; productName = '{{ addslashes($product->name) }}'"
                            color="red">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </x-table.action-button>
                        @endpermission
                    </x-table.actions>
                </x-table.cell>
            </x-table.row>
        @empty
            <x-table.empty-state colspan="7" title="Aucun produit"
                description="Commencez par créer un nouveau produit.">
                <x-slot name="icon">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </x-slot>
            </x-table.empty-state>
        @endforelse
    </x-table.body>
</x-table.table>
