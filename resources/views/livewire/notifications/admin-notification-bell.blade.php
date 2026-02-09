<div
    x-data="{ open: false }"
    @click.away="open = false"
    class="relative"
>
    {{-- Ne rien afficher si l'utilisateur n'est pas super-admin --}}
    @if(!$isSuperAdmin)
        <div class="hidden"></div>
    @else
        <!-- Bell Button -->
        <button @click="open = !open"
            class="relative p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <!-- Building Icon (for organizations) -->
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>

            <!-- Unread Badge -->
            @if($unreadCount > 0)
                <span class="absolute -top-1 -right-1 flex items-center justify-center min-w-[20px] h-5 px-1.5 text-xs font-bold text-white bg-indigo-500 rounded-full animate-pulse">
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
            <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-5 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white/20 rounded-xl">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-white font-bold">Nouvelles Organisations</h3>
                            <p class="text-white/70 text-xs">Inscriptions sur la plateforme</p>
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
                        $planColor = match($data['organization_plan'] ?? 'free') {
                            'starter' => 'from-blue-500 to-cyan-600',
                            'professional' => 'from-purple-500 to-violet-600',
                            'enterprise' => 'from-amber-500 to-orange-600',
                            default => 'from-gray-500 to-gray-600',
                        };
                        $paymentBadge = match($data['payment_status'] ?? 'pending') {
                            'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Payé'],
                            'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'En attente'],
                            default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => 'N/A'],
                        };
                    @endphp
                    <div wire:click="markAsRead('{{ $notification->id }}')"
                        class="px-4 py-3 hover:bg-gray-50 cursor-pointer transition-colors border-b border-gray-50 {{ $isUnread ? 'bg-indigo-50/50' : '' }}">
                        <div class="flex items-start gap-3">
                            <!-- Icon -->
                            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br {{ $planColor }} rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-xs font-semibold text-indigo-600 bg-indigo-100 px-2 py-0.5 rounded-full">
                                        {{ $data['organization_plan_label'] ?? 'Plan' }}
                                    </span>
                                    <span class="text-xs font-medium {{ $paymentBadge['bg'] }} {{ $paymentBadge['text'] }} px-2 py-0.5 rounded-full">
                                        {{ $paymentBadge['label'] }}
                                    </span>
                                    @if($isUnread)
                                        <span class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse"></span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-800 font-semibold mt-1 truncate">
                                    {{ $data['organization_name'] ?? 'Organisation' }}
                                </p>
                                <p class="text-xs text-gray-600 mt-0.5">
                                    <span class="font-medium">{{ $data['owner_name'] ?? 'Propriétaire' }}</span>
                                    <span class="text-gray-400 mx-1">•</span>
                                    <span>{{ $data['owner_email'] ?? '' }}</span>
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
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h4 class="text-gray-700 font-semibold">Aucune nouvelle organisation</h4>
                        <p class="text-gray-400 text-sm mt-1">Les inscriptions apparaîtront ici</p>
                    </div>
                @endforelse
            </div>

            <!-- Footer -->
            @if($notifications->count() > 0)
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
                    <a href="{{ route('organizations.index') }}"
                        class="flex items-center justify-center gap-2 text-sm text-indigo-600 hover:text-indigo-800 font-medium transition-colors">
                        <span>Voir toutes les organisations</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            @endif
        </div>
    @endif
</div>
