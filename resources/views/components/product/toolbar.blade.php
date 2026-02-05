@props(['selected', 'viewMode', 'densityMode', 'categoryFilter', 'statusFilter', 'perPage'])

<div class="flex items-center justify-between w-full">
    <!-- Left: Bulk Actions (fixed width to prevent layout shift) -->
    <div class="min-w-[240px]">
        @if(count($selected) > 0)
            <div class="flex items-center space-x-2 animate-in fade-in slide-in-from-left-3 duration-200">
                <span class="text-sm text-gray-600 whitespace-nowrap">{{ count($selected) }} sélectionné(s)</span>
                <x-form.select wire:model="bulkAction" class="text-sm min-w-[140px]">
                    <option value="">Actions groupées</option>
                    <option value="activate">Activer</option>
                    <option value="deactivate">Désactiver</option>
                    <option value="generate_labels">Générer Étiquettes</option>
                    <option value="delete">Supprimer</option>
                </x-form.select>
                <button wire:click="executeBulkAction"
                        class="px-3 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition whitespace-nowrap">
                    Appliquer
                </button>
            </div>
        @endif
    </div>

    <!-- Right: View Controls -->
    <div class="flex items-center space-x-3">
        <!-- View Mode Toggle -->
        <div class="flex items-center bg-gray-100 rounded-lg p-1">
        <button wire:click="$set('viewMode', 'table')"
                class="px-3 py-1.5 rounded {{ $viewMode === 'table' ? 'bg-white shadow-sm' : '' }} transition">
            <svg class="w-5 h-5 {{ $viewMode === 'table' ? 'text-indigo-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
            </svg>
        </button>
        <button wire:click="$set('viewMode', 'grid')"
                class="px-3 py-1.5 rounded {{ $viewMode === 'grid' ? 'bg-white shadow-sm' : '' }} transition">
            <svg class="w-5 h-5 {{ $viewMode === 'grid' ? 'text-indigo-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
        </button>
    </div>

    <!-- Export Menu -->
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="text-sm font-medium">Exporter</span>
            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="open" @click.away="open = false" x-cloak
             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-10">
            @hasfeature('export_excel')
                <button wire:click="exportExcel" @click="open = false" class="w-full text-left px-4 py-2.5 text-sm hover:bg-gray-50 flex items-center">
                    <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="font-medium text-gray-700">Exporter Excel</span>
                </button>
            @else
                <div class="w-full text-left px-4 py-2.5 text-sm text-gray-400 flex items-center cursor-not-allowed">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <span class="font-medium">Excel <span class="text-xs text-amber-600">(Starter+)</span></span>
                </div>
            @endhasfeature
            
            @hasfeature('export_pdf')
                <a href="{{ route('reports.products', ['category_id' => $categoryFilter, 'status' => $statusFilter]) }}"
                   @click="open = false" class="w-full text-left px-4 py-2.5 text-sm hover:bg-gray-50 flex items-center">
                    <svg class="w-5 h-5 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <span class="font-medium text-gray-700">Exporter PDF</span>
                </a>
            @else
                <div class="w-full text-left px-4 py-2.5 text-sm text-gray-400 flex items-center cursor-not-allowed">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <span class="font-medium">PDF <span class="text-xs text-amber-600">(Pro+)</span></span>
                </div>
            @endhasfeature
        </div>
    </div>

    <!-- Density Mode -->
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" class="px-3 py-2 text-gray-600 hover:text-gray-900 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
            </svg>
        </button>
        <div x-show="open" @click.away="open = false" x-cloak
             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-10">
            <button wire:click="$set('densityMode', 'compact')" @click="open = false"
                    class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 flex items-center justify-between {{ $densityMode === 'compact' ? 'bg-indigo-50 text-indigo-700 font-medium' : '' }}">
                <span>Vue compacte</span>
                @if($densityMode === 'compact')
                    <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                @endif
            </button>
            <button wire:click="$set('densityMode', 'comfortable')" @click="open = false"
                    class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 flex items-center justify-between {{ $densityMode === 'comfortable' ? 'bg-indigo-50 text-indigo-700 font-medium' : '' }}">
                <span>Vue normale</span>
                @if($densityMode === 'comfortable')
                    <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                @endif
            </button>
            <button wire:click="$set('densityMode', 'spacious')" @click="open = false"
                    class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 flex items-center justify-between {{ $densityMode === 'spacious' ? 'bg-indigo-50 text-indigo-700 font-medium' : '' }}">
                <span>Vue étendue</span>
                @if($densityMode === 'spacious')
                    <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                @endif
            </button>
        </div>
    </div>

    <!-- Per Page -->
    <x-form.select wire:model.live="perPage" class="text-sm">
        <option value="15">15 par page</option>
        <option value="25">25 par page</option>
        <option value="50">50 par page</option>
    </x-form.select>
</div>
