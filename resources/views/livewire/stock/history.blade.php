<div>
    <x-slot name="header">
        <x-breadcrumb :items="[
            ['label' => 'Accueil', 'url' => route('dashboard')],
            ['label' => 'Stock', 'url' => route('stock.index')],
            ['label' => 'Historique']
        ]" />
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <!-- Header -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Historique des Mouvements</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ $variant->product->name }}
                        @if($variant->size || $variant->color)
                            - {{ $variant->size }} {{ $variant->color }}
                        @endif
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Stock actuel</p>
                    <p class="text-2xl font-bold text-indigo-600">{{ $variant->stock_quantity }}</p>
                </div>
            </div>
        </div>

    <!-- Timeline -->
    <div class="p-6">
        @if($movements->count() > 0)
            <div class="flow-root">
                <ul role="list" class="-mb-8">
                    @foreach($movements as $index => $movement)
                        <li wire:key="movement-{{ $movement->id }}">
                            <div class="relative pb-8">
                                @if(!$loop->last)
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                @endif
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white
                                            {{ $movement->type === 'in' ? 'bg-green-500' : 'bg-red-500' }}">
                                            @if($movement->type === 'in')
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                                </svg>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                        <div>
                                            <p class="text-sm text-gray-900">
                                                <span class="font-semibold {{ $movement->type === 'in' ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $movement->type === 'in' ? '+' : '-' }}{{ $movement->quantity }}
                                                </span>
                                                <span class="text-gray-600">
                                                    - {{ ucfirst(str_replace('_', ' ', $movement->movement_type)) }}
                                                </span>
                                            </p>
                                            @if($movement->reference)
                                                <p class="mt-1 text-xs text-gray-500">
                                                    Réf: {{ $movement->reference }}
                                                </p>
                                            @endif
                                            @if($movement->reason)
                                                <p class="mt-1 text-sm text-gray-600">
                                                    {{ $movement->reason }}
                                                </p>
                                            @endif
                                            <p class="mt-1 text-xs text-gray-500">
                                                Par {{ $movement->user->name }}
                                            </p>
                                        </div>
                                        <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                            <time datetime="{{ $movement->date->format('Y-m-d') }}">
                                                {{ $movement->date->format('d/m/Y') }}
                                            </time>
                                            <p class="text-xs text-gray-400">
                                                {{ $movement->created_at->format('H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Load More Button -->
            @if($movements->count() >= $limit)
                <div class="mt-6 text-center">
                    <x-form.button
                        wire:click="loadMore"
                        variant="secondary"
                        size="sm"
                    >
                        Voir plus de mouvements
                    </x-form.button>
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun mouvement</h3>
                <p class="mt-1 text-sm text-gray-500">Ce produit n'a pas encore de mouvements de stock.</p>
            </div>
        @endif
    </div>
    </div>
</div>
