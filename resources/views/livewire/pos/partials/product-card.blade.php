<!-- Product Card -->
<button wire:click="addToCart({{ $variant->id }})"
    wire:loading.class="opacity-50 cursor-wait"
    wire:loading.attr="disabled"
    wire:target="addToCart({{ $variant->id }})"
    class="bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden group border-2 border-transparent hover:border-indigo-400 transform hover:-translate-y-2 flex flex-col h-full">
    <div class="aspect-square bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 flex items-center justify-center p-6 relative overflow-hidden flex-shrink-0">
        @if ($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                class="w-full h-full object-cover rounded-xl group-hover:scale-110 transition-transform duration-300">
        @else
            <svg class="w-28 h-28 text-gray-300 group-hover:text-indigo-400 group-hover:scale-110 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
        @endif
        <div class="absolute top-3 right-3">
            @php $currentStock = $variant->current_stock; @endphp
            <span class="px-3 py-1.5 rounded-full text-sm font-bold shadow-lg backdrop-blur-sm {{ $currentStock > 10 ? 'bg-green-500/90 text-white' : ($currentStock > 0 ? 'bg-orange-500/90 text-white' : 'bg-red-500/90 text-white') }}">
                {{ $currentStock }}
            </span>
        </div>
    </div>
    <div class="p-5 flex flex-col flex-grow">
        <h3 class="font-bold text-base text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors line-clamp-2 min-h-[3rem]" title="{{ $product->name }}">{{ $product->name }}</h3>
        @if($variant->size || $variant->color)
            <p class="text-sm text-gray-500 mb-3 flex items-center gap-2 flex-wrap min-h-[2rem]">
                @if($variant->size)
                    <span class="px-2.5 py-1 bg-gray-100 rounded-md font-medium">{{ $variant->size }}</span>
                @endif
                @if($variant->color)
                    <span class="px-2.5 py-1 bg-gray-100 rounded-md font-medium">{{ $variant->color }}</span>
                @endif
            </p>
        @else
            <div class="mb-3 min-h-[2rem]"></div>
        @endif
        <div class="flex items-center justify-between mt-auto">
            <span class="text-xl font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">
                {{ number_format($product->price, 0, ',', ' ') }}
            </span>
            <span class="text-sm font-semibold text-gray-500">{{ current_currency() }}</span>
        </div>
    </div>
</button>
