@props(['kpis'])

@php
    $showStock = has_stock_management();
@endphp

<div class="grid grid-cols-1 md:grid-cols-{{ $showStock ? 5 : 2 }} gap-3 mb-4">
    <!-- Total Products -->
    <x-kpi-card :title="'Total ' . products_label()" :value="$kpis['total_products']" color="indigo">
        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
        </svg>
    </x-kpi-card>

    <!-- Active Products -->
    <x-kpi-card :title="products_label() . ' Actifs'" :value="$kpis['active_products']" color="green">
        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </x-kpi-card>

    @if($showStock)
    <!-- Low Stock -->
    <x-kpi-card title="Stock Faible" :value="$kpis['low_stock_count']" color="orange" :clickable="true" wire-click="$set('stockLevelFilter', 'low')">
        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
    </x-kpi-card>

    <!-- Out of Stock -->
    <x-kpi-card title="Rupture" :value="$kpis['out_of_stock_count']" color="red">
        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </x-kpi-card>

    <!-- Total Stock Value -->
    <x-kpi-card title="Valeur Stock" :value="format_currency($kpis['total_stock_value'])" color="purple">
        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </x-kpi-card>
    @endif
</div>
