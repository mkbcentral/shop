<div>
    @if($organizationId)
        {{-- Filters --}}
        <div class="mb-4 bg-gray-50 rounded-lg p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Action Filter --}}
                <div>
                    <label for="actionFilter" class="block text-sm font-medium text-gray-700 mb-1">Action</label>
                    <select 
                        id="actionFilter" 
                        wire:model.live="actionFilter"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    >
                        <option value="">Toutes les actions</option>
                        @foreach($actions as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Date From --}}
                <div>
                    <label for="dateFrom" class="block text-sm font-medium text-gray-700 mb-1">Du</label>
                    <input 
                        type="date" 
                        id="dateFrom" 
                        wire:model.live="dateFrom"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    >
                </div>

                {{-- Date To --}}
                <div>
                    <label for="dateTo" class="block text-sm font-medium text-gray-700 mb-1">Au</label>
                    <input 
                        type="date" 
                        id="dateTo" 
                        wire:model.live="dateTo"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    >
                </div>
            </div>

            @if($actionFilter || $dateFrom || $dateTo)
                <div class="mt-3">
                    <button 
                        type="button" 
                        wire:click="resetFilters"
                        class="text-sm text-indigo-600 hover:text-indigo-800"
                    >
                        Réinitialiser les filtres
                    </button>
                </div>
            @endif
        </div>

        {{-- History Timeline --}}
        <div class="space-y-4 max-h-[400px] overflow-y-auto">
            @forelse($history as $entry)
                <div class="relative pl-8 pb-4 {{ !$loop->last ? 'border-l-2 border-gray-200' : '' }}" wire:key="history-{{ $entry->id }}">
                    {{-- Timeline Dot --}}
                    <div class="absolute left-0 top-0 -translate-x-1/2 w-4 h-4 rounded-full 
                        @switch($entry->action_color)
                            @case('blue') bg-blue-500 @break
                            @case('green') bg-green-500 @break
                            @case('indigo') bg-indigo-500 @break
                            @case('yellow') bg-yellow-500 @break
                            @case('red') bg-red-500 @break
                            @case('purple') bg-purple-500 @break
                            @default bg-gray-400
                        @endswitch
                    "></div>

                    {{-- Entry Card --}}
                    <div class="bg-white rounded-lg border border-gray-200 p-4 ml-4 shadow-sm">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                {{-- Action Badge --}}
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @switch($entry->action_color)
                                        @case('blue') bg-blue-100 text-blue-800 @break
                                        @case('green') bg-green-100 text-green-800 @break
                                        @case('indigo') bg-indigo-100 text-indigo-800 @break
                                        @case('yellow') bg-yellow-100 text-yellow-800 @break
                                        @case('red') bg-red-100 text-red-800 @break
                                        @case('purple') bg-purple-100 text-purple-800 @break
                                        @default bg-gray-100 text-gray-800
                                    @endswitch
                                ">
                                    {{ $entry->action_label }}
                                </span>

                                {{-- Plan Info --}}
                                @if($entry->old_plan && $entry->new_plan && $entry->old_plan !== $entry->new_plan)
                                    <span class="ml-2 text-sm text-gray-500">
                                        {{ ucfirst($entry->old_plan) }} → {{ ucfirst($entry->new_plan) }}
                                    </span>
                                @elseif($entry->new_plan)
                                    <span class="ml-2 text-sm text-gray-500">
                                        Plan {{ ucfirst($entry->new_plan) }}
                                    </span>
                                @endif

                                {{-- Date Range --}}
                                @if($entry->subscription_starts_at || $entry->subscription_ends_at)
                                    <div class="mt-2 text-sm text-gray-600">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        @if($entry->subscription_starts_at)
                                            {{ $entry->subscription_starts_at->format('d/m/Y') }}
                                        @endif
                                        @if($entry->subscription_starts_at && $entry->subscription_ends_at)
                                            -
                                        @endif
                                        @if($entry->subscription_ends_at)
                                            {{ $entry->subscription_ends_at->format('d/m/Y') }}
                                        @else
                                            <span class="text-gray-400">(Illimité)</span>
                                        @endif
                                    </div>
                                @endif

                                {{-- Notes --}}
                                @if($entry->notes)
                                    <p class="mt-2 text-sm text-gray-500 italic">{{ $entry->notes }}</p>
                                @endif

                                {{-- Amount --}}
                                @if($entry->amount && $entry->amount > 0)
                                    <div class="mt-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                            {{ number_format($entry->amount, 0, ',', ' ') }} {{ $entry->currency }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            {{-- Timestamp --}}
                            <div class="text-right text-xs text-gray-400 ml-4">
                                <div>{{ $entry->created_at->format('d/m/Y') }}</div>
                                <div>{{ $entry->created_at->format('H:i') }}</div>
                                @if($entry->user)
                                    <div class="mt-1 text-gray-500">
                                        par {{ $entry->user->name }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="mt-2">Aucun historique trouvé</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($history instanceof \Illuminate\Pagination\LengthAwarePaginator && $history->hasPages())
            <div class="mt-4 pt-4 border-t border-gray-200">
                {{ $history->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-8 text-gray-500">
            <p>Sélectionnez une organisation pour voir son historique d'abonnement.</p>
        </div>
    @endif
</div>
