<div x-data="{ showDeleteModal: false, productToDelete: null, productName: '' }">
    <!-- Toast Notifications -->
    <x-toast />

    <!-- Include Product Modal -->
    <livewire:product.product-modal />

    <!-- Include Label Modal -->
    <livewire:product.label-modal />

    <x-slot name="header">
        <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('dashboard')], ['label' => 'Produits']]" />
    </x-slot>

    <div class="flex items-center justify-between mt-2">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestion des Produits</h1>
            <p class="text-gray-500 mt-1">Gérez votre catalogue de produits</p>
        </div>
        <div class="flex items-center space-x-3">
            <button wire:click="generateAllLabels"
                    class="inline-flex items-center px-4 py-2 border border-indigo-300 rounded-lg text-sm font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                Étiquettes (Tous)
            </button>
            @permission('products.create')
            @if($canAddProduct)
                <x-form.button wire:click="$dispatch('openProductModal')" icon="plus">
                    Nouveau Produit
                </x-form.button>
            @else
                <div class="inline-flex items-center px-4 py-2 bg-amber-100 text-amber-800 font-semibold rounded-lg border border-amber-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Limite atteinte ({{ $productsUsage['current'] ?? 0 }}/{{ $productsUsage['max'] ?? 0 }})</span>
                    @if($currentOrg)
                        <a href="{{ route('organizations.subscription', $currentOrg) }}" class="ml-2 text-amber-900 underline hover:no-underline">Upgrader</a>
                    @endif
                </div>
            @endif
            @endpermission
        </div>
    </div>

    <!-- KPI Dashboard -->
    <x-product.kpi-dashboard :kpis="$kpis" />

    <div class="space-y-6">
        <!-- Filters -->
        <x-product.filters :categories="$categories" :search="$search" :categoryFilter="$categoryFilter" :statusFilter="$statusFilter" :stockLevelFilter="$stockLevelFilter" />

        <!-- Products List -->
        <x-card>
            <x-slot:header>
                <div class="flex items-center justify-between">
                    <x-card-title title="Liste des Produits ({{ $products->total() }})">
                        <x-slot:action>
                            <x-product.toolbar :selected="$selected" :viewMode="$viewMode" :densityMode="$densityMode" :categoryFilter="$categoryFilter"
                                :statusFilter="$statusFilter" :perPage="$perPage" />
                        </x-slot:action>
                    </x-card-title>
                </div>
            </x-slot:header>

            <!-- Loading Skeleton -->
            <div wire:loading.delay.long>
                @if ($viewMode === 'table')
                    <x-product.table-skeleton :rows="$perPage" :densityMode="$densityMode" />
                @else
                    <x-product.grid-skeleton :count="$perPage" />
                @endif
            </div>

            <!-- Content -->
            <div wire:loading.remove.delay.long>
                @if ($viewMode === 'table')
                    <x-product.table-view :products="$products" :densityMode="$densityMode" :selectAll="$selectAll" />
                @else
                    <x-product.grid-view :products="$products" />
                @endif

                @if ($products->hasPages())
                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </x-card>

        <!-- Delete Confirmation Modal -->
        <x-delete-confirmation-modal show="showDeleteModal" itemName="productName" itemType="le produit"
            onConfirm="$wire.set('productToDelete', productToDelete); $wire.call('delete'); showDeleteModal = false"
            onCancel="showDeleteModal = false; productToDelete = null; productName = ''" />
    </div>
</div>
