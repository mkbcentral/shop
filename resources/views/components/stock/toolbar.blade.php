@props(['total', 'sortField' => 'stock_quantity', 'sortDirection' => 'asc'])

<div class="flex items-center justify-between mb-4">
    <div class="flex items-center space-x-4">
        <!-- Results Count -->
        <p class="text-sm text-gray-600">
            <span class="font-semibold">{{ $total }}</span> produit(s) trouvé(s)
        </p>

        <!-- Sort Dropdown -->
        <div class="flex items-center space-x-2">
            <x-form.label class="mb-0">Trier par :</x-form.label>
            <x-form.select wire:model.live="sortField" class="text-sm">
                <option value="stock_quantity">Stock (quantité)</option>
                <option value="name">Nom</option>
                <option value="value">Valeur</option>
            </x-form.select>

            <!-- Sort Direction Toggle -->
            <button
                wire:click="sortBy('{{ $sortField }}')"
                class="p-2 hover:bg-gray-100 rounded-lg transition"
                title="Changer l'ordre"
            >
                @if($sortDirection === 'asc')
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                    </svg>
                @else
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4" />
                    </svg>
                @endif
            </button>
        </div>

        <!-- Export Dropdown -->
        <x-dropdown align="right">
            <x-slot name="trigger">
                <x-form.button variant="primary" icon="download">
                    Exporter
                    <x-icons.chevron-down class="w-4 h-4 ml-2" />
                </x-form.button>
            </x-slot>

            <x-dropdown-item wire:click="exportExcel" icon="download" iconColor="green">
                Exporter en Excel
            </x-dropdown-item>
            <x-dropdown-item wire:click="exportPdf" icon="document" iconColor="red">
                Exporter en PDF
            </x-dropdown-item>
        </x-dropdown>
    </div>

    <!-- Per Page -->
    <div class="flex items-center space-x-2">
        <x-form.label class="mb-0">Afficher :</x-form.label>
        <x-form.select wire:model.live="perPage" class="text-sm">
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </x-form.select>
    </div>
</div>
