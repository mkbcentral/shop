<div class="relative" x-data="{ open: @entangle('showDropdown') }" @click.away="open = false">
    @if($currentOrganization)
        <!-- Trigger Button -->
        <button @click="open = !open" type="button"
            class="flex items-center space-x-2 px-3 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition text-sm">

            <!-- Organization Icon/Logo -->
            @if($currentOrganization->logo)
                <img src="{{ Storage::url($currentOrganization->logo) }}" alt="" class="w-6 h-6 rounded object-cover">
            @else
                <div class="w-6 h-6 rounded bg-indigo-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            @endif

            <span class="font-medium text-gray-700 max-w-32 truncate">{{ $currentOrganization->name }}</span>

            <svg class="w-4 h-4 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <!-- Dropdown Menu -->
        <div x-show="open"
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50"
             style="display: none;">

            <!-- Header -->
            <div class="px-4 py-2 border-b border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Mes Organisations</p>
            </div>

            <!-- Organizations List -->
            <div class="max-h-80 overflow-y-auto">
                @foreach($organizations as $organization)
                    <button wire:click="switchOrganization({{ $organization->id }})"
                        class="w-full px-4 py-3 hover:bg-gray-50 transition flex items-start space-x-3 text-left {{ $currentOrganization->id === $organization->id ? 'bg-indigo-50' : '' }}">

                        <!-- Logo/Icon -->
                        @if($organization->logo)
                            <img src="{{ Storage::url($organization->logo) }}" alt="" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                        @else
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-2">
                                <span class="font-medium text-gray-900 truncate">{{ $organization->name }}</span>
                                @if($currentOrganization->id === $organization->id)
                                    <svg class="w-4 h-4 text-indigo-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500">{{ $organization->stores->count() }} magasin(s)</p>

                            <!-- Stores list preview -->
                            @if($organization->stores->isNotEmpty())
                                <div class="mt-1 flex flex-wrap gap-1">
                                    @foreach($organization->stores->take(3) as $store)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs bg-gray-100 text-gray-600">
                                            {{ Str::limit($store->name, 12) }}
                                        </span>
                                    @endforeach
                                    @if($organization->stores->count() > 3)
                                        <span class="text-xs text-gray-400">+{{ $organization->stores->count() - 3 }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </button>
                @endforeach
            </div>

            <!-- Footer Actions -->
            <div class="border-t border-gray-100 mt-2 pt-2">
                <a href="{{ route('organizations.index') }}" wire:navigate @click="open = false"
                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    Gérer les organisations
                </a>
                <a href="{{ route('organizations.create') }}" wire:navigate @click="open = false"
                    class="flex items-center px-4 py-2 text-sm text-indigo-600 hover:bg-indigo-50 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Nouvelle organisation
                </a>
            </div>
        </div>
    @else
        <!-- No Organization -->
        <a href="{{ route('organizations.create') }}" wire:navigate
            class="flex items-center space-x-2 px-3 py-2 rounded-lg bg-indigo-100 hover:bg-indigo-200 text-indigo-700 transition text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            <span class="font-medium">Créer une organisation</span>
        </a>
    @endif
</div>
