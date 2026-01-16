<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Tableau de Bord Stock</h2>
            <p class="mt-1 text-sm text-gray-600">Vue d'ensemble de l'état de votre stock</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('stock.index') }}" wire:navigate>
                <x-form.button>
                    <svg class="w-5 h-5 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Mouvements
                </x-form.button>
            </a>
        </div>
    </div>

    <!-- Date Filter -->
    <x-card class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-form.label for="dateFrom">Date début</x-form.label>
                <x-form.input type="date" wire:model.live="dateFrom" id="dateFrom" />
            </div>
            <div>
                <x-form.label for="dateTo">Date fin</x-form.label>
                <x-form.input type="date" wire:model.live="dateTo" id="dateTo" />
            </div>
        </div>
    </x-card>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Total Entries -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Entrées Totales</p>
                    <p class="text-2xl font-bold mt-1">{{ number_format($stats['total_in']) }}</p>
                </div>
                <div class="bg-white/20 rounded-lg p-2">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Exits -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Sorties Totales</p>
                    <p class="text-2xl font-bold mt-1">{{ number_format($stats['total_out']) }}</p>
                </div>
                <div class="bg-white/20 rounded-lg p-2">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Movement Value -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Valeur Mouvements</p>
                    <p class="text-2xl font-bold mt-1">@currency($stats['total_value'])</p>
                </div>
                <div class="bg-white/20 rounded-lg p-2">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Movements -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Mouvements</p>
                    <p class="text-2xl font-bold mt-1">{{ number_format($stats['total_movements']) }}</p>
                </div>
                <div class="bg-white/20 rounded-lg p-2">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Out of Stock Products -->
        <x-card :padding="false">
            <x-slot name="header">
                <div class="flex items-center justify-between px-6 pt-6">
                    <h3 class="text-lg font-bold text-gray-900">Ruptures de Stock</h3>
                    <a href="{{ route('stock.alerts') }}" wire:navigate class="text-sm text-indigo-600 hover:text-indigo-900">Voir tout</a>
                </div>
            </x-slot>
            <div class="p-6">
                @if($outOfStockProducts->count() > 0)
                    <div class="space-y-4">
                        @foreach($outOfStockProducts as $variant)
                            <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $variant->product->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $variant->sku }}</p>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Rupture
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 text-center py-4">Aucune rupture de stock</p>
                @endif
            </div>
        </x-card>

        <!-- Low Stock Products -->
        <x-card :padding="false">
            <x-slot name="header">
                <div class="flex items-center justify-between px-6 pt-6">
                    <h3 class="text-lg font-bold text-gray-900">Stock Bas</h3>
                    <a href="{{ route('stock.alerts') }}" wire:navigate class="text-sm text-indigo-600 hover:text-indigo-900">Voir tout</a>
                </div>
            </x-slot>
            <div class="p-6">
                @if($lowStockProducts->count() > 0)
                    <div class="space-y-4">
                        @foreach($lowStockProducts as $variant)
                            <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $variant->product->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $variant->sku }} - Stock: {{ $variant->stock_quantity }}</p>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Alerte
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 text-center py-4">Aucun stock bas</p>
                @endif
            </div>
        </x-card>
    </div>

    <!-- Recent Movements -->
    <x-card :padding="false">
        <x-slot name="header">
            <div class="flex items-center justify-between px-6 pt-6">
                <h3 class="text-lg font-bold text-gray-900">Mouvements Récents</h3>
                <a href="{{ route('stock.index') }}" wire:navigate class="text-sm text-indigo-600 hover:text-indigo-900">Voir tout</a>
            </div>
        </x-slot>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentMovements as $movement)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $movement->date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $movement->productVariant->product->name }}</div>
                                <div class="text-xs text-gray-500">{{ $movement->productVariant->sku }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($movement->type === 'in')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Entrée
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Sortie
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{ $movement->type === 'in' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $movement->type === 'in' ? '+' : '-' }}{{ $movement->quantity }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $movement->user->name }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                Aucun mouvement récent
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</div>
