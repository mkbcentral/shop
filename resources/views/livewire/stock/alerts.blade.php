<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Alertes de Stock</h2>
            <p class="mt-1 text-sm text-gray-600">Surveillez les produits en rupture ou avec un stock bas</p>
        </div>
    </div>

    <!-- Filters -->
    <x-card class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <x-form.search-input
                wire:model.live.debounce.300ms="search"
                wireModel="search"
                placeholder="Rechercher un produit..."
            />

            <!-- Alert Type Filter -->
            <x-form.select wire:model.live="alertType">
                <option value="all">Toutes les alertes</option>
                <option value="out_of_stock">Rupture de stock</option>
                <option value="low_stock">Stock bas</option>
            </x-form.select>

            <!-- Per Page Selector -->
            <x-form.select wire:model.live="perPage">
                <option value="10">10 par page</option>
                <option value="25">25 par page</option>
                <option value="50">50 par page</option>
                <option value="100">100 par page</option>
            </x-form.select>
        </div>
    </x-card>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Out of Stock -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Rupture de stock</p>
                    <p class="text-3xl font-bold mt-2">
                        {{ \App\Models\ProductVariant::where('stock_quantity', '<=', 0)->count() }}
                    </p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Low Stock -->
        <div class="bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Stock bas</p>
                    <p class="text-3xl font-bold mt-2">
                        {{ \App\Models\ProductVariant::where('stock_quantity', '>', 0)->whereRaw('stock_quantity <= low_stock_threshold')->count() }}
                    </p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Alerts -->
        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-indigo-100 text-sm font-medium">Total alertes</p>
                    <p class="text-3xl font-bold mt-2">
                        {{ \App\Models\ProductVariant::where(function($q) {
                            $q->where('stock_quantity', '<=', 0)
                              ->orWhereRaw('stock_quantity <= low_stock_threshold');
                        })->count() }}
                    </p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <x-card :padding="false">
        <x-table.table>
            <x-table.head>
                <tr>
                    <x-table.header>Produit</x-table.header>
                    <x-table.header>Stock Actuel</x-table.header>
                    <x-table.header>Seuil d'Alerte</x-table.header>
                    <x-table.header>Statut</x-table.header>
                    <x-table.header align="center">Actions</x-table.header>
                </tr>
            </x-table.head>

            <x-table.body>
                @forelse ($variants as $variant)
                    <x-table.row wire:key="variant-{{ $variant->id }}">
                        <x-table.cell>
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $variant->product->name }}</div>
                                    <div class="text-xs text-gray-500">
                                        SKU: {{ $variant->sku }}
                                        @if($variant->size || $variant->color)
                                            - {{ $variant->size }} {{ $variant->color }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </x-table.cell>
                        <x-table.cell>
                            <span class="text-lg font-bold {{ $variant->stock_quantity <= 0 ? 'text-red-600' : ($variant->stock_quantity <= $variant->low_stock_threshold ? 'text-yellow-600' : 'text-green-600') }}">
                                {{ $variant->stock_quantity }}
                            </span>
                        </x-table.cell>
                        <x-table.cell>
                            <span class="text-sm text-gray-600">{{ $variant->low_stock_threshold }}</span>
                        </x-table.cell>
                        <x-table.cell>
                            @if($variant->stock_quantity <= 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    Rupture de stock
                                </span>
                            @elseif($variant->stock_quantity <= $variant->low_stock_threshold)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    Stock bas
                                </span>
                            @endif
                        </x-table.cell>
                        <x-table.cell align="center">
                            <x-actions-dropdown>
                                <x-dropdown-item href="{{ route('products.index') }}" wireNavigate icon="eye">
                                    Voir le produit
                                </x-dropdown-item>
                                <x-dropdown-item href="{{ route('stock.history', $variant->id) }}" wireNavigate icon="clock">
                                    Historique
                                </x-dropdown-item>
                                <x-dropdown-item href="{{ route('stock.index') }}" wireNavigate icon="plus">
                                    Ajouter du stock
                                </x-dropdown-item>
                            </x-actions-dropdown>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.empty-state
                        colspan="5"
                        title="Aucune alerte de stock"
                        description="Tous vos produits ont un stock suffisant."
                    >
                        <x-slot name="icon">
                            <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </x-slot>
                    </x-table.empty-state>
                @endforelse
            </x-table.body>
        </x-table.table>

        <!-- Pagination -->
        @if($variants->hasPages())
            <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-t border-gray-200">
                {{ $variants->links() }}
            </div>
        @endif
    </x-card>
</div>
