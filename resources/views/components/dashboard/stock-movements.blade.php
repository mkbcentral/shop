@props(['movements'])

<x-card>
    <x-slot:header>
        <x-card-title title="Mouvements de Stock Récents">
            <x-slot:action>
                <a href="{{ route('stock.index') }}" wire:navigate class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">Voir tout →</a>
            </x-slot:action>
        </x-card-title>
    </x-slot:header>

    <div class="space-y-3">
        @forelse($movements as $movement)
            <div class="flex items-start gap-3 p-3 hover:bg-gray-50 rounded-lg transition group">
                <div class="p-2 {{ $movement->type === 'in' ? 'bg-green-100' : 'bg-red-100' }} rounded-lg group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4 {{ $movement->type === 'in' ? 'text-green-600' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($movement->type === 'in')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                        @endif
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $movement->productVariant->product->name }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $movement->type === 'in' ? 'Entrée' : 'Sortie' }} de {{ $movement->quantity }} unités
                    </p>
                </div>
                <span class="text-xs text-gray-400">{{ $movement->date->diffForHumans() }}</span>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p>Aucun mouvement récent</p>
            </div>
        @endforelse
    </div>
</x-card>
