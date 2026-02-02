<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $title }}</h3>
    <div class="space-y-4 max-h-64 overflow-y-auto">
        @forelse($activities as $activity)
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                    @if ($activity['color'] === 'blue') bg-blue-100 text-blue-600
                    @elseif($activity['color'] === 'purple') bg-purple-100 text-purple-600
                    @elseif($activity['color'] === 'green') bg-green-100 text-green-600
                    @else bg-gray-100 text-gray-600 @endif">
                    @if ($activity['icon'] === 'user-plus')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    @elseif($activity['icon'] === 'building')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    @elseif($activity['icon'] === 'credit-card')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $activity['message'] }}</p>
                    <p class="text-xs text-gray-500">{{ $activity['detail'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $activity['date']->diffForHumans() }}</p>
                </div>
            </div>
        @empty
            <p class="text-gray-500 text-sm text-center py-4">Aucune activité récente</p>
        @endforelse
    </div>
</div>
