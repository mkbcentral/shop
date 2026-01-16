@props(['rows' => 5, 'densityMode' => 'comfortable'])

@php
    $paddingClass = $densityMode === 'compact' ? 'py-1.5' : ($densityMode === 'spacious' ? 'py-8' : 'py-4');
    $imageSize = $densityMode === 'compact' ? 'w-8 h-8' : ($densityMode === 'spacious' ? 'w-16 h-16' : 'w-10 h-10');
@endphp

<x-table.table>
    <x-table.head>
        <tr>
            <x-table.header class="w-8">
                <div class="w-5 h-5 bg-gray-200 rounded-md animate-pulse"></div>
            </x-table.header>
            <x-table.header>Produit</x-table.header>
            <x-table.header>Référence</x-table.header>
            <x-table.header>Prix</x-table.header>
            <x-table.header>Stock</x-table.header>
            <x-table.header>Statut</x-table.header>
            <x-table.header align="right">Actions</x-table.header>
        </tr>
    </x-table.head>
    <x-table.body>
        @for($i = 0; $i < $rows; $i++)
            <x-table.row class="{{ $paddingClass }}">
                <!-- Checkbox -->
                <x-table.cell>
                    <div class="w-5 h-5 bg-gray-200 rounded-md animate-pulse"></div>
                </x-table.cell>

                <!-- Product Info -->
                <x-table.cell>
                    <div class="flex items-center space-x-3">
                        <div class="{{ $imageSize }} bg-gray-200 rounded-lg animate-pulse"></div>
                        <div class="flex-1 space-y-2">
                            <div class="h-4 bg-gray-200 rounded w-32 animate-pulse"></div>
                            <div class="h-3 bg-gray-200 rounded w-20 animate-pulse"></div>
                        </div>
                    </div>
                </x-table.cell>

                <!-- Reference -->
                <x-table.cell>
                    <div class="h-4 bg-gray-200 rounded w-24 animate-pulse"></div>
                </x-table.cell>

                <!-- Price -->
                <x-table.cell>
                    <div class="space-y-1">
                        <div class="h-4 bg-gray-200 rounded w-28 animate-pulse"></div>
                        <div class="h-3 bg-gray-200 rounded w-20 animate-pulse"></div>
                    </div>
                </x-table.cell>

                <!-- Stock -->
                <x-table.cell>
                    <div class="h-8 bg-gray-200 rounded-full w-32 animate-pulse"></div>
                </x-table.cell>

                <!-- Status -->
                <x-table.cell>
                    <div class="h-6 bg-gray-200 rounded-full w-16 animate-pulse"></div>
                </x-table.cell>

                <!-- Actions -->
                <x-table.cell align="right">
                    <div class="flex items-center justify-end space-x-2">
                        <div class="w-9 h-9 bg-gray-200 rounded-lg animate-pulse"></div>
                        <div class="w-9 h-9 bg-gray-200 rounded-lg animate-pulse"></div>
                    </div>
                </x-table.cell>
            </x-table.row>
        @endfor
    </x-table.body>
</x-table.table>

<style>
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>
