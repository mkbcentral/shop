@props([
    'product',
    'selected' => false,
])

@php
    // Use the model's getTotalStockAttribute which handles store-specific stock
    $totalStock = $product->total_stock;
    $stockClass = $totalStock == 0 ? 'bg-red-100 text-red-800 border-red-300' :
                 ($totalStock <= $product->stock_alert_threshold ? 'bg-orange-100 text-orange-800 border-orange-300' :
                 'bg-green-100 text-green-800 border-green-300');
@endphp

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-200 group relative">
    <!-- Checkbox -->
    <div class="absolute top-3 left-3 z-10">
        <x-form.checkbox value="{{ $product->id }}" wire:model.live="selected" size="md" />
    </div>

    <!-- Stock Badge -->
    @if($totalStock == 0)
        <div class="absolute top-3 right-3 z-10">
            <span class="px-2 py-1 bg-red-600 text-white text-xs font-bold rounded-full shadow-lg">
                RUPTURE
            </span>
        </div>
    @elseif($totalStock <= $product->stock_alert_threshold)
        <div class="absolute top-3 right-3 z-10">
            <span class="px-2 py-1 bg-orange-500 text-white text-xs font-bold rounded-full shadow-lg">
                ALERTE
            </span>
        </div>
    @endif

    <!-- Image -->
    <div class="relative h-48 bg-gray-100 overflow-hidden">
        @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}"
                 alt="{{ $product->name }}"
                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
        @else
            <div class="w-full h-full flex items-center justify-center">
                <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        @endif
    </div>

    <!-- Content -->
    <div class="p-4 space-y-3">
        <div>
            <h3 class="font-semibold text-gray-900 truncate group-hover:text-indigo-600 transition-colors">
                {{ $product->name }}
            </h3>
            <p class="text-xs text-gray-500 mt-1">{{ $product->reference }}</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            @if($product->productType)
                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">
                    {{ $product->productType->icon }} {{ $product->productType->name }}
                </span>
            @endif
            <span class="text-xs text-gray-600 bg-purple-100 px-2 py-1 rounded">
                {{ $product->category->name ?? 'N/A' }}
            </span>
            <span class="text-xs px-2 py-1 rounded {{ $product->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                {{ $product->status === 'active' ? 'Actif' : 'Inactif' }}
            </span>
        </div>

        <div class="flex items-center justify-between pt-2 border-t border-gray-100">
            <div>
                <p class="text-lg font-bold text-gray-900">{{ format_currency($product->price) }}</p>
                @if($product->cost_price)
                    <p class="text-xs text-gray-500">Coût: {{ format_currency($product->cost_price) }}</p>
                @endif
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-medium border {{ $stockClass }}">
                {{ $totalStock }}
            </span>
        </div>

        <!-- Actions -->
        <div class="flex space-x-2 pt-2">
            <button type="button"
                wire:click="$dispatch('editProduct', { productId: {{ $product->id }}, product: {{ json_encode($product) }} })"
                class="flex-1 px-3 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors">
                Modifier
            </button>
            <button type="button"
                @click="showDeleteModal = true; productToDelete = {{ $product->id }}; productName = '{{ addslashes($product->name) }}'"
                class="px-3 py-2 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </div>
    </div>
</div>
