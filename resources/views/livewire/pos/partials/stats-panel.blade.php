<!-- Stats Panel -->
<div x-show="showStatsPanel" x-transition x-cloak class="bg-white/80 backdrop-blur-sm border-b shadow-sm px-4 py-3">
    <div class="grid grid-cols-3 gap-4 mb-4">
        <!-- Stats Cards -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl p-3">
            <div class="flex items-center gap-2 mb-1">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-xs font-semibold text-green-700">Ventes</span>
            </div>
            <p class="text-2xl font-black text-green-900">{{ $todayStats['sales_count'] }}</p>
        </div>

        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl p-3">
            <div class="flex items-center gap-2 mb-1">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-xs font-semibold text-blue-700">Chiffre d'affaires</span>
            </div>
            <p class="text-lg font-black text-blue-900">@currency($todayStats['revenue'])</p>
        </div>

        <div class="bg-gradient-to-br from-purple-50 to-pink-50 border-2 border-purple-200 rounded-xl p-3">
            <div class="flex items-center gap-2 mb-1">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span class="text-xs font-semibold text-purple-700">Transactions</span>
            </div>
            <p class="text-2xl font-black text-purple-900">{{ $todayStats['transactions'] }}</p>
        </div>
    </div>

    <!-- Historique des transactions -->
    @if(count($transactionHistory) > 0)
    <div class="mt-3">
        <h3 class="text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Dernières transactions
        </h3>
        <div class="grid grid-cols-5 gap-2 max-h-20 overflow-y-auto">
            @foreach($transactionHistory as $transaction)
            <button wire:click="reprintTransaction({{ $transaction['id'] }})"
                class="bg-white border-2 border-gray-200 hover:border-indigo-400 rounded-lg p-2 text-left transition-all hover:shadow-md group">
                <div class="text-xs font-bold text-gray-900 truncate group-hover:text-indigo-600">{{ $transaction['invoice_number'] }}</div>
                <div class="text-xs text-gray-500 truncate">{{ $transaction['client'] }}</div>
                <div class="text-xs font-bold text-indigo-600 mt-1">@currency($transaction['total'])</div>
                <div class="text-xs text-gray-400">{{ $transaction['time'] }}</div>
            </button>
            @endforeach
        </div>
    </div>
    @endif
</div>
