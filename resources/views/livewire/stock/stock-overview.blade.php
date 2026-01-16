<div>
    <x-slot name="header">
        <x-breadcrumb :items="[
            ['label' => 'Accueil', 'url' => route('dashboard')],
            ['label' => 'État du Stock']
        ]" />
    </x-slot>

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">État du Stock</h1>
            <p class="text-gray-500 mt-1">Vue d'ensemble de votre inventaire et valorisation</p>
        </div>
        <div class="flex space-x-3">
            <!-- Link to Movements -->
            <x-form.button href="{{ route('stock.index') }}" variant="secondary" icon="clock">
                Historique des Mouvements
            </x-form.button>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-green-800">{{ session('message') }}</p>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-red-800">{{ session('error') }}</p>
        </div>
    @endif

    <!-- KPI Dashboard / Loading Skeleton -->
    <div class="mb-6">
        <div wire:loading.remove wire:target="search,categoryId,stockLevel,sortField,sortDirection,perPage">
            <x-stock.kpi-dashboard :kpis="$kpis" />
        </div>

        <div wire:loading wire:target="search,categoryId,stockLevel,sortField,sortDirection,perPage">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                @for($i = 0; $i < 4; $i++)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="h-4 bg-gray-200 rounded w-2/3 mb-2 animate-pulse"></div>
                                <div class="h-6 bg-gray-200 rounded w-1/2 mb-1 animate-pulse"></div>
                                <div class="h-3 bg-gray-200 rounded w-1/3 animate-pulse"></div>
                            </div>
                            <div class="h-10 w-10 bg-gray-100 rounded-lg animate-pulse"></div>
                        </div>
                    </div>
                @endfor
            </div>

            <!-- Skeleton for Profit Card -->
            <div class="bg-gradient-to-r from-gray-400 to-gray-500 rounded-lg shadow-sm p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="h-4 bg-white/30 rounded w-1/3 mb-2 animate-pulse"></div>
                        <div class="h-7 bg-white/30 rounded w-1/2 mb-2 animate-pulse"></div>
                        <div class="h-3 bg-white/30 rounded w-2/3 animate-pulse"></div>
                    </div>
                    <div class="h-12 w-12 bg-white/20 rounded-lg animate-pulse"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <x-stock.filters
        :categories="$categories"
        :search="$search"
        :categoryId="$categoryId"
        :stockLevel="$stockLevel"
    />

    <!-- Toolbar & Table Content -->
    <div wire:loading.remove wire:target="search,categoryId,stockLevel,sortField,sortDirection,perPage">
        <!-- Toolbar -->
        <x-stock.toolbar
            :total="$variants->total()"
            :sortField="$sortField"
            :sortDirection="$sortDirection"
        />

        <!-- Inventory Table -->
        <x-stock.inventory-table :variants="$variants" />

        <!-- Pagination -->
        <div class="mt-4">
            {{ $variants->links() }}
        </div>
    </div>

    <!-- Loading Skeleton for Toolbar & Table -->
    <div wire:loading wire:target="search,categoryId,stockLevel,sortField,sortDirection,perPage">
        <!-- Toolbar Skeleton -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
            <div class="flex items-center justify-between">
                <div class="h-5 bg-gray-200 rounded w-32 animate-pulse"></div>
                <div class="flex space-x-2">
                    <div class="h-10 w-24 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-10 w-24 bg-gray-200 rounded animate-pulse"></div>
                </div>
            </div>
        </div>

        <!-- Table Skeleton -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left"><div class="h-4 bg-gray-200 rounded w-24 animate-pulse"></div></th>
                            <th class="px-6 py-3 text-left"><div class="h-4 bg-gray-200 rounded w-16 animate-pulse"></div></th>
                            <th class="px-6 py-3 text-center"><div class="h-4 bg-gray-200 rounded w-16 mx-auto animate-pulse"></div></th>
                            <th class="px-6 py-3 text-center"><div class="h-4 bg-gray-200 rounded w-16 mx-auto animate-pulse"></div></th>
                            <th class="px-6 py-3 text-right"><div class="h-4 bg-gray-200 rounded w-24 ml-auto animate-pulse"></div></th>
                            <th class="px-6 py-3 text-right"><div class="h-4 bg-gray-200 rounded w-24 ml-auto animate-pulse"></div></th>
                            <th class="px-6 py-3 text-center"><div class="h-4 bg-gray-200 rounded w-16 mx-auto animate-pulse"></div></th>
                            <th class="px-6 py-3 text-right"><div class="h-4 bg-gray-200 rounded w-20 ml-auto animate-pulse"></div></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @for($i = 0; $i < 10; $i++)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="h-4 bg-gray-200 rounded w-32 mb-2 animate-pulse"></div>
                                    <div class="h-3 bg-gray-200 rounded w-24 animate-pulse"></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="h-4 bg-gray-200 rounded w-20 animate-pulse"></div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="h-6 bg-gray-200 rounded w-12 mx-auto animate-pulse"></div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="h-4 bg-gray-200 rounded w-8 mx-auto animate-pulse"></div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="h-4 bg-gray-200 rounded w-20 ml-auto animate-pulse"></div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="h-4 bg-gray-200 rounded w-24 ml-auto animate-pulse"></div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="h-5 bg-gray-200 rounded-full w-20 mx-auto animate-pulse"></div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <div class="h-8 w-8 bg-gray-200 rounded animate-pulse"></div>
                                        <div class="h-8 w-8 bg-gray-200 rounded animate-pulse"></div>
                                    </div>
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Adjust Modal -->
    <x-stock.adjust-modal
        :adjustingVariant="$adjustingVariant"
        :newQuantity="$newQuantity"
    />
</div>
