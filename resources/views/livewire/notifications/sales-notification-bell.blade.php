<div
    x-data="{ open: false }"
    @click.away="open = false"
    class="relative"
>
    <!-- Bell Button -->
    <button @click="open = !open"
        class="relative p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <!-- Bell Icon -->
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        <!-- Unread Badge -->
        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 flex items-center justify-center min-w-[20px] h-5 px-1.5 text-xs font-bold text-white bg-red-500 rounded-full animate-pulse">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown -->
    <div x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-96 bg-white rounded-xl shadow-2xl ring-1 ring-black ring-opacity-5 z-50 overflow-hidden">

        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-5 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-white/20 rounded-xl">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-bold">Notifications Ventes</h3>
                        <p class="text-white/70 text-xs">Suivi en temps réel</p>
                    </div>
                </div>
                @if($unreadCount > 0)
                    <span class="px-3 py-1 bg-white/20 rounded-full text-white text-xs font-bold">
                        {{ $unreadCount }} nouveau(x)
                    </span>
                @endif
            </div>
        </div>

        <!-- Actions Bar -->
        @if($notifications->count() > 0)
            <div class="flex items-center justify-between px-4 py-2 bg-gray-50 border-b border-gray-100">
                <button wire:click="markAllAsRead"
                    class="text-xs text-indigo-600 hover:text-indigo-800 font-medium transition-colors">
                    Tout marquer comme lu
                </button>
                <button wire:click="clearAll"
                    class="text-xs text-gray-500 hover:text-red-600 font-medium transition-colors">
                    Effacer tout
                </button>
            </div>
        @endif

        <!-- Notifications List -->
        <div class="max-h-[400px] overflow-y-auto custom-scrollbar">
            @forelse($notifications as $notification)
                @php
                    $data = $notification->data;
                    $isUnread = is_null($notification->read_at);
                    $colorClasses = match($data['color'] ?? 'gray') {
                        'green' => 'from-green-500 to-emerald-600',
                        'blue' => 'from-blue-500 to-cyan-600',
                        'yellow' => 'from-yellow-500 to-amber-600',
                        'indigo' => 'from-indigo-500 to-purple-600',
                        default => 'from-gray-500 to-gray-600',
                    };
                @endphp
                <div wire:click="markAsRead('{{ $notification->id }}')"
                    class="px-4 py-3 hover:bg-gray-50 cursor-pointer transition-colors border-b border-gray-50 {{ $isUnread ? 'bg-indigo-50/50' : '' }}">
                    <div class="flex items-start gap-3">
                        <!-- Icon -->
                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br {{ $colorClasses }} rounded-xl flex items-center justify-center shadow-lg">
                            @switch($data['icon'] ?? 'bell')
                                @case('cash')
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    @break
                                @case('chart-bar')
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    @break
                                @case('trophy')
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                    </svg>
                                    @break
                                @default
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                            @endswitch
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-semibold text-indigo-600 bg-indigo-100 px-2 py-0.5 rounded-full">
                                    {{ $data['store_name'] ?? 'Magasin' }}
                                </span>
                                @if($isUnread)
                                    <span class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse"></span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-800 font-medium mt-1 line-clamp-2">
                                {{ $data['message'] ?? 'Nouvelle notification' }}
                            </p>
                            <p class="text-xs text-gray-400 mt-1">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Empty State -->
                <div class="px-6 py-12 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h4 class="text-gray-700 font-semibold">Aucune notification</h4>
                    <p class="text-gray-400 text-sm mt-1">Les alertes de ventes apparaîtront ici</p>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
            <a href="{{ route('dashboard') }}"
                class="block w-full text-center text-sm text-indigo-600 hover:text-indigo-800 font-medium transition-colors">
                Voir le tableau de bord complet →
            </a>
        </div>
    </div>
</div>
