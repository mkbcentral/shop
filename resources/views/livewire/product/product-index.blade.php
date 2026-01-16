<div x-data="{ showDeleteModal: false, productToDelete: null, productName: '' }">
    <!-- Toast Notifications -->
    <x-toast />

    <!-- Include Product Modal -->
    <livewire:product.product-modal />

    <x-slot name="header">
        <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('dashboard')], ['label' => 'Produits']]" />
    </x-slot>

    <div class="flex items-center justify-between mt-2">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestion des Produits</h1>
            <p class="text-gray-500 mt-1">Gérez votre catalogue de produits</p>
        </div>
        <div class="flex items-center space-x-3">
            <x-form.button wire:click="$dispatch('openProductModal')" icon="plus">
                Nouveau Produit
            </x-form.button>
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
