@php
    $isServiceOrg = is_service_organization();
    $entityLabel = $isServiceOrg ? 'Services' : 'Produits';
    $entitySingular = $isServiceOrg ? 'service' : 'produit';
@endphp

<div>
    <x-slot name="header">
        <x-breadcrumb :items="[
            ['label' => 'Accueil', 'url' => route('dashboard')],
            ['label' => $entityLabel, 'url' => route('products.index')],
            ['label' => 'Historique des Prix']
        ]" />
    </x-slot>

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Historique des Prix</h1>
            <p class="text-gray-500 mt-1">Traçabilité complète des modifications de prix</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Total Changes -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-indigo-100">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Modifications</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_changes']) }}</p>
                </div>
            </div>
        </div>

        <!-- Increases -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Augmentations</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($stats['increases']) }}</p>
                </div>
            </div>
        </div>

        <!-- Decreases -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Diminutions</p>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($stats['decreases']) }}</p>
                </div>
            </div>
        </div>

        <!-- Average Change -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-amber-100">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Variation Moyenne</p>
                    <p class="text-2xl font-bold {{ $stats['avg_change'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $stats['avg_change'] >= 0 ? '+' : '' }}{{ number_format($stats['avg_change'], 2) }}%
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <!-- Search -->
            <x-form.search-input
                wire:model.live.debounce.300ms="search"
                wireModel="search"
                :placeholder="'Rechercher ' . $entitySingular . '...'"
            />

            <!-- Product Filter -->
            <x-form.select wire:model.live="productFilter">
                <option value="">Tous les {{ strtolower($entityLabel) }}</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </x-form.select>

            <!-- Price Type Filter -->
            <x-form.select wire:model.live="priceTypeFilter">
                <option value="">Tous les types</option>
                <option value="price">Prix de vente</option>
                @if(!$isServiceOrg)
                    <option value="cost_price">Prix d'achat</option>
                @endif
                <option value="additional_price">Supplément variante</option>
            </x-form.select>

            <!-- Change Direction Filter -->
            <x-form.select wire:model.live="changeDirection">
                <option value="">Toutes directions</option>
                <option value="increase">Augmentations</option>
                <option value="decrease">Diminutions</option>
            </x-form.select>

            <!-- Date From -->
            <x-form.input type="date" wire:model.live="dateFrom" />

            <!-- Date To -->
            <x-form.input type="date" wire:model.live="dateTo" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <x-form.select wire:model.live="perPage" class="w-32">
                <option value="15">15 par page</option>
                <option value="25">25 par page</option>
                <option value="50">50 par page</option>
                <option value="100">100 par page</option>
            </x-form.select>

            <button wire:click="resetFilters" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                Réinitialiser les filtres
            </button>
        </div>
    </div>

    <!-- Price History Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <x-table.table>
            <x-table.head>
                <x-table.header>Date</x-table.header>
                <x-table.header>{{ $isServiceOrg ? 'Service' : 'Produit' }}</x-table.header>
                <x-table.header>Type de prix</x-table.header>
                <x-table.header align="right">Ancien prix</x-table.header>
                <x-table.header align="right">Nouveau prix</x-table.header>
                <x-table.header align="right">Variation</x-table.header>
                <x-table.header>Raison</x-table.header>
                <x-table.header>Utilisateur</x-table.header>
            </x-table.head>
            <x-table.body>
                @forelse($priceHistories as $history)
                    <x-table.row wire:key="history-{{ $history->id }}">
                        <x-table.cell>
                            <div class="text-sm font-medium text-gray-900">
                                {{ $history->changed_at->format('d/m/Y') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $history->changed_at->format('H:i') }}
                            </div>
                        </x-table.cell>

                        <x-table.cell>
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $history->product->name ?? 'N/A' }}
                                    </div>
                                    @if($history->productVariant)
                                        <div class="text-xs text-gray-500">
                                            Variante: {{ $history->productVariant->getVariantName() }}
                                        </div>
                                    @endif
                                    <div class="text-xs text-gray-400">
                                        Réf: {{ $history->product->reference ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </x-table.cell>

                        <x-table.cell>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($history->price_type === 'price') bg-blue-100 text-blue-800
                                @elseif($history->price_type === 'cost_price') bg-purple-100 text-purple-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $history->price_type_label }}
                            </span>
                        </x-table.cell>

                        <x-table.cell align="right">
                            <span class="text-sm text-gray-500">
                                {{ $history->old_price !== null ? number_format($history->old_price, 2, ',', ' ') . ' ' . current_currency() : '-' }}
                            </span>
                        </x-table.cell>

                        <x-table.cell align="right">
                            <span class="text-sm font-medium text-gray-900">
                                {{ number_format($history->new_price, 2, ',', ' ') }} {{ current_currency() }}
                            </span>
                        </x-table.cell>

                        <x-table.cell align="right">
                            @if($history->price_difference !== null)
                                <div class="flex flex-col items-end">
                                    <span class="inline-flex items-center text-sm font-medium
                                        {{ $history->price_difference > 0 ? 'text-green-600' : ($history->price_difference < 0 ? 'text-red-600' : 'text-gray-500') }}">
                                        @if($history->price_difference > 0)
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                            </svg>
                                            +{{ number_format($history->price_difference, 2, ',', ' ') }}
                                        @elseif($history->price_difference < 0)
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                            {{ number_format($history->price_difference, 2, ',', ' ') }}
                                        @else
                                            0
                                        @endif
                                    </span>
                                    @if($history->percentage_change !== null)
                                        <span class="text-xs {{ $history->percentage_change > 0 ? 'text-green-500' : ($history->percentage_change < 0 ? 'text-red-500' : 'text-gray-400') }}">
                                            ({{ $history->percentage_change > 0 ? '+' : '' }}{{ number_format($history->percentage_change, 1) }}%)
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </x-table.cell>

                        <x-table.cell>
                            <span class="text-sm text-gray-600 max-w-xs truncate" title="{{ $history->reason }}">
                                {{ $history->reason ?? '-' }}
                            </span>
                        </x-table.cell>

                        <x-table.cell>
                            <div class="text-sm text-gray-900">
                                {{ $history->user->name ?? 'Système' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ ucfirst($history->source) }}
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="8">
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun historique</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Aucune modification de prix n'a été enregistrée pour ce {{ $entitySingular }} sur cette période.
                                </p>
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @endforelse
            </x-table.body>
        </x-table.table>

        @if($priceHistories->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $priceHistories->links() }}
            </div>
        @endif
    </div>
</div>
