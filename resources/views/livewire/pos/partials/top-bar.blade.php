<!-- Top Bar -->
<div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white px-6 py-3 shadow-xl relative overflow-hidden flex-shrink-0">
    <div class="absolute inset-0 bg-black/10"></div>
    <div class="relative flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold tracking-tight">Point de Vente</h1>
                <p class="text-xs text-indigo-100 flex items-center gap-2">
                    <span class="px-2 py-0.5 bg-white/20 rounded-full text-xs font-semibold">Caisse #{{ auth()->id() }}</span>
                    <span>{{ auth()->user()->name }}</span>
                </p>
            </div>
        </div>
        <div class="flex items-center space-x-4">
            <!-- Statistiques du jour -->
            <button wire:click="toggleStats" @click="showStatsPanel = !showStatsPanel"
                class="flex items-center gap-2 px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-xl transition-all duration-200 backdrop-blur-sm hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <div class="text-left">
                    <div class="text-xs opacity-80">CA Aujourd'hui</div>
                    <div class="text-sm font-bold">@currency($todayStats['revenue'])</div>
                </div>
            </button>

            <!-- Mode Quick Sale -->
            <button wire:click="toggleQuickSaleMode"
                class="px-3 py-1.5 rounded-xl transition-all duration-200 backdrop-blur-sm font-semibold text-xs
                {{ $quickSaleMode ? 'bg-green-500/90 text-white hover:bg-green-600' : 'bg-white/20 hover:bg-white/30' }}">
                {{ $quickSaleMode ? '⚡ Mode Rapide' : '🐌 Mode Normal' }}
            </button>

            <div class="text-right">
                <div class="text-xs text-indigo-100 font-medium">{{ now()->format('d/m/Y') }}</div>
                <div class="text-lg font-bold tabular-nums" x-ref="clock">{{ now()->format('H:i:s') }}</div>
            </div>
            <a href="{{ route('dashboard') }}" wire:navigate
                class="p-2 bg-white/20 hover:bg-white/30 rounded-xl transition-all duration-200 backdrop-blur-sm hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </a>
        </div>
    </div>
</div>
