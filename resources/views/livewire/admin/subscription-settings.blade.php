<div x-data="{ showModal: false, isEditing: true }"
     @open-plan-modal.window="showModal = true"
     @close-plan-modal.window="showModal = false; $wire.editingPlanId = null; $wire.editForm = {}">
    <x-slot name="header">
        <x-breadcrumb :items="[
            ['label' => 'Accueil', 'url' => route('dashboard')],
            ['label' => 'Administration'],
            ['label' => 'Paramètres Abonnements']
        ]" />
    </x-slot>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between mt-4">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Paramètres des Abonnements</h1>
                <p class="text-gray-600 mt-2 flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Configurez les plans, prix et limites des abonnements
                </p>
            </div>
            <button wire:click="resetToDefaults"
                wire:confirm="Êtes-vous sûr de vouloir réinitialiser tous les paramètres ?"
                wire:loading.attr="disabled"
                wire:target="resetToDefaults"
                class="group inline-flex items-center px-4 py-2.5 text-sm font-semibold text-red-600 bg-white hover:bg-red-50 border-2 border-red-200 hover:border-red-300 rounded-xl transition-all shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                <svg wire:loading.remove wire:target="resetToDefaults" class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <svg wire:loading wire:target="resetToDefaults" class="animate-spin w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="resetToDefaults">Réinitialiser par défaut</span>
                <span wire:loading wire:target="resetToDefaults">Réinitialisation...</span>
            </button>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-md border border-gray-100 p-5 hover:shadow-lg transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Total Organisations</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_organizations']) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-white to-green-50 rounded-xl shadow-md border border-green-100 p-5 hover:shadow-lg transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Revenus Total</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} {{ $currency }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-white to-blue-50 rounded-xl shadow-md border border-blue-100 p-5 hover:shadow-lg transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Revenus ce mois</p>
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['monthly_revenue'], 0, ',', ' ') }} {{ $currency }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-white to-purple-50 rounded-xl shadow-md border border-purple-100 p-5 hover:shadow-lg transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Plans payants</p>
                        <p class="text-2xl font-bold text-purple-600">
                            @php
                                $paidPlansCount = collect($stats['by_plan'])->except('free')->sum();
                            @endphp
                            {{ $paidPlansCount }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Distribution by Plan -->
        <div class="bg-gradient-to-br from-white to-indigo-50 rounded-xl shadow-md border border-indigo-100 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Distribution par plan</h3>
            </div>
            <div class="grid grid-cols-4 gap-4">
                @foreach($plans as $slug => $plan)
                    @php
                        $colorClass = match($plan['color']) {
                            'gray' => 'text-gray-600',
                            'blue' => 'text-blue-600',
                            'purple' => 'text-purple-600',
                            'amber' => 'text-amber-600',
                            'indigo' => 'text-indigo-600',
                            default => 'text-indigo-600'
                        };
                    @endphp
                    <div class="text-center p-4 rounded-xl bg-white border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                        <p class="text-3xl font-bold {{ $colorClass }}">
                            {{ $stats['by_plan'][$slug] ?? 0 }}
                        </p>
                        <p class="text-sm text-gray-500 font-medium mt-1">{{ $plan['name'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Plans Configuration -->
        <div class="bg-gradient-to-br from-white to-purple-50 rounded-xl shadow-md border border-purple-100 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Configuration des Plans</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($plans as $plan)
                    <div class="relative bg-white border-2 rounded-xl p-5 transition-all hover:shadow-lg
                        @if($plan['is_popular']) border-indigo-500 ring-2 ring-indigo-100
                        @else border-gray-200 hover:border-indigo-300
                        @endif">

                        @if($plan['is_popular'])
                            <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-md">
                                    ⭐ Populaire
                                </span>
                            </div>
                        @endif

                        <div class="text-center mb-4 pt-2">
                            <h4 class="text-lg font-bold text-gray-900">{{ $plan['name'] }}</h4>
                            <div class="mt-2">
                                <span class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                                    {{ number_format($plan['price'], 0, ',', ' ') }}
                                </span>
                                <span class="text-sm text-gray-500">{{ $currency }}/mois</span>
                            </div>
                        </div>

                        <div class="space-y-2 text-sm mb-4 bg-gray-50 rounded-lg p-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Magasins</span>
                                <span class="font-semibold text-gray-900">{{ $plan['max_stores'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Utilisateurs</span>
                                <span class="font-semibold text-gray-900">{{ $plan['max_users'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Produits</span>
                                <span class="font-semibold text-gray-900">{{ number_format($plan['max_products'], 0, ',', ' ') }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col space-y-2">
                            <div class="flex space-x-2">
                                <button wire:click="openEditModal({{ $plan['id'] }})"
                                    wire:loading.attr="disabled"
                                    wire:target="openEditModal({{ $plan['id'] }})"
                                    class="flex-1 px-3 py-2.5 text-sm font-semibold bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl transition-all shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span wire:loading.remove wire:target="openEditModal({{ $plan['id'] }})">
                                        Modifier
                                    </span>
                                    <span wire:loading wire:target="openEditModal({{ $plan['id'] }})" class="inline-flex items-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Chargement...
                                    </span>
                                </button>
                                <button wire:click="togglePopular({{ $plan['id'] }})"
                                    wire:loading.attr="disabled"
                                    wire:target="togglePopular({{ $plan['id'] }})"
                                    class="px-3 py-2.5 text-sm border-2 border-gray-200 hover:border-amber-400 hover:bg-amber-50 rounded-xl transition-all disabled:opacity-50"
                                    title="Marquer comme populaire">
                                    <span wire:loading.remove wire:target="togglePopular({{ $plan['id'] }})">⭐</span>
                                    <span wire:loading wire:target="togglePopular({{ $plan['id'] }})" class="inline-flex">
                                        <svg class="animate-spin h-4 w-4 text-amber-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                            <button wire:click="openFeaturesModal({{ $plan['id'] }})"
                                wire:loading.attr="disabled"
                                wire:target="openFeaturesModal({{ $plan['id'] }})"
                                class="w-full px-3 py-2 text-sm font-medium text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border-2 border-emerald-200 hover:border-emerald-300 rounded-xl transition-all disabled:opacity-50"
                                title="Gérer les fonctionnalités techniques">
                                <span wire:loading.remove wire:target="openFeaturesModal({{ $plan['id'] }})" class="flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Fonctionnalités
                                </span>
                                <span wire:loading wire:target="openFeaturesModal({{ $plan['id'] }})" class="inline-flex items-center justify-center">
                                    <svg class="animate-spin h-4 w-4 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Edit Plan Modal -->
    <x-ui.alpine-modal name="plan" max-width="lg"
        edit-title="Modifier le plan"
        icon-bg="from-indigo-500 to-purple-600">
        <x-slot:icon>
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
        </x-slot:icon>

        <form wire:submit="savePlan" wire:key="plan-form-{{ $editingPlanId ?? 'new' }}">
            <x-ui.alpine-modal-body>
                <!-- Nom du plan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom du plan <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="editForm.name"
                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900 transition-all"
                        placeholder="Ex: Starter">
                    @error('editForm.name') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Prix mensuel -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prix mensuel ({{ $currency }}) @if(($editForm['slug'] ?? '') !== 'free')<span class="text-red-500">*</span>@endif</label>
                    <input type="number" wire:model="editForm.price" min="0" step="100"
                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900 transition-all disabled:bg-gray-100 disabled:cursor-not-allowed"
                        placeholder="Ex: 9900" @if(($editForm['slug'] ?? '') === 'free') disabled @endif>
                    @if(($editForm['slug'] ?? '') === 'free')
                        <p class="text-xs text-gray-500 mt-1">Le plan gratuit ne peut pas avoir de prix</p>
                    @endif
                    @error('editForm.price') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Limites -->
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Max Magasins <span class="text-red-500">*</span></label>
                        <input type="number" wire:model="editForm.max_stores" min="1"
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900 transition-all"
                            placeholder="1">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Max Utilisateurs <span class="text-red-500">*</span></label>
                        <input type="number" wire:model="editForm.max_users" min="1"
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900 transition-all"
                            placeholder="3">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Max Produits <span class="text-red-500">*</span></label>
                        <input type="number" wire:model="editForm.max_products" min="1"
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900 transition-all"
                            placeholder="100">
                    </div>
                </div>

                <!-- Fonctionnalités -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fonctionnalités (une par ligne)</label>
                    <textarea wire:model="editForm.features_text" rows="6"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900 placeholder-gray-400 transition-all"
                        placeholder="Jusqu'à X magasins&#10;Support prioritaire&#10;Rapports avancés"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Entrez une fonctionnalité par ligne</p>
                </div>
            </x-ui.alpine-modal-body>

            <x-ui.alpine-modal-footer
                edit-submit-text="Enregistrer les modifications"
                target="savePlan"
            />
        </form>
    </x-ui.alpine-modal>

    <!-- Features Modal -->
    @if($showFeaturesModal)
    <div x-data="{ open: true }" x-show="open"
         class="fixed inset-0 z-50 overflow-y-auto"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" wire:click="closeFeaturesModal"></div>

        <!-- Modal Content -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-4xl bg-white rounded-2xl shadow-2xl"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 @click.stop>

                <!-- Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-100">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Fonctionnalités techniques</h3>
                            <p class="text-sm text-gray-500">
                                Plan: <span class="font-semibold text-emerald-600">{{ $editingFeaturesPlanName ?? '' }}</span>
                            </p>
                        </div>
                    </div>
                    <button wire:click="closeFeaturesModal"
                        class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="p-6 max-h-[60vh] overflow-y-auto">
                    <p class="text-sm text-gray-600 mb-6">
                        Sélectionnez les fonctionnalités disponibles pour ce plan. Ces fonctionnalités contrôlent l'accès aux différentes parties de l'application.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($featureCategories as $categoryKey => $categoryLabel)
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                <h4 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                    @switch($categoryKey)
                                        @case('core')
                                            <span class="w-6 h-6 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-xs">📦</span>
                                            @break
                                        @case('reports')
                                            <span class="w-6 h-6 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center text-xs">📊</span>
                                            @break
                                        @case('stores')
                                            <span class="w-6 h-6 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center text-xs">🏪</span>
                                            @break
                                        @case('export')
                                            <span class="w-6 h-6 bg-green-100 text-green-600 rounded-lg flex items-center justify-center text-xs">📁</span>
                                            @break
                                        @case('integration')
                                            <span class="w-6 h-6 bg-cyan-100 text-cyan-600 rounded-lg flex items-center justify-center text-xs">🔗</span>
                                            @break
                                        @case('limits')
                                            <span class="w-6 h-6 bg-red-100 text-red-600 rounded-lg flex items-center justify-center text-xs">⚡</span>
                                            @break
                                        @case('support')
                                            <span class="w-6 h-6 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center text-xs">💬</span>
                                            @break
                                        @case('enterprise')
                                            <span class="w-6 h-6 bg-gray-700 text-white rounded-lg flex items-center justify-center text-xs">🏢</span>
                                            @break
                                        @default
                                            <span class="w-6 h-6 bg-gray-100 text-gray-600 rounded-lg flex items-center justify-center text-xs">⚙️</span>
                                    @endswitch
                                    {{ $categoryLabel }}
                                </h4>

                                <div class="space-y-2">
                                    @foreach($availableFeatures as $featureKey => $feature)
                                        @if($feature['category'] === $categoryKey)
                                            <label class="flex items-start gap-3 p-2 rounded-lg hover:bg-white cursor-pointer transition-colors group">
                                                <input type="checkbox"
                                                    wire:click="toggleFeature('{{ $featureKey }}')"
                                                    @checked(in_array($featureKey, $selectedFeatures))
                                                    class="w-5 h-5 mt-0.5 text-emerald-600 border-2 border-gray-300 rounded focus:ring-emerald-500 focus:ring-2 cursor-pointer">
                                                <div class="flex-1">
                                                    <span class="text-sm font-medium text-gray-800 group-hover:text-emerald-700">
                                                        {{ $feature['label'] }}
                                                    </span>
                                                    <p class="text-xs text-gray-500 mt-0.5">{{ $feature['description'] }}</p>
                                                </div>
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Features Summary -->
                    <div class="mt-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-sm font-medium text-emerald-800">
                                    {{ count($selectedFeatures) }} fonctionnalité(s) activée(s) sur {{ count($availableFeatures) }}
                                </span>
                            </div>
                            <div class="flex gap-2">
                                <button type="button"
                                    wire:click="selectAllFeatures"
                                    class="text-xs px-2 py-1 text-emerald-700 hover:bg-emerald-100 rounded transition-colors">
                                    Tout sélectionner
                                </button>
                                <span class="text-emerald-300">|</span>
                                <button type="button"
                                    wire:click="deselectAllFeatures"
                                    class="text-xs px-2 py-1 text-emerald-700 hover:bg-emerald-100 rounded transition-colors">
                                    Tout désélectionner
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
                    <button type="button" wire:click="closeFeaturesModal"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border-2 border-gray-200 hover:border-gray-300 hover:bg-gray-50 rounded-xl transition-all">
                        Annuler
                    </button>
                    <button type="button" wire:click="saveTechnicalFeatures"
                        wire:loading.attr="disabled"
                        wire:target="saveTechnicalFeatures"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 rounded-xl shadow-md hover:shadow-lg transition-all disabled:opacity-50">
                        <span wire:loading.remove wire:target="saveTechnicalFeatures" class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Enregistrer
                        </span>
                        <span wire:loading wire:target="saveTechnicalFeatures" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Enregistrement...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Toast -->
    <x-toast />
</div>
