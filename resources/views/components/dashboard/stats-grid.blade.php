@props([
    'todaySales',
    'salesGrowth',
    'totalProducts',
    'totalStockValue',
    'lowStockAlerts',
    'outOfStockAlerts',
    'monthSales',
    'totalSales'
])

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <x-stat-card
        title="Ventes Aujourd'hui"
        :value="format_currency($todaySales)"
        color="blue"
        :trend="'Croissance: ' . number_format($salesGrowth, 1) . '%'"
        :trendUp="$salesGrowth > 0">
        <x-slot:icon>
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </x-slot:icon>
    </x-stat-card>

    <x-stat-card
        title="Total Produits"
        :value="number_format($totalProducts)"
        color="green"
        :trend="'Valeur: ' . format_currency($totalStockValue)"
        :trendUp="true">
        <x-slot:icon>
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </x-slot:icon>
    </x-stat-card>

    <x-stat-card
        title="Stock Bas"
        :value="number_format($lowStockAlerts)"
        color="orange"
        :trend="$outOfStockAlerts . ' en rupture'"
        :trendUp="false">
        <x-slot:icon>
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </x-slot:icon>
    </x-stat-card>

    <x-stat-card
        title="Ventes ce Mois"
        :value="format_currency($monthSales)"
        color="purple"
        :trend="$totalSales . ' transactions'"
        :trendUp="true">
        <x-slot:icon>
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
        </x-slot:icon>
    </x-stat-card>
</div>
