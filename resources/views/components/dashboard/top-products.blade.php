@props(['products'])

<x-card>
    <x-slot:header>
        <x-card-title title="Produits les plus vendus">
            <x-slot:action>
                <a href="{{ route('products.index') }}" wire:navigate class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">Voir tout →</a>
            </x-slot:action>
        </x-card-title>
    </x-slot:header>

    <div class="space-y-3">
        @forelse($products as $index => $product)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                <div class="flex items-center gap-3 flex-1">
                    <span class="flex items-center justify-center w-8 h-8 rounded-full {{ $index === 0 ? 'bg-yellow-100 text-yellow-600' : ($index === 1 ? 'bg-gray-100 text-gray-600' : 'bg-orange-100 text-orange-600') }} font-bold text-sm">
                        {{ $index + 1 }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</p>
                        <p class="text-xs text-gray-500">{{ $product->total_quantity }} vendus</p>
                    </div>
                </div>
                <span class="text-sm font-semibold text-gray-900">@currency($product->total_revenue)</span>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <p>Aucune vente enregistrée</p>
            </div>
        @endforelse
    </div>
</x-card>
