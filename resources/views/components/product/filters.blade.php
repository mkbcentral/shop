@props(['categories', 'search', 'categoryFilter', 'statusFilter', 'stockLevelFilter'])

@php
    $showStock = has_stock_management();
@endphp

<x-card>
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-900">Filtres</h2>
        @if($search || $categoryFilter || $statusFilter || ($showStock && $stockLevelFilter))
            <button wire:click="resetFilters" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                Réinitialiser les filtres
            </button>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-{{ $showStock ? 5 : 4 }} gap-4">
        <!-- Search -->
        <div class="md:col-span-2">
            <x-form.search-input
                wire:model.live.debounce.300ms="search"
                wireModel="search"
                :placeholder="'Rechercher par nom ou référence...'"
            />
        </div>

        <!-- Category Filter -->
        <div>
            <x-form.select wire:model.live="categoryFilter">
                <option value="">Toutes les catégories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </x-form.select>
        </div>

        <!-- Status Filter -->
        <div>
            <x-form.select wire:model.live="statusFilter">
                <option value="">Tous les statuts</option>
                <option value="active">Actif</option>
                <option value="inactive">Inactif</option>
            </x-form.select>
        </div>

        @if($showStock)
        <!-- Stock Level Filter -->
        <div>
            <x-form.select wire:model.live="stockLevelFilter">
                <option value="">Tous les stocks</option>
                <option value="low">Stock faible (≤10)</option>
                <option value="medium">Stock moyen (11-50)</option>
                <option value="high">Stock élevé (>50)</option>
            </x-form.select>
        </div>
        @endif
    </div>
</x-card>
