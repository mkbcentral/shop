<div>
    <x-slot name="header">
        <x-breadcrumb :items="[
            ['label' => 'Accueil', 'url' => route('dashboard')],
            ['label' => 'Organisations', 'url' => route('organizations.index')],
            ['label' => $organization->name]
        ]" />
    </x-slot>

    <!-- Header with Actions -->
    <div class="flex items-start justify-between mt-6">
        <div class="flex items-start space-x-4">
            <!-- Logo -->
            @if($organization->logo)
                <img src="{{ Storage::url($organization->logo) }}" alt="{{ $organization->name }}"
                    class="w-20 h-20 rounded-xl object-cover shadow-sm">
            @else
                <div class="w-20 h-20 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-sm">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            @endif

            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $organization->name }}</h1>
                <div class="flex items-center space-x-3 mt-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $organization->subscription_plan === 'free' ? 'bg-gray-100 text-gray-800' : '' }}
                        {{ $organization->subscription_plan === 'starter' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $organization->subscription_plan === 'professional' ? 'bg-purple-100 text-purple-800' : '' }}
                        {{ $organization->subscription_plan === 'enterprise' ? 'bg-green-100 text-green-800' : '' }}">
                        {{ $organization->plan_label }}
                    </span>
                    <span class="text-gray-500">•</span>
                    <span class="text-gray-600">{{ $organization->type_label }}</span>
                    @if($organization->legal_form)
                        <span class="text-gray-500">•</span>
                        <span class="text-gray-600">{{ $organization->legal_form }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex items-center space-x-2">
            @can('manageSubscription', $organization)
                <x-form.button href="{{ route('organizations.subscription', $organization) }}" wire:navigate variant="secondary" icon="credit-card">
                    Abonnement
                </x-form.button>
            @endcan
            @can('update', $organization)
                <x-form.button href="{{ route('organizations.edit', $organization) }}" wire:navigate variant="secondary" icon="edit">
                    Modifier
                </x-form.button>
            @endcan
            @can('manageMembers', $organization)
                <x-form.button href="{{ route('organizations.members', $organization) }}" wire:navigate icon="users">
                    Gérer les membres
                </x-form.button>
            @endcan
        </div>
    </div>

    <!-- Toast -->
    <x-toast />

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
        <!-- Stores -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Magasins</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['stores']['total'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">sur {{ $organization->max_stores ?? '∞' }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Members -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Membres</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['members']['total'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">sur {{ $organization->max_users ?? '∞' }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Stores -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Magasins actifs</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['stores']['active'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ ($statistics['stores']['total'] ?? 0) ? round((($statistics['stores']['active'] ?? 0)/($statistics['stores']['total']))*100) : 0 }}% actifs</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Invitations -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Invitations</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['pending_invitations'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">En attente</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Details Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Informations détaillées</h2>
                </div>
                <div class="p-6 space-y-4">
                    @if($organization->legal_name)
                        <div class="flex justify-between py-2">
                            <span class="text-sm font-medium text-gray-600">Raison sociale</span>
                            <span class="text-sm text-gray-900">{{ $organization->legal_name }}</span>
                        </div>
                    @endif
                    @if($organization->tax_id)
                        <div class="flex justify-between py-2">
                            <span class="text-sm font-medium text-gray-600">NIF / RCCM</span>
                            <span class="text-sm text-gray-900 font-mono">{{ $organization->tax_id }}</span>
                        </div>
                    @endif
                    @if($organization->registration_number)
                        <div class="flex justify-between py-2">
                            <span class="text-sm font-medium text-gray-600">N° Immatriculation</span>
                            <span class="text-sm text-gray-900 font-mono">{{ $organization->registration_number }}</span>
                        </div>
                    @endif
                    @if($organization->email)
                        <div class="flex justify-between py-2">
                            <span class="text-sm font-medium text-gray-600">Email</span>
                            <a href="mailto:{{ $organization->email }}" class="text-sm text-indigo-600 hover:text-indigo-700">{{ $organization->email }}</a>
                        </div>
                    @endif
                    @if($organization->phone)
                        <div class="flex justify-between py-2">
                            <span class="text-sm font-medium text-gray-600">Téléphone</span>
                            <a href="tel:{{ $organization->phone }}" class="text-sm text-indigo-600 hover:text-indigo-700">{{ $organization->phone }}</a>
                        </div>
                    @endif
                    @if($organization->website)
                        <div class="flex justify-between py-2">
                            <span class="text-sm font-medium text-gray-600">Site web</span>
                            <a href="{{ $organization->website }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-700">{{ $organization->website }}</a>
                        </div>
                    @endif
                    @if($organization->address)
                        <div class="flex justify-between py-2">
                            <span class="text-sm font-medium text-gray-600">Adresse</span>
                            <span class="text-sm text-gray-900 text-right max-w-xs">{{ $organization->address }}</span>
                        </div>
                    @endif
                    @if($organization->city || $organization->country)
                        <div class="flex justify-between py-2">
                            <span class="text-sm font-medium text-gray-600">Localisation</span>
                            <span class="text-sm text-gray-900">{{ $organization->city }}{{ $organization->city && $organization->country ? ', ' : '' }}{{ $organization->country }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between py-2">
                        <span class="text-sm font-medium text-gray-600">Devise</span>
                        <span class="text-sm text-gray-900">{{ $organization->settings['currency'] ?? 'USD' }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-sm font-medium text-gray-600">Fuseau horaire</span>
                        <span class="text-sm text-gray-900">{{ $organization->settings['timezone'] ?? 'Africa/Kinshasa' }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-sm font-medium text-gray-600">Créée le</span>
                        <span class="text-sm text-gray-900">{{ $organization->created_at->format('d/m/Y à H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Stores List -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Magasins ({{ $organization->stores->count() }})</h2>
                    @can('createStore', $organization)
                        <x-form.button wire:click="openStoreModal" size="sm" icon="plus">
                            Ajouter
                        </x-form.button>
                    @else
                        <div class="text-xs text-gray-500">
                            Limite de {{ $organization->max_stores }} magasin(s) atteinte
                        </div>
                    @endcan
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($organization->stores as $store)
                        <div class="px-6 py-4 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $store->name }}</h3>
                                    <div class="flex items-center space-x-3 mt-1 text-sm text-gray-500">
                                        @if($store->store_number)
                                            <span class="font-mono">#{{ $store->store_number }}</span>
                                        @endif
                                        @if($store->address)
                                            <span>{{ Str::limit($store->address, 40) }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        {{ $store->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($store->status) }}
                                    </span>
                                    <a href="{{ route('stores.show', $store) }}"
                                        class="text-indigo-600 hover:text-indigo-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Aucun magasin</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Owner Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Propriétaire</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $organization->owner->name }}</p>
                            <p class="text-sm text-gray-500">{{ $organization->owner->email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subscription Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Abonnement</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $organization->plan_label }}</p>
                        <p class="text-sm text-gray-500 mt-1">Plan actuel</p>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Magasins</span>
                            <span class="font-medium">{{ $statistics['stores']['total'] ?? 0 }} / {{ $organization->max_stores ?? '∞' }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Utilisateurs</span>
                            <span class="font-medium">{{ $statistics['members']['total'] ?? 0 }} / {{ $organization->max_users ?? '∞' }}</span>
                        </div>
                    </div>

                    @if($organization->subscription_plan !== 'enterprise')
                        <x-form.button href="{{ route('organizations.edit', $organization) }}" wire:navigate :fullWidth="true">
                            Améliorer le plan
                        </x-form.button>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Actions rapides</h2>
                </div>
                <div class="p-4 space-y-2">
                    @can('manageMembers', $organization)
                        <a href="{{ route('organizations.members', $organization) }}" wire:navigate
                            class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg transition">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Gérer les membres
                        </a>
                    @endcan
                    @can('update', $organization)
                        <a href="{{ route('organizations.edit', $organization) }}" wire:navigate
                            class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg transition">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Modifier l'organisation
                        </a>
                    @endcan
                    <a href="{{ route('stores.index') }}" wire:navigate
                        class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg transition">
                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Voir tous les magasins
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Création Magasin -->
    <x-modal name="showStoreModal" maxWidth="xl" :showHeader="false">
        <div class="bg-white rounded-xl shadow-xl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Nouveau magasin</h3>
                </div>
                <button wire:click="closeStoreModal" type="button" class="text-gray-400 hover:text-gray-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <form wire:submit.prevent="saveStore">
                <div class="p-6 space-y-4">
                    <!-- Nom et Code sur la même ligne -->
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Nom -->
                        <x-form.form-group label="Nom du magasin" for="storeFormName" required>
                            <x-form.input wire:model="storeForm.name" id="storeFormName" type="text" />
                            <x-form.input-error for="storeForm.name" />
                        </x-form.form-group>

                        <!-- Code -->
                        <x-form.form-group label="Code" for="storeFormCode" hint="Généré automatiquement">
                            <x-form.input wire:model="storeForm.code" id="storeFormCode" type="text" disabled class="bg-gray-50 text-gray-500" />
                            <x-form.input-error for="storeForm.code" />
                        </x-form.form-group>
                    </div>

                    <!-- Adresse -->
                    <x-form.form-group label="Adresse" for="storeFormAddress">
                        <x-form.textarea wire:model="storeForm.address" id="storeFormAddress" rows="2" />
                        <x-form.input-error for="storeForm.address" />
                    </x-form.form-group>

                    <!-- Ville, Téléphone et Email sur la même ligne -->
                    <div class="grid grid-cols-3 gap-4">
                        <!-- Ville -->
                        <x-form.form-group label="Ville" for="storeFormCity">
                            <x-form.input wire:model="storeForm.city" id="storeFormCity" type="text" />
                            <x-form.input-error for="storeForm.city" />
                        </x-form.form-group>

                        <!-- Téléphone -->
                        <x-form.form-group label="Téléphone" for="storeFormPhone">
                            <x-form.input wire:model="storeForm.phone" id="storeFormPhone" type="tel" />
                            <x-form.input-error for="storeForm.phone" />
                        </x-form.form-group>

                        <!-- Email -->
                        <x-form.form-group label="Email" for="storeFormEmail">
                            <x-form.input wire:model="storeForm.email" id="storeFormEmail" type="email" />
                            <x-form.input-error for="storeForm.email" />
                        </x-form.form-group>
                    </div>

                    <!-- Options -->
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="storeForm.is_active"
                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Magasin actif</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="storeForm.is_main"
                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Magasin principal</span>
                        </label>
                    </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                            <x-form.button type="button" variant="secondary" wire:click="closeStoreModal">
                                Annuler
                            </x-form.button>
                            <x-form.button type="submit" variant="primary" wire:loading.attr="disabled" wire:target="saveStore">
                                <span wire:loading.remove wire:target="saveStore">Créer le magasin</span>
                                <span wire:loading wire:target="saveStore">Création...</span>
                            </x-form.button>
                        </div>
                    </div>
                </form>
            </div>
        </x-modal>
</div>
