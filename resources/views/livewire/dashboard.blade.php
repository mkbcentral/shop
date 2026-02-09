<div>
    <!-- Toast Notifications -->
    <x-toast />

    <!-- Include Product Modal -->
    <livewire:product.product-modal />

    <x-slot name="header">
        <x-breadcrumb :items="[['label' => 'Accueil']]" />

    </x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Tableau de bord</h1>
                <p class="text-gray-500 mt-1">{{ now()->translatedFormat('l d F Y') }}</p>
            </div>
            <div class="flex items-center gap-3">
                @if(has_stock_management())
                <a href="{{ route('stock.overview') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Vue Stock
                </a>
                @endif
            </div>
        </div>
        <!-- Stats Grid with Modern Cards -->
        <x-dashboard.stats-grid :todaySales="$today_sales" :salesGrowth="$sales_growth" :totalProducts="$total_products" :totalStockValue="$total_stock_value"
            :lowStockAlerts="$low_stock_alerts" :outOfStockAlerts="$out_of_stock_alerts" :monthSales="$month_sales" :totalSales="$total_sales" />

        <!-- Charts and Analytics Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Sales Chart -->
            <x-dashboard.sales-chart :chartData="$sales_chart_data" />

            <!-- Top Products -->
            <x-dashboard.top-products :products="$top_products" />
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Quick Actions - Takes 2 columns -->
            <div class="lg:col-span-2">
                <x-dashboard.quick-actions />
            </div>

            <!-- Recent Activity & Stock Movements (only for non-service orgs) -->
            @if(has_stock_management())
                <x-dashboard.stock-movements :movements="$recent_movements" />
            @else
                <x-card>
                    <x-slot:header>
                        <x-card-title title="Activité Récente" />
                    </x-slot:header>
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p>Organisation de services</p>
                        <p class="text-xs text-gray-400 mt-1">La gestion de stock n'est pas nécessaire</p>
                    </div>
                </x-card>
            @endif
        </div>

        <!-- Recent Sales -->
        <x-dashboard.recent-sales :sales="$recent_sales" />
    </div>
</div>
