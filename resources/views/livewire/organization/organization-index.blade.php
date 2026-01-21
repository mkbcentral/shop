<div x-data="{ showDeleteModal: false, orgToDelete: null, orgName: '' }"
     wire:poll.30s
     @organization-created.window="$wire.$refresh()"
     @organization-updated.window="$wire.$refresh()"
     @organization-deleted.window="$wire.$refresh()">
    <x-slot name="header">
        <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('dashboard')], ['label' => 'Organisations']]" />
    </x-slot>

    <div class="flex items-center justify-between mt-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Mes Organisations</h1>
            <p class="text-gray-500 mt-1">Gérez vos organisations et leurs magasins</p>
        </div>
        <x-form.button href="{{ route('organizations.create') }}" icon="plus">
            Nouvelle Organisation
        </x-form.button>
    </div>

    <!-- Toast Notifications -->
    <x-toast />

    <!-- Search and Filters -->
    <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-200 p-4 mt-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Search -->
            <x-form.search-input
                wire:model.live.debounce.300ms="search"
                wireModel="search"
                placeholder="Rechercher une organisation..."
            />

            <!-- Type Filter -->
            <div class="flex items-center space-x-2">
                <label for="type" class="text-sm font-medium text-gray-700 whitespace-nowrap">Type :</label>
                <select id="type" wire:model.live="type"
                    class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <option value="">Tous les types</option>
                    @foreach($types as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Organizations List -->
    <div class="space-y-4">
        @forelse ($organizations as $organization)
            <div wire:key="org-{{ $organization->id }}"
                class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition duration-150 {{ $currentOrganization?->id === $organization->id ? 'ring-2 ring-indigo-500' : '' }}">

                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <!-- Organization Info -->
                        <div class="flex items-start space-x-4">
                            <!-- Logo/Icon -->
                            <div class="flex-shrink-0">
                                @if($organization->logo)
                                    <img src="{{ Storage::url($organization->logo) }}" alt="{{ $organization->name }}"
                                        class="w-16 h-16 rounded-lg object-cover">
                                @else
                                    <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <div>
                                <div class="flex items-center space-x-2">
                                    <h3 class="text-lg font-bold text-gray-900">{{ $organization->name }}</h3>
                                    @if($organization->pivot)
                                        @if($organization->pivot->role === 'owner')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Propriétaire
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ ucfirst($organization->pivot->role) }}
                                            </span>
                                        @endif
                                    @endif
                                    @if($currentOrganization?->id === $organization->id)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @endif
                                </div>

                                <p class="text-sm text-gray-500 mt-1">{{ $organization->type_label }}</p>

                                @if($organization->legal_name)
                                    <p class="text-sm text-gray-600">{{ $organization->legal_name }}</p>
                                @endif

                                <!-- Stats -->
                                <div class="flex items-center space-x-4 mt-3 text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        {{ $organization->stores->count() }} magasin(s)
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                        </svg>
                                        {{ $organization->members->count() }} membre(s)
                                    </div>
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                            {{ $organization->subscription_plan === 'free' ? 'bg-gray-100 text-gray-800' : '' }}
                                            {{ $organization->subscription_plan === 'starter' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $organization->subscription_plan === 'professional' ? 'bg-purple-100 text-purple-800' : '' }}
                                            {{ $organization->subscription_plan === 'enterprise' ? 'bg-green-100 text-green-800' : '' }}">
                                            {{ $organization->plan_label }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-2">
                            @if($currentOrganization?->id !== $organization->id)
                                <x-form.button wire:click="switchTo({{ $organization->id }})" size="sm" icon="arrow-right">
                                    Basculer
                                </x-form.button>
                            @endif

                            <x-form.button href="{{ route('organizations.show', $organization) }}" variant="secondary" size="sm" icon="eye">
                                Voir
                            </x-form.button>

                            @can('update', $organization)
                                <x-form.button href="{{ route('organizations.edit', $organization) }}" variant="secondary" size="sm" icon="edit" />
                            @endcan

                            @can('manageMembers', $organization)
                                <x-form.button href="{{ route('organizations.members', $organization) }}" variant="secondary" size="sm" icon="users" />
                            @endcan
                        </div>
                    </div>

                    <!-- Stores Preview -->
                    @if($organization->stores->isNotEmpty())
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Magasins</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($organization->stores->take(5) as $store)
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-700">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        {{ $store->name }}
                                    </span>
                                @endforeach
                                @if($organization->stores->count() > 5)
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-indigo-100 text-indigo-700">
                                        +{{ $organization->stores->count() - 5 }} autre(s)
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Aucune organisation</h3>
                <p class="mt-2 text-sm text-gray-500">Vous n'êtes membre d'aucune organisation pour le moment.</p>
                <div class="mt-6">
                    <x-form.button href="{{ route('organizations.create') }}" icon="plus">
                        Créer votre première organisation
                    </x-form.button>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($organizations->hasPages())
        <div class="mt-6">
            {{ $organizations->links() }}
        </div>
    @endif
</div>
