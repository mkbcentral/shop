@props(['categories', 'search' => '', 'categoryId' => '', 'stockLevel' => ''])

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Search -->
        <div class="md:col-span-2">
            <x-form.label>Rechercher</x-form.label>
            <x-form.search-input
                wire:model.live.debounce.300ms="search"
                placeholder="Nom, SKU, code-barres..."
            />
        </div>

        <!-- Category Filter -->
        <div>
            <x-form.label>Catégorie</x-form.label>
            <x-form.select wire:model.live="categoryId">
                <option value="">Toutes les catégories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </x-form.select>
        </div>

        <!-- Stock Level Filter -->
        <div>
            <x-form.label>État du Stock</x-form.label>
            <x-form.select wire:model.live="stockLevel">
                <option value="">Tous</option>
                <option value="in_stock">En stock</option>
                <option value="low_stock">Stock faible</option>
                <option value="out_of_stock">Rupture</option>
            </x-form.select>
        </div>
    </div>

    <!-- Reset Filters -->
    @if($search || $categoryId || $stockLevel)
        <div class="mt-3 flex items-center justify-end">
            <x-form.button
                wire:click="resetFilters"
                variant="secondary"
                size="sm"
                icon="x"
            >
                Réinitialiser les filtres
            </x-form.button>
        </div>
    @endif
</div>
