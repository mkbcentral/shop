<!-- Cart Item - Ultra Compact -->
<div class="bg-white rounded-lg px-2.5 py-2 shadow-sm hover:shadow-md transition-all duration-200 border border-gray-100" wire:key="cart-{{ $key }}">
    <div class="flex items-center gap-2">
        <!-- Nom et variantes -->
        <div class="flex-1 min-w-0">
            <div class="flex items-baseline gap-1.5">
                <h3 class="font-bold text-gray-900 text-xs truncate">{{ $item['product_name'] }}</h3>
                @if($item['variant_size'] || $item['variant_color'])
                    <span class="text-xs text-gray-400 flex-shrink-0">
                        @if($item['variant_size']){{ $item['variant_size'] }}@endif
                        @if($item['variant_size'] && $item['variant_color'])/@endif
                        @if($item['variant_color']){{ $item['variant_color'] }}@endif
                    </span>
                @endif
            </div>
            <p class="text-xs text-gray-500">@currency($item['price'])</p>
        </div>

        <!-- Quantité -->
        <div class="flex items-center gap-1">
            <button wire:click="updateQuantity('{{ $key }}', {{ $item['quantity'] - 1 }})"
                class="w-6 h-6 flex items-center justify-center bg-gray-100 hover:bg-indigo-100 rounded transition-all group">
                <svg class="w-3 h-3 text-gray-600 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4" />
                </svg>
            </button>
            <input type="number" wire:model.blur="cart.{{ $key }}.quantity"
                wire:change="calculateTotals"
                class="w-10 text-center text-sm font-bold border border-gray-200 rounded py-0.5 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                min="1" max="{{ $item['stock'] }}">
            <button wire:click="updateQuantity('{{ $key }}', {{ $item['quantity'] + 1 }})"
                class="w-6 h-6 flex items-center justify-center bg-gray-100 hover:bg-indigo-100 rounded transition-all group">
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
        <button wire:click="removeFromCart('{{ $key }}')"
            class="p-1 text-red-500 hover:bg-red-50 rounded transition-colors flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>
